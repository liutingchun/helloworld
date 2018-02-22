app.config(
	function($ocLazyLoadProvider) {
		$ocLazyLoadProvider.config({
			modules: [
			
				{
					name: 'iCheck',
					files: [
						'lib/icheck/skins/square/orange.css',
						'lib/icheck/icheck.min.js',
						'js/directives/icheck.directive.js'
					]
				},
				
				{
					name: 'datatables',
					files: [			
						'lib/angular-datatables/jquery-datatables.min.js',
						'lib/angular-datatables/angular-datatables.min.js',
						'lib/angular-datatables/jquery-datatables.min.css',
						'lib/angular-datatables/angular-datatables.min.css'
					],
					serie: true
				},
				
				{
					name: 'ngMap',
					files: [
						'lib/ng-map/ng-map.min.js'
					]
				},
				
				{
					name: 'PayPal-checkout',
					files: [
						'https://www.paypalobjects.com/api/checkout.js',
						'js/directives/paypal-checkout-button.directive.js'
					]
				},
				
				{
					name: 'ui-bootstrap',
					files: [
						'https://cdnjs.cloudflare.com/ajax/libs/angular-ui-bootstrap/2.5.0/ui-bootstrap-tpls.min.js'
					]
				},
				
				{
					name: 'angularFileUpload',
					files: [
						'https://cdnjs.cloudflare.com/ajax/libs/angular-file-upload/2.5.0/angular-file-upload.min.js'
					]
				},
				
				{
					name: 'ui-select',
					files: [
						'https://cdnjs.cloudflare.com/ajax/libs/angular-ui-select/0.20.0/select.min.css',
						'https://cdnjs.cloudflare.com/ajax/libs/angular-ui-select/0.20.0/select.min.js'
					]
				},
				
				{
					name: 'angular-messages',
					files: [
						'https://ajax.googleapis.com/ajax/libs/angularjs/1.5.0-rc.0/angular-messages.min.js',
						'js/directives/compare-to.directive.js'
					]
				},
				
				{
					name: 'sweetalert2',
					files: [
						'https://cdnjs.cloudflare.com/ajax/libs/limonte-sweetalert2/6.11.5/sweetalert2.all.min.js'
					]
				}
			
			]
		});
	}
);
