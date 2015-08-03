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
	var i = document.getElementById('play_pause_img');
	
	if (e.paused)
	{
		i.src = "./imgs/play.png";
	}
	else
	{
		i.src = "./imgs/pause.png";
	}
}