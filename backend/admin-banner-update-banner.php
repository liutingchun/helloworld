<?php
require_once('utility/PropertyLoader.php');
require_once('utility/SessionLoader.php');
require_once('utility/AuthenticationLoader.php');

require_once('bean/MemberBean.php');
require_once('dao/MemberDao.php');

require_once('bean/BannerBean.php');
require_once('dao/BannerDao.php');

require_once('utility/RequestLoader.php');
require_once('utility/RequestResultWrap.php');

$sessionLoader = new SessionLoader();
$propertyLoader = new PropertyLoader();

$memberDao = new MemberDao($propertyLoader);

$authenticationLoader = new AuthenticationLoader($sessionLoader, $memberDao);

$requestResultWrap = new RequestResultWrap();

if ($authenticationLoader->isAdmin()) {	
	$requestLoader = new RequestLoader();
	$banner = $requestLoader -> getParameter('banner');

	$bannerBean = new BannerBean();
	
	$bannerBean->bId = $banner['bId'];
	$bannerBean->bSource = $banner['bSource'];
	$bannerBean->bType = $banner['bType'];
	$bannerBean->bTarget = $banner['bTarget'];
	$bannerBean->bOrder = $banner['bOrder'];
	$bannerBean->bPosition = $banner['bPosition'];
	
	$bannerDao = new BannerDao($propertyLoader);
	
	$bannerDao->update($bannerBean);

	$requestResultWrap->status = 'success';
	$requestResultWrap->message = $bannerBean;
}
else {
	$requestResultWrap->status = 'failure';
	$requestResultWrap->message = 'Access denied.';
}

echo json_encode($requestResultWrap);

?>
