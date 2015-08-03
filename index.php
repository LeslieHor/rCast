<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <!-- The above 3 meta tags *must* come first in the head; any other head content must come *after* these tags -->
    <meta name="description" content="An online podcast manager.">
    <meta name="author" content="Leslie Hor">
    <!--<link rel="icon" href="../../favicon.ico">-->
	<script src="http://ajax.googleapis.com/ajax/libs/angularjs/1.3.14/angular.min.js"></script>

    <title>Podcast Manager</title>

    <!-- Bootstrap core CSS -->
    <link href="libs/bootstrap/css/bootstrap.min.css" rel="stylesheet">

    <!-- Custom styles for this template -->
    <link href="style.css" rel="stylesheet">
	
  </head>

  <body>

    <nav class="navbar navbar-inverse navbar-fixed-top">
      <div class="container">
        <div class="navbar-header">
          <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#navbar" aria-expanded="false" aria-controls="navbar">
            <span class="sr-only">Toggle navigation</span>
            <span class="icon-bar"></span>
            <span class="icon-bar"></span>
            <span class="icon-bar"></span>
          </button>
          <a class="navbar-brand" href="#">Podcast Manager</a>
        </div>
        <div id="navbar" class="collapse navbar-collapse">
          <ul class="nav navbar-nav">
            <li class="active"><a href="#">Home</a></li>
            <li><a href="#about">About</a></li>
            <li><a href="#contact">Contact</a></li>
          </ul>
        </div><!--/.nav-collapse -->
      </div>
    </nav>

    <div class="container">
		<div class="main-content" ng-app="myApp" ng-controller="myCtrl" >
			<!-- Shows the currently playing track -->
			<div class="current_track">Currently Playing: {{ current_track }}</div>
			
			<!-- Lists all the podcasts and their shows -->
			<div ng-repeat="x in podcasts">
				<div class="podcast">
				{{ x.name + " - " + (x.total_episodes + 1) }}
				</div>
				<div class="episode" ng-repeat="y in x.episodes">
				<!-- Alternate row colours by changing the class if it's odd or if it's even -->
				<div ng-class-odd="'odd'" ng-class-even="'even'">
					<div class="title">{{ y.title }}</div>
					<div class="status">{{ y.status }}</div>
					<div class="status"><a href="{{ y.download_url }}">DOWNLOAD</a></div>
					<div class="time">{{ y.bookmark }} / {{ y.total_time | secondsToDateTime | date:'HH:mm:ss' }}</div>
					<div class="size">{{ y.size }}</div>
					<div class="publish_date">{{ y.publish_date }}</div>
				</div>
				</div>
			</div>
			
		</div>

    </div><!-- /.container -->
	
	<nav class="navbar navbar-default navbar-fixed-bottom" role="navigation">
		<div class="container">
		<!-- Floating player controls at the botttom of the page -->
			<div class="player_container">
				<div class="player">
					<div class="audio_player">
						<audio class="audio_player" id="audio_player" src="./podcasts/podcast_files/this_american_life/20150727_443_amusement_park.mp3" preload="auto" controls ontimeupdate="update_time();">
							<p>Your browser does not support the audio element</p>
						</audio>
					</div>
					<div class="player_controls">
						<span class="player_control"><a href="javascript:void(0);" onclick=""><img class="player_control_img" src="./imgs/prev.png"></a></span>
						<span class="player_control"><a href="javascript:void(0);" onclick="togglePlayState()"><img class="player_control_img" id="play_pause_img" src="./imgs/play.png"></a></span>
						<span class="player_control"><a href="javascript:void(0);" onclick=""><img class="player_control_img" src="./imgs/next.png"></a></span>
						<!--<span class="player_control">Mute</span>-->
					</div>
				</div>
			</div>
		</div>
	</nav>


    <!-- Bootstrap core JavaScript
    ================================================== -->
    <!-- Placed at the end of the document so the pages load faster -->
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.3/jquery.min.js"></script>
    <script src="libs/bootstrap/js/bootstrap.min.js"></script>
	<script src="app.js"></script>
	<script src="player_controls.js"></script>
  </body>
</html>
