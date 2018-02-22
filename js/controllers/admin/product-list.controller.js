'use strict';
(function() {
	angular.module('myApp').controller('ProductListController',ProductListController);
	ProductListController.$inject = ['$scope', '$state', '$window', '$translate', 'DTOptionsBuilder', 'adminProductService'];
	function ProductListController($scope, $state, $window, $translate, DTOptionsBuilder, adminProductService) {
	
		var ctrl=this;
				

		/*	
		** Declaring
		*/
		ctrl.productList = [];
		
		/*
		** Initialization
		*/
		init();
		function init() {	
			var language = {
				"sEmptyTable": $translate.instant('oym.order.history.dt.sEmptyTable'),
				"sInfo": $translate.instant('oym.order.history.dt.sInfo'),
				"sInfoEmpty": $translate.instant('oym.order.history.dt.sInfoEmpty'),
				"sLengthMenu": $translate.instant('oym.order.history.dt.sLengthMenu'),
				"sSearch": $translate.instant('oym.order.history.dt.sSearch'),
				"oPaginate": {
					"sNext": $translate.instant('oym.order.history.dt.sNext'),
					"sPrevious": $translate.instant('oym.order.history.dt.sPrevious')
				}
			}
		
			ctrl.dtOptions = DTOptionsBuilder.newOptions()
				.withOption('responsive', true)
				.withOption('scrollX', 'true')
				.withOption('order', [0, 'desc'])
				.withLanguage(language);
				/*.withButtons([
					'columnsToggle',
					'colvis',
					'copy',
					'print',
					'excel',
					{
						text: 'Some button',
						key: '1',
						action: function (e, dt, node, config) {
							alert('Button activated');
						}
					}
				]);*/
		
			adminProductService.findAllProduct().then(function(response) {
				ctrl.productList = response.data;
			});
		}
		
		/*
		** Front-end Action Handler
		*/
		
		ctrl.viewProduct = function(pid) {
			var url = $state.href('root.admin.form-update-product', {pid: pid});
			$window.open(url,'_blank');
		}
		

	}
})();