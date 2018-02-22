<?php

class CartLoader {
	private $sessionLoader;
	
	function __construct($sessionLoader) {
		$this->sessionLoader = $sessionLoader;
	}
	
	function getQuantity($pid, $productStatus, $productColor, $productSize) {
		$cart = $this->sessionLoader->getProperty('cart');
		
		if (isset($cart[$pid][$productStatus][$productColor][$productSize])) {
			return $cart[$pid][$productStatus][$productColor][$productSize];
		}
		else {
			return 0;
		}
				
		//return $cart[$pid][$productStatus][$productColor][$productSize];
	}
	
	function getAllItem() {
		$cart = $this->sessionLoader->getProperty('cart');
		
		return $cart;
	}
	
	function getAllItemArray() {
		$cart = $this->sessionLoader->getProperty('cart');
		
		$output = array();
		foreach ($cart as $pid => $pidList) {
			foreach ($pidList as $status => $statusList) {
				foreach ($statusList as $color => $colorList) {
					foreach ($colorList as $size => $quantity) {
						for ($i = 0; $i < $quantity; $i++) {
							$item = array();
							
							$item['pid'] = $pid;
							$item['productStatus'] = $status;
							$item['productColor'] = $color;
							$item['productSize'] = $size;
							
							array_push($output, $item);
						}
					}
				}
			}
		}
		
		return $output;
	}
	
	function clearCart() {
		$this->sessionLoader->clearProperty('cart');
	}
	
	function addItem($pid, $productStatus, $productColor, $productSize) {
		$cart = $this->sessionLoader->getProperty('cart');
		
		if (isset($cart[$pid][$productStatus][$productColor][$productSize])) {
			$cart[$pid][$productStatus][$productColor][$productSize] += 1;
		}
		else {
			$cart[$pid][$productStatus][$productColor][$productSize] = 1;
		}
		
		$this->sessionLoader->setProperty('cart', $cart);
	}
	
	function removeItem($pid, $productStatus, $productColor, $productSize) {
		$cart = $this->sessionLoader->getProperty('cart');
		
		if (isset($cart[$pid][$productStatus][$productColor][$productSize])) {
			unset($cart[$pid][$productStatus][$productColor][$productSize]);
		}
		
		if (sizeof($cart[$pid][$productStatus][$productColor]) == 0) {
			unset($cart[$pid][$productStatus][$productColor]);
		}
		
		if (sizeof($cart[$pid][$productStatus]) == 0) {
			unset($cart[$pid][$productStatus]);
		}
		
		if (sizeof($cart[$pid]) == 0) {
			unset($cart[$pid]);
		}
		
		$this->sessionLoader->setProperty('cart', $cart);
	}
}

?>