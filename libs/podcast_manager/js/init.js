$(document).ready(function() {
	$('.audio_player').hide();
}); //document.ready

$('#player_progress_bar').slider({
	formatter: function(value) {
		return 'Current value: ' + value;
	}
});

function test_function(){
	var e = document.getElementById('audio_player');
	alert(e.currentTime);
}

function update_feed(){
	var feed_url = $('#feed_url').val();
	
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

function update_all_feeds(){
	$.ajax({
		url: 'common.php',
		data: {
			action: 'update_all_feeds'
		},
		type: 'post',
		success: function(output) {
			alert("done");
			var scope = angular.element("#main-content").scope();
			scope.$apply();
		}
	}); //Ajax call
}
