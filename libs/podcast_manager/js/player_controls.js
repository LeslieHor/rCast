function update_time()
{
	var e = document.getElementById('audio_player');
	var percentage = (e.currentTime / e.duration) * 100; 
	$('#player_progress_bar1').css('width', percentage+'%');
	
	var scope = angular.element("#main-content").scope();
	scope.$apply(function(){
        scope.current_time = e.currentTime;
        scope.total_time = e.duration;
    })
}

function togglePlayState()
{
	var e = document.getElementById('audio_player');
	if (e.paused)
	{
		play();
	} 
	else
	{
		pause();
	}
	
	setPlayPauseImage();
}

function play()
{
	var e = document.getElementById('audio_player');
	e.play();
	
	setPlayPauseImage();
}

function pause()
{
	var e = document.getElementById('audio_player');
	e.pause();
	
	setPlayPauseImage();
}

function toggle_audio_control_visibility()
{
	$('.audio_player').toggle();
}

function toggle_player_information_visibility()
{
	$('.player_information').toggle();
}

/*
function next()
{
	currentTrack += 1;
	playFile(playlist[currentTrack]);
	
	setPlayPauseImage();
}

function prev()
{
	currentTrack -= 1;
	playFile(playlist[currentTrack]);
	
	setPlayPauseImage();
}
*/

function mute()
{
	var e = document.getElementById('audio_player');
	e.muted = !e.muted;
	if (e.muted)
	{
		$('#mute').removeClass("glyphicon-volume-up");
		$('#mute').addClass("glyphicon-volume-off");
	}
	else
	{
		$('#mute').removeClass("glyphicon-volume-off");
		$('#mute').addClass("glyphicon-volume-up");
	}
}

function setPlayPauseImage()
{
	var e = document.getElementById('audio_player');
	var i = document.getElementById('play_pause');
	
	if (e.paused)
	{
		$('#play_pause').removeClass("glyphicon-pause");
		$('#play_pause').addClass("glyphicon-play");
		//$('#play_pause').switchClass('glyphicon-play', 'glyphicon-pause');
	}
	else
	{
		$('#play_pause').removeClass("glyphicon-play");
		$('#play_pause').addClass("glyphicon-pause");
		//$('#play_pause').switchClass('glyphicon-pause', 'glyphicon-play');
	}
}