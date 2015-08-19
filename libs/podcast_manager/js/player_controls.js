
function update_time()
{
	// Set e to reference the audio player
	var e = document.getElementById('audio_player');
	
	// If the slider bar max is not the same as the e.duration, set it to be the e.duration
	if ($('#player_bar').attr('max') != e.duration)
	{	
		$('#player_bar').attr('max', e.duration);
	}
	
	// Set the value of the slider bar to the current time of the audio player
	$('#player_bar').val(e.currentTime);
	
	// Update the relevant AngularJS scope values
	var scope = angular.element("#main-content").scope();
	scope.$apply(function(){
        scope.current_time = e.currentTime;
        scope.total_time = e.duration; // This is a bit redundant. It only needs to be done once, not every time the current time changes.
		
		if ((e.currentTime > (e.duration - 15)) && (scope.episode['status'] < 4))
		{
			scope.finished_episode();
		}
		else if (current_time < (e.currentTime - 10))
		{
			scope.save_time();
			current_time = e.currentTime;
		}
    })
}

function change_time()
{
	// Set e to reference the audio player
	var e = document.getElementById('audio_player');
	
	// Set the audio player's current time to what the user selects on the slider bar.
	e.currentTime = $('#player_bar').val();
	
	save_time();
}

function togglePlayState()
{
	// Set e to reference the audio player
	var e = document.getElementById('audio_player');
	
	// Toggle between play and pause
	if (e.paused)
	{
		play();
	} 
	else
	{
		pause();
	}
	
	save_time();
}

function save_time()
{
	// Save the time
	var scope = angular.element("#head_html").scope();
	scope.$apply(function(){
		scope.save_time();
    })
}

function play()
{
	// Set e to reference the audio player
	var e = document.getElementById('audio_player');
	
	// Set the audio player to play
	e.play();
	
	// Sets the correct icon to match the play state of the audio player
	setPlayPauseImage();
}

function pause()
{
	// Set e to reference the audio player
	var e = document.getElementById('audio_player');
	
	// Set the audio player to pause
	e.pause();
	
	// Sets the correct icon to match the play state of the audio player
	setPlayPauseImage();
}

function toggle_audio_control_visibility()
{
	// Show/hide the audio player
	$('.audio_player').toggle();
}

function toggle_player_information_visibility()
{
	// Show/hide the current track information
	$('.player_information').toggle();
}

function mute()
{
	// Set e to reference the audio player
	var e = document.getElementById('audio_player');
	
	// Toggle the mute status
	e.muted = !e.muted;
	
	// Set the correct icon to match the mute status
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
	// Set e to reference the audio player
	var e = document.getElementById('audio_player');
	
	// Sets the correct icon to match the play state of the audio player
	if (e.paused)
	{
		$('#play_pause').removeClass("glyphicon-pause");
		$('#play_pause').addClass("glyphicon-play");
	}
	else
	{
		$('#play_pause').removeClass("glyphicon-play");
		$('#play_pause').addClass("glyphicon-pause");
	}
}