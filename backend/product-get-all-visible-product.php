<?php

require_once('utility/PropertyLoader.php');

require_once('bean/ProductBean.php');
require_once('dao/ProductDao.php');

$propertyLoader = new PropertyLoader();
$productDao = new ProductDao($propertyLoader);

$productList = $productDao -> findByHideNot('1');

echo json_encode($productList);

?>