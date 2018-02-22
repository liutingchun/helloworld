<?php
require_once('utility/RequestResultWrap.php');

require_once('utility/PropertyLoader.php');
require_once('utility/SessionLoader.php');
require_once('utility/RequestLoader.php');
require_once('utility/AuthenticationLoader.php');

require_once('bean/MemberBean.php');
require_once('dao/MemberDao.php');
require_once('bean/ProductBean.php');
require_once('dao/ProductDao.php');

$requestResultWrap = new RequestResultWrap();

$sessionLoader = new SessionLoader();
$propertyLoader = new PropertyLoader();

$memberDao = new MemberDao($propertyLoader);

$authenticationLoader = new AuthenticationLoader($sessionLoader, $memberDao);

if ($authenticationLoader->isAdmin()) {
	$requestLoader = new RequestLoader();

	$form = $requestLoader -> getParameter('productForm');

	$productBean = new ProductBean();
	
	$productBean->name = $form['productName'];
	$productBean->officalCode = 'N/A';
	$productBean->category = $form['category'];
	$productBean->brand = $form['brand'];
	$productBean->color = $form['color'];
	$productBean->sex = $form['gender'];
	$productBean->price = $form['price'];
	$productBean->fakeprice = $form['fakeprice'];
	$productBean->description = $form['description'];
	
	$productDao = new ProductDao($propertyLoader);

	$productDao->add($productBean);
	
	$directoryPath = dirname(dirname(__FILE__)) . DIRECTORY_SEPARATOR . 'img' . DIRECTORY_SEPARATOR . 'product' . DIRECTORY_SEPARATOR . $productBean->pid;
	mkdir($directoryPath, 0777, true); 
	
	$requestResultWrap->status = 'success';
	$requestResultWrap->message = $productBean;
}
else {
	$requestResultWrap->status = 'failure';
	$requestResultWrap->message = 'Access denied.';
}

echo json_encode($requestResultWrap);

?>