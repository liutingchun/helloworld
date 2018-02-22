<?php

class TransactionHandler {
	private $cartLoader;
	private $inventoryDao;
	private $orderMadeDao;
	private $orderSubDao;
	private $productDao;
	private $discountGroupDao;
			
	function __construct($cartLoader, $inventoryDao, $orderMadeDao, $orderSubDao, $productDao, $discountGroupDao) {
		$this->cartLoader = $cartLoader;
		$this->inventoryDao = $inventoryDao;
		$this->orderMadeDao = $orderMadeDao;
		$this->orderSubDao = $orderSubDao;
		$this->productDao = $productDao;
		$this->discountGroupDao = $discountGroupDao;
	}
	
	function getRandomNumberic($length) {
		$out = '';
		for ($i = 0; $i < $length; $i++) {
			$out = $out . rand(0,9);
		}
		return $out;
	}
	
	function getCurrentTime() {
		date_default_timezone_set('Asia/Hong_Kong');
		return $date = date('Y-m-d H:i:s', time());	
	}
	
	function interceptString($a, $b, $length) {
		$string_a = strval($a);
		$string_b = strval($b);
		
		$out = '';
		for ($i = 0; $i < $length; $i++ ) {
			if (strlen($string_a) > $i) {
				$out = $out . substr($string_a, $i, 1);
			}
			else {
				$out = $out . 0;
			}
			
			if (strlen($string_b) > $i) {
				$out = $out . substr($string_b, $i, 1);
			}
			else {
				$out = $out . 0;
			}
		}
		
		return $out;
	}
	
	function checkEnoughStock($pid, $productStatus, $productColor, $productSize) {
		$inventoryBeanList = $this->inventoryDao -> findByPidAndProductStatusAndProductColorAndProductSize($pid, $productStatus, $productColor, $productSize);
		
		if (sizeof($inventoryBeanList) > 0) {
			$inventoryBean = $inventoryBeanList[0];
			
			$currentItemCount = $this->cartLoader -> getQuantity($pid, $productStatus, $productColor, $productSize);
			
			return ($inventoryBean->quantity == -1 || $inventoryBean->quantity >= $currentItemCount);
		}
		else {
			return false;
		}
	}
	
	function checkEnoughStockToAdd($pid, $productStatus, $productColor, $productSize) {		
		$inventoryBeanList = $this->inventoryDao -> findByPidAndProductStatusAndProductColorAndProductSize($pid, $productStatus, $productColor, $productSize);
		$inventoryBean = $inventoryBeanList[0];
		
		$currentItemCount = $this->cartLoader -> getQuantity($pid, $productStatus, $productColor, $productSize);
		
		return ($inventoryBean->quantity == -1 || $inventoryBean->quantity >= $currentItemCount + 1);
	}
	
	function checkAllItemInCartAvailability() {
		$cart = $this->cartLoader->getAllItem();
		
		foreach ($cart as $pid => $pidList) {
			foreach ($pidList as $status => $statusList) {
				foreach ($statusList as $color => $colorList) {
					foreach ($colorList as $size => $quantity) {
						if (!$this->checkEnoughStock($pid, $status, $color, $size)) {
							return false;
						}
					}
				}
			}
		}
		
		return true;
	}
	
	function checkAndRemoveSoldOutItem($found) {
		$cart = $this->cartLoader->getAllItem();
		
		if (is_array($cart)) {
			foreach ($cart as $pid => $pidList) {
				foreach ($pidList as $status => $statusList) {
					foreach ($statusList as $color => $colorList) {
						foreach ($colorList as $size => $quantity) {
							if (!$this->checkEnoughStock($pid, $status, $color, $size)) {
								$this->cartLoader->removeItem($pid, $status, $color, $size);
								return $this->checkAndRemoveSoldOutItem(true);
							}
						}
					}
				}
			}
		}
		
		return $found;
	}
	
	function increaseQuantity($pid, $productStatus, $productColor, $productSize, $productPrice) {
		$inventoryBeanList = $this->inventoryDao->findByPidAndProductStatusAndProductColorAndProductSize($pid, $productStatus, $productColor, $productSize);
		
		$inventoryBean = null;
		
		if (sizeof($inventoryBeanList) > 0) {
			$inventoryBean = $inventoryBeanList[0];
			
			if ($inventoryBean->quantity >= 0) {
				$inventoryBean->quantity += 1;
				
				$this->inventoryDao->update($inventoryBean);
			}
		}
		else {
			$inventoryBean = new InventoryBean();
			
			$inventoryBean->pid = $pid;
			$inventoryBean->productStatus = $productStatus;
			$inventoryBean->productColor = $productColor;
			$inventoryBean->productSize = $productSize;
			$inventoryBean->productPrice = $productPrice;
			$inventoryBean->quantity = 1;
			
			$this->inventoryDao->add($inventoryBean);
		}
		
		return $inventoryBean;
	}
	
