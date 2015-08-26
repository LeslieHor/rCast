var current_time;

$(document).ready(function() {
	$('.audio_player').hide();
	current_time = 0;
}); //document.ready

// Delete this at some point
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
			if (!output)
			{
				alert("This is an invalid podcast feed");
			}
			var scope = angular.element("#head_html").scope();
			scope.refresh_data();
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
			var scope = angular.element("#head_html").scope();
			scope.$apply();
		}
	}); //Ajax call
}
