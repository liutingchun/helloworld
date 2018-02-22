<?php
require_once('utility/RequestResultWrap.php');
require_once('utility/RequestLoader.php');
require_once('utility/ValidationHandler.php');

require_once('utility/PropertyLoader.php');
require_once('bean/MemberBean.php');
require_once('dao/MemberDao.php');

$ack = new RequestResultWrap();

$propertyLoader = new PropertyLoader();
$memberDao = new MemberDao($propertyLoader);

$validationHandler = new ValidationHandler();

$requestLoader = new RequestLoader();
$form = $requestLoader -> getParameter('registerForm');

//Check Madatory field
if (
	is_null($form['username']) ||
	is_null($form['password']) ||
	is_null($form['phone']) ||
	is_null($form['address'])
) {
	$ack->status = 'failure';
	$ack->message = 'oym.action.msg.register.failure.missing.info';
}

//Check field format
else if (
	!$validationHandler->username($form['username']) ||
	!$validationHandler->password($form['password']) ||
	!$validationHandler->phone($form['phone']) ||
	!$validationHandler->address($form['address'])
) {
	$ack->status = 'failure';
	$ack->message = 'oym.action.msg.register.failure.missing.info';
}

//Check optional field
else if (
	(isset($form['email']) && !$validationHandler->email($form['email'])) ||
	(isset($form['name']) && !$validationHandler->name($form['name'])) ||
	(isset($form['gender']) && !$validationHandler->gender($form['gender']))
) {
	$ack->status = 'failure';
	$ack->message = 'oym.action.msg.register.failure.missing.info';
}

//Check username duplicate
else {
	$memberList = $memberDao->findByUsername($form['username']);
	if (sizeof($memberList) > 0) {
		$ack->status = 'failure';
		$ack->message = 'oym.action.msg.register.failure.duplicate';
	}
	else {
		$memberBean = new MemberBean();
		
		$memberBean->mUsername = $form['username'];
		$memberBean->mPw = $form['password'];
		$memberBean->mPhone = $form['phone'];
		$memberBean->mRegion = $form['address'];
		
		$memberBean->mEmail = isset($form['email']) ? $form['email'] : '';
		$memberBean->mFname = isset($form['name']) ? $form['name'] : '';
		$memberBean->mGender = isset($form['gender']) ? $form['gender'] : '';
		
		$memberDao->add($memberBean);
		
		$ack->status = 'success';
	}
}

echo json_encode($ack);

?>