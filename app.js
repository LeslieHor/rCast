var app = angular.module('myApp', []);
app.controller('myCtrl', function($scope, $http) {
	$http.get("podcasts/podcast_data/podcasts.json")
	.success(function(response) {
		$scope.podcasts = response.podcasts;
		$scope.current_track = response.current_track;
		});
});
app.filter('secondsToDateTime', [function() {
    return function(seconds) {
        return new Date(1970, 0, 1).setSeconds(seconds);
    };
}])