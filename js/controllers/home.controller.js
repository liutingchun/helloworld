'use strict';
(function() {
	angular.module('myApp').controller('HomeController',HomeController);
	HomeController.$inject = ['$scope','$state','$window','generalService'];
	function HomeController($scope,$state,$window,generalService) {
	
		$scope.googleMapsUrl="https://maps.googleapis.com/maps/api/js?key=AIzaSyDD6H_AW3JM9z-3WmGrPz_lGidbJIUVEfA";
	
		var ctrl=this;
				
		/*
		** Declaring
		*/	
		ctrl.productSliderLeftList = [];		
		ctrl.productSliderRightList = [];		
		ctrl.productCategoryList = [];		
		
		ctrl.productKeyword = null;
			
		/*
		** Initialization
		*/
		init();
		function init() {
			generalService.getBanner().then(function(response) {
				for (var index in response.data) {
					var row = response.data[index];
					
					switch (row.bPosition) {
						case 0:
							ctrl.productSliderLeftList.push(row);
							break;
						case 1:
							ctrl.productSliderRightList.push(row);
							break;
						case 2:
							ctrl.productCategoryList.push(row);
							break;
					}
				}
			});	
		}
		
		/*
		** Front-end Action Handle
		*/
		ctrl.goBannerTarger = function(type, target) {
			switch(type) {
				case 'product': {
					$state.go('root.product-detail', {pid: target});
					break;
				}
				case 'tag': {
					$state.go('root.product-grid', {tag: target});
					break;
				}
				case 'external': {
					$window.open(target,'_blank');
					break;
				}
			}
		}
		
		ctrl.searchProduct = function() {
			$state.go('root.product-grid', {keyword: ctrl.productKeyword});
		}
		
		ctrl.viewProduct = function(pid) {
			$state.go('root.product-detail', {pid: pid});
		}
		
		ctrl.toGridWithTag = function(tag) {
			$state.go('root.product-grid', {tag: tag});
		}
				
	}
})();