	function deductQuantity($pid, $productStatus, $productColor, $productSize) {
		$inventoryBean = $this->inventoryDao->findByPidAndProductStatusAndProductColorAndProductSize($pid, $productStatus, $productColor, $productSize)[0];
		
		if ($inventoryBean->quantity != -1 && $inventoryBean->quantity > 0) {
			$inventoryBean->quantity -= 1;
			
			$this->inventoryDao->update($inventoryBean);
			
			return true;
		}
		else {
			return false;
		}
	}
	
	function addOrderAndSubOrder($username, $orderInfo, $itemList, $discount) {
		$countItems = 0;
		$sumItemPrice = 0;
		
		//Retrieve product info
		$subOrderList = array();
		foreach ($itemList as $item) {
			$orderSubBean = new OrderSubBean();
			
			$productBean = $this->productDao->findByPid($item['pid'])[0];
			$inventoryBean = $this->inventoryDao->findByPidAndProductStatusAndProductColorAndProductSize($item['pid'], $item['productStatus'], $item['productColor'], $item['productSize'])[0];
	
			//$orderSubBean->iId = $this->orderSubDao->getNextIId();
			//$orderSubBean->iPrice = $productBean->price;
			$orderSubBean->iPrice = $inventoryBean->productPrice;
			if (isset($discount[$item['pid']][$item['productStatus']][$item['productColor']][$item['productSize']])) {
				$orderSubBean->iPrice -= $discount[$item['pid']][$item['productStatus']][$item['productColor']][$item['productSize']];
			}
			$orderSubBean->iStatus = $item['productStatus'];
			//$orderSubBean->oId = $oId;
			$orderSubBean->pId = $item['pid'];
			$orderSubBean->pColor = $item['productColor'];
			$orderSubBean->pSize = $item['productSize'];
						
			array_push($subOrderList, $orderSubBean);
			
			$countItems++;
			$sumItemPrice += $orderSubBean->iPrice;
			//$sumItemPrice += $productBean->price;
		}
		
		//Add main order
		$orderEntity = new OrderMadeBean();
		
		$orderEntity->oId = $this->orderMadeDao -> getNextOId();
		$orderEntity->oDate = $this->getCurrentTime();
		$orderEntity->mUsername = $username;
		$orderEntity->oFname = $orderInfo['name'];
		$orderEntity->oLname = null;
		$orderEntity->oEmail = $orderInfo['email'];
		$orderEntity->oPhone = $orderInfo['phone'];
		$orderEntity->oLocation = $orderInfo['country'];
		$orderEntity->oStatus = 'received';
		$orderEntity->oItems = $countItems;
		$orderEntity->oTotal = $sumItemPrice;
		$orderEntity->oPaid = 0;
		
		$ran = $this->getRandomNumberic(6);
		
		$orderEntity->oSerial = $this->interceptString($ran, $orderEntity->oId, 6);
		
		$this->orderMadeDao -> add($orderEntity);
		
		//Add sub order
		foreach ($subOrderList as $subOrderEntity) {
			$subOrderEntity->iId = $this->orderSubDao->getNextIId();
			$subOrderEntity->oId = $orderEntity->oId;
			
			if ($this->deductQuantity($subOrderEntity->pId, $subOrderEntity->iStatus, $subOrderEntity->pColor, $subOrderEntity->pSize)) {
				;
			}
			else {
				$subOrderEntity->status = 'stock_out';
			}
			
			$this->orderSubDao->add($subOrderEntity);
		}
		
		/*$ack = array();
		
		$ack['serial'] = $orderEntity->oSerial;
		$ack['phone'] = $orderEntity->oPhone;
		$ack['email'] = $orderEntity->oEmail;*/
		
		
		return $orderEntity;
	}
	
