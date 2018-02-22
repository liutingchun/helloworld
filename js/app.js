'use strict';

//var myApp = angular.module('helloworld', ['ui.router']);

//var app = angular.module('myApp', ['ui.router', 'pascalprecht.translate', 'oc.lazyLoad', 'datatables', 'ngMessages', 'angularFileUpload', 'ngMap', 'ui.select', 'ui.bootstrap']);
var app = angular.module('myApp', ['ui.router', 'pascalprecht.translate', 'oc.lazyLoad']);

app.config(
	function($stateProvider, $urlRouterProvider) {

		$stateProvider
					
		.state('root', {
			url: '',
			abstract: true,
			views: {
				'root': {
					templateUrl: 'partials/root.html',
					controller: 'RootController',
					controllerAs: 'rCtrl'
				}
			},
			resolve: {
				loggedInUserRequest: function($http) {
					return $http({
						method : 'POST',
						url : 'backend/authentication-get-logged-in-user.php'
					});
				}
			} 
		})
		
		.state('root.home', {
			url: '/home',
			views: {
				'header': {templateUrl: 'partials/menu-bar.html'},
				'content': {templateUrl: 'partials/home.html'}
			}
		})
				
		.state('root.info-order-guide', {
			url: '/info-order-guide',
			views: {
				'header': {templateUrl: 'partials/menu-bar.html'},
				'content': {templateUrl: 'partials/info-order-guide.html'}
			}
		})
		
		.state('root.info-faq', {
			url: '/info-faq',
			views: {
				'header': {templateUrl: 'partials/menu-bar.html'},
				'content': {templateUrl: 'partials/info-faq.html'}
			}
		})
		
		.state('root.info-contact', {
			url: '/info-contact',
			views: {
				'header': {templateUrl: 'partials/menu-bar.html'},
				'content': {templateUrl: 'partials/info-contact.html'}
			},
			resolve: {
				lazyLoadPlugin: function($ocLazyLoad) {
					return $ocLazyLoad.load(['ngMap']);
				}
			}
		})
		
		.state('root.info-terms', {
			url: '/info-terms',
			views: {
				'header': {templateUrl: 'partials/menu-bar.html'},
				'content': {templateUrl: 'partials/info-terms.html'}
			}
		})
		
		.state('root.product-grid', {
			url: '/product-grid',
			params: {
				category: null,
				keyword: null,
				tag: null
			},
			views: {
				'header': {templateUrl: 'partials/menu-bar.html'},
				'content': {templateUrl: 'partials/product-grid.html'}
			},
			resolve: {
				lazyLoadPlugin: function($ocLazyLoad) {
					return $ocLazyLoad.load(['iCheck']);
				}
			}
		})
		
		.state('root.product-detail', {
			url: '/product-detail/:pid',
			views: {
				'header': {templateUrl: 'partials/menu-bar.html'},
				'content': {templateUrl: 'partials/product-detail.html'}
			},
			resolve: {
				lazyLoadPlugin: function($ocLazyLoad) {
					return $ocLazyLoad.load(['ui-select', 'sweetalert2']);
				}
			}
		})
		
		.state('root.form-login', {
			url: '/form-login',
			params: {
				category: null
			},
			views: {
				'header': {templateUrl: 'partials/menu-bar.html'},
				'content': {templateUrl: 'partials/form-login.html'}
			},
			resolve: {
				lazyLoadPlugin: function($ocLazyLoad) {
					return $ocLazyLoad.load(['sweetalert2']);
				}
			}
		})
		
		.state('root.form-search-order', {
			url: '/form-search-order',
			views: {
				'header': {templateUrl: 'partials/menu-bar.html'},
				'content': {templateUrl: 'partials/form-search-order.html'}
			}
		})
		
		.state('root.form-register', {
			url: '/form-register',
			views: {
				'header': {templateUrl: 'partials/menu-bar.html'},
				'content': {templateUrl: 'partials/form-register.html'}
			},
			resolve: {
				lazyLoadPlugin: function($ocLazyLoad) {
					return $ocLazyLoad.load(['angular-messages', 'sweetalert2']);
				}
			}
		})
		
		.state('root.cart', {
			url: '/cart',
			views: {
				'header': {templateUrl: 'partials/menu-bar.html'},
				'content': {templateUrl: 'partials/cart.html'}
			},
			resolve: {
				lazyLoadPlugin: function($ocLazyLoad) {
					return $ocLazyLoad.load(['sweetalert2']);
				}
			}
		})
		
		.state('root.order-history', {
			url: '/order-history/:phone/:email',
			views: {
				'header': {templateUrl: 'partials/menu-bar.html'},
				'content': {templateUrl: 'partials/order-history.html'}
			},
			resolve: {
				lazyLoadPlugin: function($ocLazyLoad) {
					return $ocLazyLoad.load(['datatables', 'sweetalert2']);
				}
			}
		})
		
		.state('root.order-detail', {
			url: '/order-detail/:serial/:phone/:email',
			views: {
				'header': {templateUrl: 'partials/menu-bar.html'},
				'content': {templateUrl: 'partials/order-detail.html'}
			},
			resolve: {
				lazyLoadPlugin: function($ocLazyLoad) {
					return $ocLazyLoad.load(['angularFileUpload', 'ui-bootstrap', 'PayPal-checkout', 'sweetalert2']);
				}
			}
		})
				
		.state('root.admin', {
			url: '/admin',
			views: {
				'header': {templateUrl: 'partials/menu-bar.html'},
				'content': {
					templateUrl: 'partials/admin/admin-home.html',
					controller: 'AdminHomeController'
				}
			},
			abstract: true,
			resolve: {

			} 
		})
		
		.state('root.admin.form-add-product', {
			url: '/form-add-product',
			views: {
				'protected': {templateUrl: 'partials/admin/form-add-product.html'}
			},
			resolve: {
				lazyLoadPlugin: function($ocLazyLoad) {
					return $ocLazyLoad.load(['ui-select', 'angular-messages', 'sweetalert2']);
				}
			}
		})
		
		.state('root.admin.form-update-product', {
			url: '/form-update-product/:pid',
			views: {
				'protected': {templateUrl: 'partials/admin/form-update-product.html'}
			},
			resolve: {
				lazyLoadPlugin: function($ocLazyLoad) {
					return $ocLazyLoad.load(['ui-select', 'angularFileUpload', 'angular-messages', 'sweetalert2']);
				}
			}
		})
		
		.state('root.admin.product-list', {
			url: '/product-list',
			views: {
				'protected': {templateUrl: 'partials/admin/product-list.html'}
			},
			resolve: {
				lazyLoadPlugin: function($ocLazyLoad) {
					return $ocLazyLoad.load(['datatables']);
				}
			}
		})
		
		.state('root.admin.order-list', {
			url: '/order-list',
			views: {
				'protected': {templateUrl: 'partials/admin/order-list.html'}
			},
			resolve: {
				lazyLoadPlugin: function($ocLazyLoad) {
					return $ocLazyLoad.load(['datatables', 'ui-select', 'sweetalert2']);
				}
			}
		})
		
		.state('root.admin.form-add-banner', {
			url: '/form-add-banner',
			views: {
				'protected': {templateUrl: 'partials/admin/form-add-banner.html'}
			},
			resolve: {
				lazyLoadPlugin: function($ocLazyLoad) {
					return $ocLazyLoad.load(['angularFileUpload', 'ui-select', 'angular-messages', 'sweetalert2']);
				}
			}
		})
		
		.state('root.admin.banner-list', {
			url: '/banner-list',
			views: {
				'protected': {templateUrl: 'partials/admin/banner-list.html'}
			},
			resolve: {
				lazyLoadPlugin: function($ocLazyLoad) {
					return $ocLazyLoad.load(['ui-select', 'sweetalert2']);
				}
			}
		})
		
		.state('root.admin.form-add-discount-group', {
			url: '/form-add-discount-group',
			views: {
				'protected': {templateUrl: 'partials/admin/form-add-discount-group.html'}
			},
			resolve: {
				lazyLoadPlugin: function($ocLazyLoad) {
					return $ocLazyLoad.load(['ui-select', 'angular-messages', 'sweetalert2']);
				}
			}
		})
		
		.state('root.admin.discount-group-list', {
			url: '/discount-group-list',
			views: {
				'protected': {templateUrl: 'partials/admin/discount-group-list.html'}
			},
			resolve: {
				lazyLoadPlugin: function($ocLazyLoad) {
					return $ocLazyLoad.load(['ui-bootstrap', 'ui-select', 'sweetalert2']);
				}
			}
		})
		
		.state('root.admin.batch-job-list', {
			url: '/batch-job-list',
			views: {
				'protected': {templateUrl: 'partials/admin/batch-job-list.html'}
			},
			resolve: {
				lazyLoadPlugin: function($ocLazyLoad) {
					return $ocLazyLoad.load(['sweetalert2']);
				}
			}
		})
				
		$urlRouterProvider.otherwise('/home');
		
		/*$routeProvider.when('/home', {templateUrl: 'partials/home.html', controller: GenericViewCtrl});
		$routeProvider.when('/project_a', {templateUrl: 'partials/project_a.html', controller: GenericViewCtrl});
		$routeProvider.when('/project_b', {templateUrl: 'partials/project_b.html', controller: GenericViewCtrl});
		$routeProvider.when('/contact', {templateUrl: 'partials/contact.html', controller: ContactViewCtrl});
		$routeProvider.when('/imprint', {templateUrl: 'partials/imprint.html', controller: GenericViewCtrl});
		$routeProvider.when('/menu', {templateUrl: 'partials/menu/menu.html', controller: GenericViewCtrl});
		$routeProvider.otherwise({redirectTo: '/home'});*/
	}
);
