'use strict';
(function() {
	angular.module('myApp').controller('OrderHistoryController',OrderHistoryController);
	OrderHistoryController.$inject = ['$scope','$state','$stateParams','$translate','DTOptionsBuilder','orderService'];
	function OrderHistoryController($scope,$state,$stateParams,$translate,DTOptionsBuilder,orderService) {
	
		var ctrl=this;
									
		/*
		** Declaring
		*/	
		ctrl.phone = $stateParams.phone;
		ctrl.email = $stateParams.email;

		ctrl.orderList = [];
		
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
		
			if (ctrl.phone && ctrl.email) {
				orderService.getOrderByPhoneAndEmail(ctrl.phone, ctrl.email).then(function(response) {
					orderSearchCallBack(response);
				});
			}
			else {
				orderService.getOrderByLoggedInAccount().then(function(response) {
					orderSearchCallBack(response);
				});
			}
		}
		
		/*
		** Front-end Action Handle
		*/

		/*
		** Utility
		*/
		function orderSearchCallBack(response) {
			if (response.data.status == 'success') {
				ctrl.orderList = response.data.message;
				
				//Post-processing
				for (var index in ctrl.orderList) {
					ctrl.orderList[index].oDate = new Date(ctrl.orderList[index].oDate)
					if (ctrl.orderList[index].mUsername.length > 15) {
						ctrl.orderList[index].mUsername = ctrl.orderList[index].mUsername.substring(0, 12) + "...";
					}
					if (ctrl.orderList[index].oPhone.length > 12) {
						ctrl.orderList[index].oPhone = ctrl.orderList[index].oPhone.substring(0, 9) + "...";
					}
				}
			}
			else {
				swal($translate.instant('oym.action.msg.title.warning'),$translate.instant(response.data.message),'warning');
			}
		}
		
		
	}
})();