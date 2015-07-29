<html>
<head>
<script src="/data/libs/jquery-1.11.2.min.js"></script>
<script src="http://ajax.googleapis.com/ajax/libs/angularjs/1.3.14/angular.min.js"></script>
<link rel="stylesheet" type="text/css" href="style.css"/>
<meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no" />
<title>Podcast Manager</title>
</head>

<div id="container">
	<div ng-app="myApp" ng-controller="myCtrl" >
		<!-- Shows the currently playing track -->
		<div class="current_track">Currently Playing: {{ current_track }}</div>
		
		<!-- Lists all the podcasts and their shows -->
		<div ng-repeat="x in podcasts">
			<div class="podcast">
			{{ x.name + " - " + (x.total_episodes + 1) }}
			</div>
			<div class="episode" ng-repeat="y in x.episodes">
			<!-- Alternate row colours. Odd rows have darker colour -->
			<div ng-if="$odd" style="background-color:#6B919A">
				<div class="title">{{ y.title }}</div>
				<div class="status">{{ y.status }}</div>
				<div class="status"><a href="{{ y.download_url }}">DOWNLOAD</a></div>
				<div class="time">{{ y.bookmark }} / {{ y.total_time }}</div>
				<div class="size">{{ y.size }}</div>
				<div class="publish_date">{{ y.publish_date }}</div>
			</div>
			<!-- Even rows have lighter colour -->
			<div ng-if="$even" style="background-color:#44747F">
				<div class="title">{{ y.title }}</div>
				<div class="status">{{ y.status }}</div>
				<div class="status"><a href="{{ y.download_url }}">DOWNLOAD</a></div>
				<div class="time">{{ y.bookmark }} / {{ y.total_time }}</div>
				<div class="size">{{ y.size }}</div>
				<div class="publish_date">{{ y.publish_date }}</div>
			</div>
			</div>
		</div>
		
		<!-- Floating player controls at the botttom of the page -->
		<div class="player_container">
			<div class="player">
				-------------------------------------------<br>
				Prev Play Next Mute
			</div>
		</div>
	</div>
</div>

</body>
<script>
var app = angular.module('myApp', []);
app.controller('myCtrl', function($scope, $http) {
	$http.get("podcasts/podcast_data/podcasts.json")
	.success(function(response) {
		$scope.podcasts = response.podcasts;
		$scope.current_track = response.current_track;
		});
});
</script>
</html>
