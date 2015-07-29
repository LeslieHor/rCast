<html>
<head>
<script src="/data/libs/jquery-1.11.2.min.js"></script>
<script src="/data/libs/cryptojs3.1.2/rollups/md5.js"></script>
<script src="http://ajax.googleapis.com/ajax/libs/angularjs/1.3.14/angular.min.js"></script>
<link rel="stylesheet" type="text/css" href="style.css"/>
<!--<link rel="icon" type="image/png"  href="favicon.png"/>-->
<meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no" />
<title>Podcast Manager</title>
</head>

<div id="container">
	<div ng-app="myApp" ng-controller="myCtrl" ><!--ng-init="test='gooble'"-->
	<!--
		<p>TEST: <input type="text" ng-model="test"></p>
		<p ng-bind="test"></p>
		<p>{{ test }}</p>
		<p>{{ 5 + 5 }}</p>
		-->
		<div class="current_track">{{ current_track }}</div>
		<div ng-repeat="x in podcasts">
			<div class="podcast">
			{{ x.name + " - " + (x.total_episodes + 1) }}
			</div>
			<div class="episode" ng-repeat="y in x.episodes">
			<div ng-if="$odd" style="background-color:#6B919A">Title: {{ y.title }}</div>
			<div ng-if="$even" style="background-color:#44747F">Title: {{ y.title }}</div>
			</div>
		</div>
		
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
/*
var app = angular.module('myApp', []);
app.controller('myCtrl', function($scope) {
	$scope.current_track = "#001 - Bitches gotsta learn";
	$scope.podcasts = [
	{
		name:"This American Life",
		total_episodes:3,
		episodes:[
		{
			title:"#001 - Bitches gotsta learn",
			url:"whatever/mp3.mp3",
			status:0,
			bookmark:203.02
		}
		,{
			title:"#003 - I am one with everything",
			url:"whaddtever/mp3.mp3",
			status:0,
			bookmark:203.02
		}
		,{
			title:"#004 - Needle in the thread",
			url:"whaddtever/mp3.mp3",
			status:0,
			bookmark:203.02
		}
		]
	}
	,{
		name:"Radio Lab",
		total_episodes:144,
		episodes:[
		{
			title:"#002 - We have a situation",
			url:"whatever/2p3.mp3",
			status:1,
			bookmark:203.02
		}
		]
	}
	];
});
*/
var app = angular.module('myApp', []);
app.controller('myCtrl', function($scope, $http) {
	$http.get("fake_data.php")
	.success(function(response) {
		$scope.podcasts = response.podcasts;
		$scope.current_track = response.current_track;
		});
});
</script>
</html>
