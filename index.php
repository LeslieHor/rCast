<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <!-- The above 3 meta tags *must* come first in the head; any other head content must come *after* these tags -->
    <meta name="description" content="An online podcast manager.">
    <meta name="author" content="Leslie Hor">
    <link rel="icon" href="favicon.png">
	
	<!-- CDNs for: jQuery, AngularJS -->
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.3/jquery.min.js"></script>
	<script src="http://ajax.googleapis.com/ajax/libs/angularjs/1.3.14/angular.min.js"></script>

    <title>rCast</title>

    <!-- Bootstrap core CSS -->
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.5/css/bootstrap.min.css">

    <!-- Custom styles for this template -->
    <link href="style.css" rel="stylesheet">
  </head>

  <body>
	<div class="container">
		<div id="main-content"" class="main-content" ng-app="myApp" ng-controller="myCtrl" >
		
			<!-- Navigation bar -->
			<nav class="navbar navbar-inverse navbar-fixed-top">
			  <div class="container">
				<div class="navbar-header">
				  <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#navbar" aria-expanded="false" aria-controls="navbar">
					<span class="sr-only">Toggle navigation</span>
					<span class="icon-bar"></span>
					<span class="icon-bar"></span>
					<span class="icon-bar"></span>
				  </button>
				  <a class="navbar-brand" href="#">rCast</a>
				</div>
				<div id="navbar" class="collapse navbar-collapse">
				  <ul class="nav navbar-nav">
					<li>
						<form class="navbar-form navbar-left" role="group">
							<div class="form-group">
								<input id="feed_url" type="text" class="form-control" placeholder="Podcast URL">
							</div>
							<button type="add" class="btn btn-default" onclick="update_feed()"><span class="glyphicon glyphicon-plus"></span></button>
						</form>
					</li>
					<li><a id="update_all" href="#" onclick="update_all_feeds()">Update All</a></li>
					<li><a id="refresh_data" href="javascript:void(0);" ng-click="refresh_data()">REF</a></li>
					<li><a id="testt" href="javascript:void(0);" ng-click="test()">Test</a></li>
				  </ul>
				</div><!--/.nav-collapse -->
			  </div>
			</nav>
			
			<!-- Lists all the podcasts and their shows -->
			<div class="centre_panel">
			  <accordion close-others="true">
				<accordion-group ng-repeat="podcast in podcasts">
					<accordion-heading ng-click="load_episodes(podcast)">
						{{podcast.name}} <span class="badge">{{ (podcast.episodes).length }}</span>
					</accordion-heading>
					<div>
						<button class="btn btn-default" ng-click="update_feed(podcast)"><span class="glyphicon glyphicon-refresh"></span></button>
						<button class="btn btn-default" ng-click="delete_podcast(podcast)"><span class="glyphicon glyphicon-trash"></span></button>
						{{ podcast.url }}
					</div>
					<accordion close-others="false">
						<accordion-group ng-repeat="episode in podcast.episodes" >
							<accordion-heading>
								{{ episode.title }}
								
								<!-- Change label according to the episode status -->
								<span ng-class="{ 'label-info' : episode.status == 0 , 'label-warning' : episode.status == 1, 'label-success' : episode.status == 2, 'label-primary' : episode.status == 3, 'label-danger' : episode.status == 4, 'label-default' : episode.status == 5}" class="label">{{ episode.status | intToStatus }}</span>
							</accordion-heading>
							<div>
								<!-- Show podcast episode information -->
								<div class="publish_date"><h4>{{ episode.publish_date }}<h4></div>
								<div class="time">{{ episode.bookmark | secondsToDateTime | date:'HH:mm:ss' }} / {{ episode.total_time | secondsToDateTime | date:'HH:mm:ss' }}</div>
								<div class="size">{{ episode.size }}</div>
								
								<div class="description"><small>{{ episode.description }}</small></div>
								
								<!-- Buttons to show on status between 0 and 1 (not downloaded or downloading) -->
								<button ng-class="{ 'hide' : !(episode.status >= 0 && episode.status <= 1) }" class="btn btn-default" ng-click="download_episode(podcast, episode)"><span class="glyphicon glyphicon-download-alt"></span></button>
								
								<!-- Buttons to show on status between 2 and 4 (downloaded, in progress, or finished) -->
								<button ng-class="{ 'hide' : !(episode.status >= 2 && episode.status <= 4) }" class="btn btn-default" ng-click="play_episode(podcast, episode)"><span class="glyphicon glyphicon-play"></span></button>
								<button ng-class="{ 'hide' : !(episode.status >= 2 && episode.status <= 4) }" class="btn btn-default" ng-click="delete_episode(podcast, episode)"><span class="glyphicon glyphicon-trash"></span></button>
								
								<!-- Buttons to show on status 5 (deleted) -->
								<button ng-class="{ 'hide' : !(episode.status == 5) }" class="btn btn-default" ng-click="reset_episode(podcast, episode)"><span class="glyphicon glyphicon-repeat"></span></button>
								
								<!-- Overflow menu -->
								<div class="btn-group dropup">
								  <button type="button" class="btn btn-default dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
									Options <span class="caret"></span>
								  </button>
								  <ul class="dropdown-menu">
									<li><a href="#" onclick="alert('Not working')">Set as finished</a></li>
									<li><a href="#" onclick="alert('Not working')">Set as downloaded</a></li>
									<li><a href="#" onclick="alert('Not working')">Delete</a></li>
									<li><a href="#" onclick="alert('Not working')">Reset</a></li>
									<li role="separator" class="divider"></li>
									<li ng-class="{ 'hide' : !(episode.status >= 2 && episode.status <= 4) }"><a href="#" ng-click="save_time()">Save Time</a></li>
									<li><a href="#" ng-click="debug(episode)">Debug</a></li>
								  </ul>
								</div>
							</div>
						</accordion-group>
					</accordion>
				</accordion-group>
			  </accordion>
			</div>
	
			<!-- Footer containing the audio player -->
			<nav class="navbar navbar-default navbar-fixed-bottom" role="navigation">
				<div class="container">
				<!-- Floating player controls at the botttom of the page -->
					<div class="player_container">
						<div class="audio_player">
							<audio class="audio_player" id="audio_player" preload="auto" controls ontimeupdate="update_time();">
								<p>Your browser does not support the audio element</p>
							</audio>
						</div>
						
						<!-- Progress bar. Keeps up to date with the audio object time -->
						<div class="progress_input">
							  <input type="range" id="player_bar" value="50" min="0" max="100" onchange="change_time()">
						</div>
						
						
						<!-- Shows track information -->
						<div id="player_information" class="player_information">
							<small>{{ current_podcast }} - {{ current_track }}  {{ current_time | secondsToDateTime | date:'H:mm:ss'  }} / {{ total_time | secondsToDateTime | date:'H:mm:ss' }}</small>
						</div>
						
						<div class="player_controls">
							<!-- Menu -->
							<div class="dropup btn-group btn-group-lg">
							  <button class="btn btn-default dropdown-toggle" type="button" id="dropdownMenu2" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
								<span class="glyphicon glyphicon-chevron-up"></span>
							  </button>
							  <ul class="dropdown-menu" aria-labelledby="dropdownMenu2">
								<li><a href="#" onclick="toggle_audio_control_visibility()">Toggle Player</a></li>
								<li><a href="#" onclick="toggle_player_information_visibility()">Toggle Info</a></li>
							  </ul>
							</div>
					
							<!-- Prev Play Pause Next Controls -->
							<div class="main_player_controls btn-group btn-group-lg" role="group">
								<button type="button" class="btn btn-default btn-lg" ng-click="prev()"><span id="prev" class="glyphicon glyphicon-fast-backward"></span></button>
								<button type="button" class="btn btn-default btn-lg" onclick="togglePlayState()"><span id="play_pause" class="glyphicon glyphicon-play"></span></button>
								<button type="button" class="btn btn-default btn-lg" ng-click="next()"><span id="play_pause" class="glyphicon glyphicon-fast-forward"></span></button>
							</div>
							
							<!-- Mute -->
							<button type="button" class="btn btn-default btn-lg" onclick="mute()"><span id="mute" class="glyphicon glyphicon-volume-up"></span></button>
						</div>
					</div>
				</div>
			</nav>

			
		</div>
    </div><!-- /.container -->
	
	
    <!-- Bootstrap core JavaScript
    ================================================== -->
    <!-- Placed at the end of the document so the pages load faster -->
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.5/js/bootstrap.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/angular-ui-bootstrap/0.13.3/ui-bootstrap-tpls.js"></script>
	<script src="libs/podcast_manager/js/init.js"></script>
	<script src="libs/podcast_manager/js/app.js"></script>
	<script src="libs/podcast_manager/js/player_controls.js"></script>
  </body>
</html>
