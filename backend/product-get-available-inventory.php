<?php

require_once('utility/PropertyLoader.php');

require_once('bean/InventoryBean.php');
require_once('dao/InventoryDao.php');

require_once('utility/RequestLoader.php');

$requestLoader = new RequestLoader();

$pid = $requestLoader -> getParameter('pid');

$propertyLoader = new PropertyLoader();
$inventoryDao = new InventoryDao($propertyLoader);

$inventoryList = $inventoryDao -> findByPidAndQuantityNot($pid, 0);

echo json_encode($inventoryList);

?>