<?php

require_once('utility/PropertyLoader.php');

require_once('bean/DiscountGroupTagBean.php');
require_once('dao/DiscountGroupTagDao.php');

require_once('utility/RequestLoader.php');

$requestLoader = new RequestLoader();

$pid = $requestLoader -> getParameter('pid');

$propertyLoader = new PropertyLoader();
$discountGroupTagDao = new DiscountGroupTagDao($propertyLoader);

$discountGroupTagList = $discountGroupTagDao -> findByPid($pid);

echo json_encode($discountGroupTagList);

?>