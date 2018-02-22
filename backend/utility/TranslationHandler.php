<?php

class TranslationHandler {
	private $messageBundleDao;
	private $discountGroupDao;
			
	function __construct($messageBundleDao, $discountGroupDao) {
		$this->messageBundleDao = $messageBundleDao;
		$this->discountGroupDao = $discountGroupDao;
	}
	
	function getMessageBundle() {
		$json_en = array();
		$json_zhTW = array();
		$json_zhCN = array();
		
		$messageKeyValue = $this->messageBundleDao -> findAll();
		
		foreach($messageKeyValue as &$bean) {
			$json_en[$bean->messageKey] = $bean->en;
			$json_zhTW[$bean->messageKey] = $bean->zhTW;
			$json_zhCN[$bean->messageKey] = $bean->zhCN;
		}
		
		$output = array();
		$output['en'] = $json_en;
		$output['zhTW'] = $json_zhTW;
		$output['zhCN'] = $json_zhCN;
		
		return $output;
	}
	
	function generateDiscountGroupMsg() {
		$json_en = array();
		$json_zhTW = array();
		$json_zhCN = array();
		
		$messageKeyValue = $this->discountGroupDao -> findAll();
		
		foreach($messageKeyValue as &$bean) {
			$key = 'generated.discount.group.desc.' . $bean->dId;
			
			$json_en[$key] = $bean->dDescEn;
			$json_zhTW[$key] = $bean->dDescZhTW;
			$json_zhCN[$key] = $bean->dDescZhCN;
		}
		
		$output = array();
		$output['en'] = $json_en;
		$output['zhTW'] = $json_zhTW;
		$output['zhCN'] = $json_zhCN;
		
		return $output;
	}
}

?>