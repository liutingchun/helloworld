<?php

require_once('utility/PropertyLoader.php');

require_once('bean/TagBean.php');
require_once('dao/TagDao.php');

$propertyLoader = new PropertyLoader();
$tagDao = new TagDao($propertyLoader);

$tagList = $tagDao -> findAll();

echo json_encode($tagList);

?>