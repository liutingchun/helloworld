<?php

require_once('utility/RequestResultWrap.php');
require_once('utility/SessionLoader.php');
require_once('utility/CartLoader.php');

$result = new RequestResultWrap();

$sessionLoader = new SessionLoader();
$cartLoader = new CartLoader($sessionLoader);

$cart = $cartLoader -> getAllItem();

$result->status = "success";
$result->message = $cart;

echo json_encode($result);

?>