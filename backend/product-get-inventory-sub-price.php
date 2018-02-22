<?php

require_once('utility/PropertyLoader.php');

require_once('bean/InventoryBean.php');
require_once('dao/InventoryDao.php');

require_once('utility/RequestLoader.php');

$requestLoader = new RequestLoader();

$pid = $requestLoader -> getParameter('pid');
$productStatus = $requestLoader -> getParameter('productStatus');
$productColor = $requestLoader -> getParameter('productColor');
$productSize = $requestLoader -> getParameter('productSize');

$propertyLoader = new PropertyLoader();
$inventoryDao = new InventoryDao($propertyLoader);

$inventoryList = $inventoryDao -> findByPidAndProductStatusAndProductColorAndProductSize($pid, $productStatus, $productColor, $productSize);

echo json_encode($inventoryList[0]);

?>