<?php

require_once('utility/PropertyLoader.php');

require_once('bean/BannerBean.php');
require_once('dao/BannerDao.php');

$propertyLoader = new PropertyLoader();
$bannerDao = new BannerDao($propertyLoader);

$bannerList = $bannerDao -> findAll();

echo json_encode($bannerList);


?>