<?php
require_once('utility/RequestResultWrap.php');
require_once('utility/RequestLoader.php');
require_once('utility/SessionLoader.php');
require_once('utility/PropertyLoader.php');
require_once('utility/AuthenticationLoader.php');
require_once('utility/TransactionHandler.php');
require_once('utility/CartLoader.php');
require_once('utility/ValidationHandler.php');
require_once('utility/EmailHandler.php');

require_once('bean/InventoryBean.php');
require_once('bean/MemberBean.php');
require_once('bean/OrderMadeBean.php');
require_once('bean/OrderSubBean.php');
require_once('bean/ProductBean.php');
require_once('bean/DiscountGroupBean.php');

require_once('dao/InventoryDao.php');
require_once('dao/MemberDao.php');
require_once('dao/OrderMadeDao.php');
require_once('dao/OrderSubDao.php');
require_once('dao/ProductDao.php');
require_once('dao/DiscountGroupDao.php');

$ack = new RequestResultWrap();

$requestLoader = new RequestLoader();
$form = $requestLoader -> getParameter('orderInfoForm');

$validationHandler = new ValidationHandler();
$sessionLoader = new SessionLoader();
$cartLoader = new CartLoader($sessionLoader);

//Check Madatory field
if (
	is_null($form['name']) ||
	is_null($form['email']) ||
	is_null($form['phone']) ||
	is_null($form['country'])
) {
	$ack->status = 'failure';
	$ack->message = 'oym.action.msg.trans.failure.missing.info';
}

else if (
	!$validationHandler->name($form['name']) ||
	!$validationHandler->email($form['email']) ||
	!$validationHandler->phone($form['phone']) ||
	!$validationHandler->address($form['country'])
) {
	$ack->status = 'failure';
	$ack->message = 'oym.action.msg.trans.failure.missing.info';
} 

else if (
	is_null($cartLoader->getAllItem()) ||
	sizeof($cartLoader->getAllItem()) == 0
) {
	$ack->status = 'failure';
	$ack->message = 'oym.action.msg.trans.failure.empty.cart';
}

else {
	$propertyLoader = new PropertyLoader();
	$inventoryDao = new InventoryDao($propertyLoader);
	$memberDao = new MemberDao($propertyLoader);
	$orderMadeDao = new OrderMadeDao($propertyLoader);
	$orderSubDao = new OrderSubDao($propertyLoader);
	$productDao = new ProductDao($propertyLoader);
	$discountGroupDao = new DiscountGroupDao($propertyLoader);
	
	$authenticationLoader = new AuthenticationLoader($sessionLoader, $memberDao);
	$transactionHandler = new TransactionHandler($cartLoader, $inventoryDao, $orderMadeDao, $orderSubDao, $productDao, $discountGroupDao);

	//Check stock
	if (!$transactionHandler->checkAllItemInCartAvailability()) {
		$ack->status = "failure";
		$ack->message = "oym.action.msg.trans.failure.no.stock";
	}
	else {
		//Make order
		$itemArray = $cartLoader->getAllItemArray();
		$discount = $transactionHandler->getDiscountGroupTriggered();
		$loggedInUser = $authenticationLoader -> getLoggedInUser();
		$username = $loggedInUser != null ? $loggedInUser->mUsername : 'Guest';

		$orderBean = $transactionHandler->addOrderAndSubOrder($username, $form, $itemArray, $discount);
		$cartLoader->clearCart();
		
		$emailHandler = new EmailHandler($propertyLoader);
		$emailHandler->sendOrderConfirmation($orderBean, $form['locale']);
		
		$orderDetail = array();
		$orderDetail['serial'] = $orderBean->oSerial;
		$orderDetail['phone'] = $orderBean->oPhone;
		$orderDetail['email'] = $orderBean->oEmail;
		
		$ack->status = "success";
		$ack->message = $orderDetail;
	}
}

echo json_encode($ack);

?>