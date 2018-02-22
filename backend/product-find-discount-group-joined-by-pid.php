<?php

require_once('utility/PropertyLoader.php');

require_once('bean/DiscountGroupBean.php');
require_once('dao/DiscountGroupDao.php');

require_once('utility/RequestLoader.php');

$requestLoader = new RequestLoader();

$pid = $requestLoader -> getParameter('pid');

$propertyLoader = new PropertyLoader();
$discountGroupDao = new DiscountGroupDao($propertyLoader);

$discountGroupList = $discountGroupDao -> joinDiscountGroupTagFindByPid($pid);

echo json_encode($discountGroupList);

?>