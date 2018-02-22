'use strict';
(function() {
	angular.module('myApp').controller('BatchJobListController',BatchJobListController);
	BatchJobListController.$inject = ['$scope','$translate','adminBatchService'];
	function BatchJobListController($scope,$translate,adminBatchService) {
	
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

		}
		
		/*
		** Front-end Action Handler
		*/
		
		ctrl.updateTranslation = function() {		
			swal({
				title: $translate.instant('oym.batch.job.msg.run'),
				text: $translate.instant('oym.batch.job.msg.confirm') + " " + $translate.instant('oym.batch.job.r1.name') + "?",
				type: "warning",
				showCancelButton: true,
				confirmButtonClass: 'btn-danger',
				confirmButtonText: $translate.instant('oym.action.msg.btn.confirm'),
				cancelButtonText: $translate.instant('oym.action.msg.btn.cancel')
			}).then(function(response) {
				adminBatchService.generateTranslateHash().then(function(response) {
					if (response.data.status == 'success') {
						swal($translate.instant('oym.action.msg.title.success'),$translate.instant('oym.batch.job.msg.run') + " " + $translate.instant('oym.batch.job.r1.name') + " " + $translate.instant('oym.batch.job.msg.success'),'success');
					}
					else {
						swal($translate.instant('oym.action.msg.title.failure'),$translate.instant('oym.batch.job.msg.run') + " " + $translate.instant('oym.batch.job.r1.name') + " " + $translate.instant('oym.batch.job.msg.failure'),'error');
					}
				});
			});
		}
		
		ctrl.sendTestEmail = function() {
			adminBatchService.sendTestEmail().then(function(response) {
				console.log(response.data);
			});
		}

	}
})();