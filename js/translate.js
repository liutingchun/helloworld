app.config(['$translateProvider', function ($translateProvider) {	
	$translateProvider.useLoader('asyncLoader');
	$translateProvider.preferredLanguage('zhTW');
	
	$translateProvider.useSanitizeValueStrategy('escape');
}]);

app.factory('asyncLoader', function ($http, $q, $timeout) {
		
	return function (options) {		
		var deferred = $q.defer(); 
		
		$http({
			method : 'POST',
			url : 'backend/translate-get-message-bundle-from-txt.php',
			data: {
				locale: options.key
			}
		}).then(function(response){	
			//console.log(options.key);
			//console.log(response.data);
			/*console.log(response.data);
		
			var translate = {};
			
			for (var index in response.data) {							
				translate[response.data[index].messageKey] = response.data[index][options.key];
			}
		
			deferred.resolve(translate);*/
			
			//console.log(response.data);
			
			deferred.resolve(response.data);
		});
			
		return deferred.promise;
  };
});