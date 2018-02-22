<?php

require_once('utility/PropertyLoader.php');

require_once('bean/ProductBean.php');
require_once('dao/ProductDao.php');

require_once('utility/RequestLoader.php');

$requestLoader = new RequestLoader();

$pid = $requestLoader -> getParameter('pid');

$propertyLoader = new PropertyLoader();
$productDao = new ProductDao($propertyLoader);

$productDao -> updateViewCount($pid);

?>