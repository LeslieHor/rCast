var app = angular.module('myApp', ['ui.bootstrap']);
app.controller('myCtrl', function($scope, $http) {
	// Reserving variable names
	$scope.current_podcast = "";
	$scope.current_track = "";
	
	// Reserving variable names
	$scope.current_time = 0;
	$scope.total_time = 0;
	
	$scope.refresh_data = function() {
		$http.get("podcasts/podcasts.json")
		.success(function(response) {
			$scope.podcasts = response.podcasts;
			
			angular.forEach($scope.podcasts, function(podcast, key){
				$http.get("podcasts/podcast_data/" + podcast.md5 + ".json",  { headers: { 'Cache-Control' : 'no-cache' } })
				.success(function(response) {
					// Pass the data to the master array
					podcast.episodes = response.episodes;
				});
			});
		});
	};
	
	$scope.refresh_data();
	
	// Download an episode
	$scope.download_episode = function(podcast, episode) {
		episode.status = 1;
		$.ajax({
			url: 'common.php',
			data: {
				action: 'download_episode',
				podcast_md5: podcast.md5,
				episode_md5: episode.md5
			},
			type: 'post',
			success: function(output) {
				alert(output);
				$http.get("podcasts/podcast_data/" + podcast.md5 + ".json")
				.success(function(response) {
					// Pass the data to the master array
					episode.status = 2;
					episode.local_path = output;
				});
			}
		}); //Ajax call
	};
	
	$scope.play_episode = function(podcast, episode) {
		episode.status = 3;
		var e = document.getElementById('audio_player');
		e.src = 'podcasts/podcast_files/' + episode.local_path;
		
		$scope.current_podcast = podcast.name;
		$scope.current_track = episode.title;
		
		setTimeout($scope.load_time(episode), 1000);
		
		play();
	};
	
	$scope.load_time = function(episode) {
		var e = document.getElementById('audio_player');
		e.currentTime = episode.bookmark;
	}
	
	
	$scope.save_time = function(podcast, episode){
		var e = document.getElementById('audio_player');
		var time = e.currentTime;
		
		$.ajax({
			url: 'common.php',
			data: {
				action: 'save_time',
				podcast_md5: podcast.md5,
				episode_md5: episode.md5,
				time: time
			},
			type: 'post',
			success: function(output) {
				episode.bookmark = time;
			}
		}); //Ajax call
	};
	
	$scope.update_feed = function(podcast){
		var feed_url = podcast.url;
		$.ajax({
			url: 'common.php',
			data: {
				action: 'update_feed',
				feed_url: feed_url,
			},
			type: 'post',
			success: function(output) {
				alert(output);
				var scope = angular.element("#main-content").scope();
				scope.$apply();
			}
		}); //Ajax call
	};
	
	$scope.debug = function(object){
		alert(JSON.stringify(object));
	};
	$scope.prev = function(){
		var e = document.getElementById('audio_player');
		e.currentTime = e.currentTime - 30;
	}
	
	$scope.next = function(){
		var e = document.getElementById('audio_player');
		e.currentTime = e.currentTime + 30;
	}
});

app.filter('secondsToDateTime', [function() {
    return function(seconds) {
        return new Date(1970, 0, 1).setSeconds(seconds);
    };
}])

app.filter('intToStatus', [function() {
    return function(statusInt) {
		switch (statusInt)
		{
			case 0:
				return 'Not Downloaded';
			case 1:
				return 'Downloading';
			case 2:
				return 'Downloaded';
			case 3:
				return 'In Progress';
			case 4:
				return 'Finished';
			case 5:
				return 'Deleted';
			default:
				return statusInt;
		}
    };
}])