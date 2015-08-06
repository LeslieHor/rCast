var app = angular.module('myApp', []);
app.controller('myCtrl', function($scope, $http) {
	$http.get("podcasts/podcasts.json")
	.success(function(response) {
		$scope.podcasts = response.podcasts;
	});
	
	$scope.load_episodes = function(podcast) {
		podcast.show = true;
		// Don't bother loading episodes if we already have them.
		if (typeof podcast.episodes !== 'undefined')
		{
			return;
		}
		
		// Load in the episodes
		$http.get("podcasts/podcast_data/" + podcast.md5 + ".json")
		.success(function(response) {
			// Pass the data to the master array
			podcast.episodes = response.episodes;
		});
	};
});
app.filter('secondsToDateTime', [function() {
    return function(seconds) {
        return new Date(1970, 0, 1).setSeconds(seconds);
    };
}])