<?php

require_once('utility/PropertyLoader.php');
require_once('utility/RequestLoader.php');

require_once('bean/TagBean.php');
require_once('dao/TagDao.php');

$requestLoader = new RequestLoader();
$pid = $requestLoader -> getParameter('pid');

$propertyLoader = new PropertyLoader();
$tagDao = new TagDao($propertyLoader);

$tagList = $tagDao -> findByPId($pid);

echo json_encode($tagList);

?>