<?php
require_once('utility/RequestResultWrap.php');
require_once('utility/SessionLoader.php');
require_once('utility/PropertyLoader.php');
require_once('utility/TransactionHandler.php');
require_once('utility/CartLoader.php');

require_once('bean/DiscountGroupBean.php');
require_once('dao/DiscountGroupDao.php');
require_once('bean/InventoryBean.php');
require_once('dao/InventoryDao.php');

$ack = new RequestResultWrap();

$sessionLoader = new SessionLoader();
$cartLoader = new CartLoader($sessionLoader);

$propertyLoader = new PropertyLoader();
$discountGroupDao = new DiscountGroupDao($propertyLoader);
$inventoryDao = new InventoryDao($propertyLoader);

$transactionHandler = new TransactionHandler($cartLoader, $inventoryDao, null, null, null, $discountGroupDao);

$ack->message = $transactionHandler->getDiscountGroupTriggered();
$ack->status = 'success';

echo json_encode($ack);

?>