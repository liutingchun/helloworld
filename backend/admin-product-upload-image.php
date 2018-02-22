<?php
require_once('utility/PropertyLoader.php');
require_once('utility/SessionLoader.php');
require_once('utility/AuthenticationLoader.php');

require_once('utility/ImageHandler.php');
require_once('bean/MemberBean.php');
require_once('dao/MemberDao.php');

require_once('utility/RequestResultWrap.php');

$requestResultWrap = new RequestResultWrap();

$sessionLoader = new SessionLoader();
$propertyLoader = new PropertyLoader();

$memberDao = new MemberDao($propertyLoader);

$authenticationLoader = new AuthenticationLoader($sessionLoader, $memberDao);

if ($authenticationLoader->isAdmin()) {	
	$pid = $_POST['pid'];
	$iid = $_POST['iid'];

	$tempPath = $_FILES['file']['tmp_name'];
	$uploadPath = dirname(dirname(__FILE__)) . DIRECTORY_SEPARATOR . 'img' . DIRECTORY_SEPARATOR . 'product' . DIRECTORY_SEPARATOR . $pid . DIRECTORY_SEPARATOR . $iid . '.jpg';
	//$uploadPath = dirname( __FILE__ ) . DIRECTORY_SEPARATOR . 'uploads' . DIRECTORY_SEPARATOR . $_FILES['file']['name'];
	move_uploaded_file($tempPath, $uploadPath);
	
	if ($iid == 0) {
		$uploadPathCompressed = dirname(dirname(__FILE__)) . DIRECTORY_SEPARATOR . 'img' . DIRECTORY_SEPARATOR . 'product' . DIRECTORY_SEPARATOR . $pid . DIRECTORY_SEPARATOR . 'preview.jpg';
		$imageHandler = new ImageHandler();
		$imageHandler->createCompressedProductPreview($uploadPath, $uploadPathCompressed);
	}

	$requestResultWrap->status = 'success';
	$requestResultWrap->message = $uploadPath;
}
else {
	$requestResultWrap->status = 'failure';
	$requestResultWrap->message = 'Access deined.';
}

echo json_encode($requestResultWrap);

?>