<?php
require_once('utility/PropertyLoader.php');
require_once('utility/SessionLoader.php');
require_once('utility/AuthenticationLoader.php');
require_once('bean/MemberBean.php');
require_once('dao/MemberDao.php');

require_once('bean/BannerBean.php');
require_once('dao/BannerDao.php');

require_once('utility/RequestResultWrap.php');

$requestResultWrap = new RequestResultWrap();

$sessionLoader = new SessionLoader();
$propertyLoader = new PropertyLoader();

$memberDao = new MemberDao($propertyLoader);

$authenticationLoader = new AuthenticationLoader($sessionLoader, $memberDao);

if ($authenticationLoader->isAdmin()) {	
	$bannerDao = new BannerDao($propertyLoader);

	$nextId = $bannerDao->getNextBId();
	$extension = pathinfo($_FILES['file']['name'], PATHINFO_EXTENSION);
	$newName = $nextId . '.' . $extension;
	
	$tempPath = $_FILES['file']['tmp_name'];
	$uploadPath = dirname(dirname(__FILE__)) . DIRECTORY_SEPARATOR . 'img' . DIRECTORY_SEPARATOR . 'banner' . DIRECTORY_SEPARATOR . $newName;
	move_uploaded_file($tempPath, $uploadPath);

	$bType = $_POST['target'];
	$bTarget = $_POST['url'];
	$bPosition= $_POST['position'];

	$bannerBean = new BannerBean();
	
	$bannerBean->bId = $nextId;
	$bannerBean->bSource = $newName;
	$bannerBean->bType = $bType;
	$bannerBean->bTarget = $bTarget;
	$bannerBean->bOrder = $bannerDao->getNextBOrder();
	$bannerBean->bPosition = $bPosition;
	
	$bannerDao->add($bannerBean);
	
	$requestResultWrap->status = 'success';
	$requestResultWrap->message = $bannerBean;
}
else {
	$requestResultWrap->status = 'failure';
	$requestResultWrap->message = 'Access deined.';
}

echo json_encode($requestResultWrap);

?>