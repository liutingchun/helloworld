<?php
require_once('utility/PropertyLoader.php');
require_once('utility/SessionLoader.php');
require_once('utility/AuthenticationLoader.php');
require_once('utility/TranslationHandler.php');

require_once('bean/MemberBean.php');
require_once('dao/MemberDao.php');

require_once('bean/MessageBundleBean.php');
require_once('dao/MessageBundleDao.php');
require_once('bean/DiscountGroupBean.php');
require_once('dao/DiscountGroupDao.php');

require_once('utility/RequestResultWrap.php');

$sessionLoader = new SessionLoader();
$propertyLoader = new PropertyLoader();

$memberDao = new MemberDao($propertyLoader);

$authenticationLoader = new AuthenticationLoader($sessionLoader, $memberDao);

$requestResultWrap = new RequestResultWrap();

if ($authenticationLoader->isAdmin()) {	
	$messageBundleDao = new MessageBundleDao($propertyLoader);
	$discountGroupDao = new DiscountGroupDao($propertyLoader);
	
	$translationHandler = new TranslationHandler($messageBundleDao, $discountGroupDao);
	
	$msgArray = array();
	$msgArray['en'] = array();
	$msgArray['zhTW'] = array();
	$msgArray['zhCN'] = array();
	
	$tempArray = array();
	$tempArray[0] = $translationHandler->getMessageBundle();
	$tempArray[1] = $translationHandler->generateDiscountGroupMsg();
	
	foreach($tempArray as $tempArrayMember) {
		foreach($tempArrayMember as $locale => $msg) {
			$msgArray[$locale] = array_merge($msgArray[$locale], $msg);
			//array_push($msgArray[$locale], $msg);
		}
	}

	
	$path = dirname(dirname(__FILE__)) . DIRECTORY_SEPARATOR . 'message-bundle' . DIRECTORY_SEPARATOR;

	foreach($msgArray as $locale => $msg) {
		$file = fopen($path . $locale . ".json", "w");
		fwrite($file, json_encode($msg));
	}	
	
	$requestResultWrap->status = 'success';
	
	/*$messageKeyValue = $messageBundleDao -> findAll();

	$json_en = array();
	$json_zhTW = array();
	$json_zhCN = array();
	
	foreach($messageKeyValue as &$bean) {
		$json_en[$bean->messageKey] = $bean->en;
		$json_zhTW[$bean->messageKey] = $bean->zhTW;
		$json_zhCN[$bean->messageKey] = $bean->zhCN;
	}
	
	$path = dirname(dirname(__FILE__)) . DIRECTORY_SEPARATOR . 'message-bundle' . DIRECTORY_SEPARATOR;	
	
	$file_en = fopen($path . "en.json", "w");
	$file_zhTW = fopen($path . "zhTW.json", "w");
	$file_zhCN = fopen($path . "zhCN.json", "w");
	
	fwrite($file_en, json_encode($json_en));
	fwrite($file_zhTW, json_encode($json_zhTW));
	fwrite($file_zhCN, json_encode($json_zhCN));*/
	
	$requestResultWrap->status = 'success';
}
else {
	$requestResultWrap->status = 'failure';
	$requestResultWrap->message = 'Access denied.';
}

echo json_encode($requestResultWrap);

?>