	//Assume order exist
	function cancelOrderAndReleaseQuantity($oId) {
		$orderMadeBean = $this->orderMadeDao->findByOId($oId)[0];
		
		$orderMadeBean->oStatus = 'cancelled';
		
		$this->orderMadeDao->update($orderMadeBean);
		
		$orderSubList = $this->orderSubDao->findByOId($oId);
		
		$result = array();
		
		foreach ($orderSubList as $orderSubBean) {
			$pid = $orderSubBean->pId;
			$productStatus = $orderSubBean->iStatus;
			$productColor = $orderSubBean->pColor;
			$productSize = $orderSubBean->pSize;
			$productPrice = $orderSubBean->iPrice;
			
			array_push($result, $this->increaseQuantity($pid, $productStatus, $productColor, $productSize, $productPrice));			
		}
		
		return $result;
	}
	
	function getDiscountGroupTriggered() {		
		$productCount = array();
		$cart = $this->cartLoader->getAllItem();
		
		foreach ($cart as $pid => $pidList) {
			foreach ($pidList as $status => $statusList) {
				foreach ($statusList as $color => $colorList) {
					foreach ($colorList as $size => $quantity) {
						if (isset($productCount[$pid])) {
							$productCount[$pid] += $quantity;
						}
						else {
							$productCount[$pid] = $quantity;	
						}
					}
				}
			}
		}
		
		$discountGroupHash = array();
		$discountGroupMember = array();
		$discountGroupCounting = array();
		foreach ($productCount as $pid => $quantity) {
			$discountGroupList = $this->discountGroupDao->joinDiscountGroupTagFindByPid($pid);
			
			foreach($discountGroupList as $discountGroup) {
				if (isset($discountGroupMember[$discountGroup->dId])) {
					array_push($discountGroupMember[$discountGroup->dId], $pid);
				}
				else {
					$discountGroupMember[$discountGroup->dId] = array();
					array_push($discountGroupMember[$discountGroup->dId], $pid);
				}
				
				if (isset($discountGroupCounting[$discountGroup->dId])) {
					$discountGroupCounting[$discountGroup->dId] -= $quantity;
				}
				else {
					$discountGroupCounting[$discountGroup->dId] = $discountGroup->dQuantity - $quantity;
				}
				
				if (!isset($discountGroupHash[$discountGroup->dId])) {
					$discountGroupHash[$discountGroup->dId] = $discountGroup;
				}
			}
		}
				
		$discountItem = array();
		foreach ($discountGroupCounting as $dId => $count) {
			if ($count <= 0) {
				foreach ($discountGroupMember[$dId] as $member) {
					if (isset($discountItem[$member])) {
						array_push($discountItem[$member], $discountGroupHash[$dId]);
					}
					else {
						$discountItem[$member] = array();
						array_push($discountItem[$member], $discountGroupHash[$dId]);
					}
				}
			}
		}
		
		//Find max
		$maxDiscountOfItem = array();
		foreach ($cart as $pid => $pidList) {
			foreach ($pidList as $status => $statusList) {
				foreach ($statusList as $color => $colorList) {
					foreach ($colorList as $size => $quantity) {
						if (isset($discountItem[$pid])) {
							$inventoryBean = $this->inventoryDao->findByPidAndProductStatusAndProductColorAndProductSize($pid, $status, $color, $size)[0];
							$price = $inventoryBean->productPrice;
							
							$maxIndex = 0;
							$maxValue = 0;
							switch ($discountItem[$pid][$maxIndex]->dType) {
								case 'percentage':
									$maxValue = $price * $discountItem[$pid][$maxIndex]->dValue / 100;
									break;
								case 'real':
									$maxValue = $discountItem[$pid][$maxIndex]->dValue;
									break;
							}
								
							for ($i = 1; $i < sizeof($discountItem[$pid]); $i++) {													
								$compare = 0;
								switch ($discountItem[$pid][$i]->dType) {
									case 'percentage':
										$compare = $price * $discountItem[$pid][$i]->dValue / 100;
										break;
									case 'real':
										$compare = $discountItem[$pid][$i]->dValue;
										break;
								}
								
								if ($compare > $maxValue) {
									$maxIndex = $i;
									$maxValue = $compare;
								}
							}
							
							//$maxDiscountOfItem[$pid][$status][$color][$size] = $discountItem[$pid][$maxIndex];
							$maxDiscountOfItem[$pid][$status][$color][$size] = round($maxValue);
						}
					}
				}
			}
		}

		return $maxDiscountOfItem;
	}
}

?>