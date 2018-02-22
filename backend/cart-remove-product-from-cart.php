<?php

require_once('utility/RequestResultWrap.php');
require_once('utility/RequestLoader.php');
require_once('utility/SessionLoader.php');
require_once('utility/CartLoader.php');

$requestLoader = new RequestLoader();

$pid = $requestLoader -> getParameter('pid');
$productStatus = $requestLoader -> getParameter('productStatus');
$productColor = $requestLoader -> getParameter('productColor');
$productSize = $requestLoader -> getParameter('productSize');

$sessionLoader = new SessionLoader();
$cartLoader = new CartLoader($sessionLoader);

$cartLoader -> removeItem($pid, $productStatus, $productColor, $productSize);

$cart = $cartLoader -> getAllItem($pid, $productStatus, $productColor, $productSize);


echo json_encode($cart);

//echo $pid . " " . $productStatus . " " . $productColor . " " . $productSize;

?>