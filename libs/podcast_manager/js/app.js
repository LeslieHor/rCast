var app = angular.module('myApp', ['ui.bootstrap']);
app.controller('myCtrl', function($scope, $http) {
	// Reserving variable names
	$scope.current_podcast = "";
	$scope.current_track = "";
	
	// Reserving variable names
	$scope.current_time = 0;
	$scope.total_time = 0;
	
	$scope.toggle_footer = function() {
		// Toggle the status of the footer.
		if ($('#footer').hasClass("collapse"))
		{
			$scope.show_footer();
		}
		else
		{
			$scope.hide_footer();
		}
	}
	
	$scope.show_footer = function() {
		$('#footer').removeClass("collapse");
	}
	
	$scope.hide_footer = function() {
		$('#footer').addClass("collapse");
	}
	
	// Loads the settings file and calls the initial load_data function
	$scope.load_settings = function() {
		$http.get("settings.json" + '?id=' + new Date().getTime())
		.success(function(response) {
			$scope.name = response.name;
			$scope.version = response.version;
			$scope.podcast_file_path = response.podcast_file_path;
			$scope.podcast_data_path = response.podcast_data_path;
			$scope.podcasts_head_file = response.podcasts_head_file;
			$scope.load_data();
		});
		
	};
	
	$scope.refresh_data = function() {
		$http.get($scope.podcasts_head_file + '?id=' + new Date().getTime())
		.success(function(response) {
			temp_podcasts = response.podcasts;
			angular.forEach(temp_podcasts, function(temp_podcast, temp_key){
				var match_found = false;
				angular.forEach($scope.podcasts, function(podcast, key){
					if (match_found == false)
					{
						if (temp_podcast.md5 == podcast.md5)
						{
							match_found = true;
						}
					}
				});
				
				if (match_found == false)
				{
					// Add the new podcast to the array
					$scope.podcasts.push(temp_podcast);
				}
			});
		});
		
		angular.forEach($scope.podcasts, function(podcast, key){
			if (typeof podcast.episodes == "undefined" || !(podcast.episodes instanceof Array)) {
				podcast.episodes = [];
			}
			
			$http.get($scope.podcast_data_path + podcast.md5 + ".json" + '?id=' + new Date().getTime())
			.success(function(response) {
				temp_episodes = response.episodes;
				var episode_counter = 0;
				angular.forEach(temp_episodes, function(temp_episode, temp_key){
					var match_found = false;
					angular.forEach(podcast.episodes, function(episode, key){
						if (match_found == false)
						{
							if (episode.md5 == temp_episode.md5)
							{
								match_found = true;
							}
						}
					});
					
					if (match_found == false)
					{
						(podcast.episodes).splice(episode_counter, 0, temp_episode);
						episode_counter++;
					}
				});
			});
		});
	};
	
	$scope.load_data = function() { // Fast load for the first time
		$http.get($scope.podcasts_head_file + '?id=' + new Date().getTime())
		.success(function(response) {
			$scope.podcasts = response.podcasts;
			
			angular.forEach($scope.podcasts, function(podcast, key){
				$http.get($scope.podcast_data_path + podcast.md5 + ".json" + '?id=' + new Date().getTime())
				.success(function(response) {
					// Pass the data to the master array
					podcast.episodes = response.episodes;
				});
			});
		});
	};
	
	$scope.delete_podcast = function(podcast){
		if (confirm("Are you sure you want to delete this episode?")) {
			$.ajax({
				url: 'common.php',
				data: {
					action: 'delete_podcast',
					podcast_md5: podcast.md5
				},
				type: 'post',
				success: function(output) {
					var index = $scope.podcasts.indexOf(podcast);
					if (index > -1) {
						$scope.podcasts.splice(index, 1);
					}
					$scope.$apply();
				}
			}); //Ajax call
		}
	};
	
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
				$http.get($scope.podcast_data_path + podcast.md5 + ".json" + '?id=' + new Date().getTime())
				.success(function(response) {
					// Pass the data to the master array
					episode.status = 2;
					episode.local_path = output;
				});
			}
		}); //Ajax call
	};
	
	$scope.play_episode = function(podcast, episode) {
		var e = document.getElementById('audio_player');
		if (e.src.length > 0){
			$scope.save_time_specific($scope.podcast, $scope.episode);
		}
		
		$scope.podcast = podcast;
		$scope.episode = episode;
		episode.status = 3;
		
		$scope.set_status(podcast, episode, status);
		
		var e = document.getElementById('audio_player');
		e.src = $scope.podcast_file_path + episode.local_path;
		
		$('#player_bar').attr('max', episode.total_time);
		
		$scope.current_podcast = podcast.name;
		$scope.current_track = episode.title;
		
		$scope.load_time(episode);
		$scope.show_footer();
		
		play();
	};
	
	$scope.set_status = function(podcast, episode, status)
	{
		$.ajax({
			url: 'common.php',
			data: {
				action: 'set_status',
				podcast_md5: podcast.md5,
				episode_md5: episode.md5,
				status: status
			},
			type: 'post',
			success: function(output) {
				
			}
		}); //Ajax call
	}
	
	$scope.load_time = function(episode) {
		var e = document.getElementById('audio_player');
		e.currentTime = episode.bookmark;
	}
	
	$scope.finished_episode_current_track = function(){
		$scope.finished_episode($scope.podcast, $scope.episode);
	}
	
	$scope.finished_episode = function(podcast, episode) {
		$.ajax({
			url: 'common.php',
			data: {
				action: 'finished_episode',
				podcast_md5: podcast.md5,
				episode_md5: episode.md5
			},
			type: 'post',
			success: function(output) {
				episode.status = 4;
				$scope.$apply();
			}
		}); //Ajax call
	}
	
	// Delete the episode
	$scope.delete_episode = function(podcast, episode) {
		if (confirm("Are you sure you want to delete this episode?")) {
			$.ajax({
				url: 'common.php',
				data: {
					action: 'delete_episode',
					podcast_md5: podcast.md5,
					episode_md5: episode.md5
				},
				type: 'post',
				success: function(output) {
					episode.status = 5;
					episode.bookmark = 0;
					episode.local_path = '';
					$scope.$apply();
				}
			}); //Ajax call
		}
	}
	
	// Reset an episode back to status 0
	$scope.reset_episode = function(podcast, episode){
		$.ajax({
			url: 'common.php',
			data: {
				action: 'reset_episode',
				podcast_md5: podcast.md5,
				episode_md5: episode.md5
			},
			type: 'post',
			success: function(output) {
				episode.status = 0;
				episode.bookmark = 0;
				episode.local_path = '';
				$scope.$apply();
			}
		}); //Ajax call
	}
	
	// Save the time for the currently playing episode
	$scope.save_time = function(){
		var e = document.getElementById('audio_player');
		var time = e.currentTime;
		
		$.ajax({
			url: 'common.php',
			data: {
				action: 'save_time',
				podcast_md5: $scope.podcast.md5,
				episode_md5: $scope.episode.md5,
				time: time
			},
			type: 'post',
			success: function(output) {
				$scope.episode.bookmark = time;
			}
		}); //Ajax call
	};
	
	// Save the time for a specific episode as given
	$scope.save_time_specific = function(podcast, episode){
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
				$scope.refresh_data();
				$scope.refresh_data();
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
	
	$scope.play = function(){
		
	}
	
	$scope.pause = function(){
		
	}
	
	$scope.toggle_play_pause = function(){
		
	}
	
	$scope.load_settings();
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