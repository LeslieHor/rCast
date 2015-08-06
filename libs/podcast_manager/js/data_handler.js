$('body').on('click', '#refresh_data', function() {
	alert("test");
	navigator.vibrate(1000);
	//$(this).next(".feed_category").slideToggle(function() {
	//	var expand_state = $(this).css('display');
    //
	//	$.ajax({
	//		url: 'common.php',
	//		data: {
	//			action: 'set_display',
	//			category_title: category_title,
	//			expand_state: expand_state
	//		},
	//		type: 'post',
	//		success: function(output) {
    //
	//		}
	//	}); //Ajax call
	//});
}); //event handler