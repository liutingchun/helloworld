'use strict';
(function() {
	angular.module('myApp').controller('ProductGridController',ProductGridController);
	ProductGridController.$inject = ['$scope','$rootScope','$state','$stateParams','$location','$anchorScroll','productService'];
	function ProductGridController($scope,$rootScope,$state,$stateParams,$location,$anchorScroll,productService) {
	
		var ctrl=this;
									
		/*
		** Declaring
		*/	
		
		ctrl.sourceProductList = [];
		ctrl.pageProductList = [];
		
		ctrl.filter = {
			'search': null,
			'category': {},
			'brand': {},
			'tag': {},
			'order': 'latest',
			'item': '24',
			'currentPage': 0,
			'currentItem': 0
		};	
		
		ctrl.filterOptions = {
			'category': [],
			'brand': [],
			'tag': []
		};
					
		ctrl.filterTagSubList = {};			
					
		if ($stateParams.category) {
			if ($stateParams.category != 'all')
				ctrl.filter.category[$stateParams.category] = true;
		}	

		if ($stateParams.keyword) {
			ctrl.filter.search = $stateParams.keyword;
		}
		
		if ($stateParams.tag) {
			ctrl.filter.tag[$stateParams.tag] = true;
		}
								
		/*
		** Initialization
		*/
		init();
		function init() {
			//Force reset state
			if ($stateParams.category || $stateParams.keyword || $stateParams.tag) {
				$rootScope.gridFilter = null;
			}		
			
			//Resume previous page state if exist
			if ($rootScope.gridFilter) {
				ctrl.filter = $rootScope.gridFilter;
				ctrl.filterOptions = $rootScope.girdFilterOption;
				ctrl.filterTagSubList = $rootScope.gridFilterTagList;
				ctrl.sourceProductList = $rootScope.gridProductList;
				
				$rootScope.gridFilter = null;
				$rootScope.girdFilterOption = null;
				$rootScope.gridFilterTagList = null;
				$rootScope.gridProductList = null;
				
				$location.hash('item' + ctrl.filter.currentItem);
				$anchorScroll();
							
				renderTable(ctrl.sourceProductList);
			}
			else {
				//Reload if no saved state
				productService.getAllVisibleProduct().then(function(response) {
					ctrl.sourceProductList = response.data;
				
					for (var index in ctrl.sourceProductList) {
						//Compute filter options - category, brand
						if (ctrl.filterOptions.category.indexOf(ctrl.sourceProductList[index].category) == -1) {
							ctrl.filterOptions.category.push(ctrl.sourceProductList[index].category);
						}
						if (ctrl.filterOptions.brand.indexOf(ctrl.sourceProductList[index].brand) == -1) {
							ctrl.filterOptions.brand.push(ctrl.sourceProductList[index].brand);
						}
						
						//Compute search index
						ctrl.sourceProductList[index].searchIndex = 
							ctrl.sourceProductList[index].brand + "|" +
							ctrl.sourceProductList[index].category + "|" +
							ctrl.sourceProductList[index].color + "|" +
							ctrl.sourceProductList[index].description + "|" +
							ctrl.sourceProductList[index].name + "|" +
							ctrl.sourceProductList[index].officalCode + "|" +
							ctrl.sourceProductList[index].sex;
					}		
					
					renderTable(ctrl.sourceProductList);
				});	
				
				productService.getAllTag().then(function(response) {
					//Compute filter options - tag
					for (var index in response.data) {
						if (ctrl.filterOptions.tag.indexOf(response.data[index].tName) == -1) {
							ctrl.filterOptions.tag.push(response.data[index].tName);
							ctrl.filterTagSubList[response.data[index].tName] = [];
							ctrl.filterTagSubList[response.data[index].tName].push(response.data[index].pId);
						}
						else {
							ctrl.filterTagSubList[response.data[index].tName].push(response.data[index].pId);
						}
					}
				});
			}
		}
		
		/*
		** Front-end Action Handler
		*/
		ctrl.changePage = function(selectedPage) {		
			if (ctrl.filter.currentPage != selectedPage) {
				ctrl.filter.currentPage = selectedPage;
				
				$location.hash('top-anchor');
				$anchorScroll();
			}
		};
		
		ctrl.clickOnFilter = function() {	
			ctrl.filter.currentPage = 0;
			renderTable(ctrl.sourceProductList);
		}
		
		ctrl.viewDetail = function(item, pid) {
			ctrl.filter.currentItem = item;
		
			//Save page state for back page
			$rootScope.gridFilter = ctrl.filter;
			$rootScope.girdFilterOption = ctrl.filterOptions;
			$rootScope.gridFilterTagList = ctrl.filterTagSubList;
			$rootScope.gridProductList = ctrl.sourceProductList;
			
			$state.go('root.product-detail', {'pid': pid});
		}
		
		/*
		** Utilities
		*/
		
		function renderTable(sourceArray) {
			var renderArray = sourceArray.slice(0);
			
			var indexToRemove = [];
			
			//Iniailizing Filter
			
			//Compute applied category filter
			var categoryToKeep = [];
			if (filterActivated(ctrl.filter.category)) {				
				for (var key in ctrl.filter.category) {
					if (ctrl.filter.category[key]) {
						categoryToKeep.push(key);
					}
				}
			}
			
			//Compute applied brand filter
			var brandToKeep = [];
			if (filterActivated(ctrl.filter.brand)) {				
				for (var key in ctrl.filter.brand) {
					if (ctrl.filter.brand[key]) {
						brandToKeep.push(key);
					}
				}
			}
			
			//Compute applied tag filter
			var productInTag = [];
			if (filterActivated(ctrl.filter.tag)) {				
				for (var key in ctrl.filter.tag) {
					if (ctrl.filter.tag[key]) {
						for (var index in ctrl.filterTagSubList[key]) {
							if (productInTag.indexOf(ctrl.filterTagSubList[key][index]) == -1) {
								productInTag.push(ctrl.filterTagSubList[key][index]);
							}
						}
					}
				}
			}
					
			for (var index in renderArray) {
				
				//Filter by category
				if (categoryToKeep.length > 0) {
					if (categoryToKeep.indexOf(renderArray[index].category) == -1) {
						indexToRemove.push(index);
						continue;
					}
				}
				
				//Filter by brand
				if (brandToKeep.length > 0) {
					if (brandToKeep.indexOf(renderArray[index].brand) == -1) {
						indexToRemove.push(index);
						continue;
					}
				}
				
				//Filter by tag
				if (productInTag.length > 0) {
					if (productInTag.indexOf(renderArray[index].pid) == -1) {
						indexToRemove.push(index);
						continue;
					}
				}
				
				//Filter by keyword
				if (ctrl.filter.search) {
					if (!renderArray[index].searchIndex.toLowerCase().includes(ctrl.filter.search.toLowerCase())) {
						indexToRemove.push(index);
						continue;
					}
				}
			}
			
			//Remove tagged element
			for (var i = 0; i < indexToRemove.length; i++) {
				renderArray.splice(indexToRemove[i] - i, 1);
			}
			
			//Apply sort order
			var sortFunction = null;
			
			switch(ctrl.filter.order) {
				case 'latest':
					sortFunction = function(a, b) {
						return new Date(b.lastEdit).valueOf() - new Date(a.lastEdit).valueOf();
					};
					break;
				case 'price-low-to-high':
					sortFunction = function(a, b) {
						return a.price - b.price;
					};
					break;
				case 'price-high-to-low':
					sortFunction = function(a, b) {
						return b.price - a.price;
					};
					break;
				case 'best-sale':
					sortFunction = function(a, b) {
						return b.viewCount - a.viewCount;
					};
					break;
			}
												
			renderArray.sort(sortFunction);
			
			createPageing(renderArray);
		}
		
		function filterActivated(json) {			
			var jsonArray = Object.keys(json);
			
			if (!jsonArray) {
				return false;
			}
			else {
				for (var index in json) {
					if (json[index]) {
						return true;
					}
				}
			}
			
			return false;
		}
		
		//CAUTION: WILL DESTROY ORIGINAL ARRAY
		function createPageing(inputArray) {
			ctrl.pageProductList = [];
			
			var length = inputArray.length;
			for (var i = 0; i < Math.floor(length / ctrl.filter.item); i++) {
				ctrl.pageProductList[i] = inputArray.splice(0, ctrl.filter.item);
			}
			
			ctrl.pageProductList.push(inputArray);
		}
				
	}
})();