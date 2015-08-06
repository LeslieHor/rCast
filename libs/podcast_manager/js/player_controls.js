function update_time()
{
	
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
	/*
	alert("test");
	var img = document.getElementById('toggle_audio_control_visibility').src;
	alert(img);
    if (img.indexOf('imgs/up_square.png')!=-1) {
        document.getElementById('toggle_audio_control_visibility').src  = 'imgs/down_square.png';
    }
     else {
       document.getElementById('toggle_audio_control_visibility').src = 'imgs/down_square.png';
    }*/
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