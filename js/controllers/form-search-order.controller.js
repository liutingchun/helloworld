'use strict';
(function() {
	angular.module('myApp').controller('FormSearchOrderController',FormSearchOrderController);
	FormSearchOrderController.$inject = ['$scope','$state','$translate'];
	function FormSearchOrderController($scope,$state,$translate) {
	
		var ctrl=this;
				
		/*	
		** Declaring
		*/
		ctrl.phone = null;
		ctrl.email = null;
		
		/*
		** Front-end Action Handler
		*/
		ctrl.submit = function() {
			$state.go('root.order-history', {'phone': ctrl.phone, 'email': ctrl.email});
		}				
	}
})();