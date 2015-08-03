<?php
function podcast($feed, $url){
	$podcast = [];
	$podcast["name"] = $feed->get_title();
	$podcast["description"] = $feed->get_description();
	$podcast["url"] = $url;
	$podcast["md5"] = md5($url);
	
	return $podcast;
}

function episode($item){
	$new_episode = [];
	$new_episode["title"] = $item->get_title();
	$enclosure = $item->get_enclosure();
	$new_episode["download_url"] = $enclosure->get_link();
	$new_episode["publish_date"] = $item->get_date();
	$new_episode["md5"] = md5($enclosure->get_link());
	$new_episode["status"] = 0;
	$new_episode["total_time"] = $enclosure->get_duration();
	$new_episode["extension"] = pathinfo($enclosure->get_link(), PATHINFO_EXTENSION);
	
	return $new_episode;
}

function update_feed($feed_url)
{
	$feed_xml = load_feed_xml($feed_url);
	$feed_md5 = md5($feed_url);
	
	// Initialising an empty array to store the podcast information.
	$podcasts = [];
	$podcasts["podcasts"] = [];
	
	$podcast = podcast($feed_xml, $feed_url);
	
	$episodes = [];
	
	foreach ($feed_xml->get_items() as $item)
	{
		array_push($episodes, episode($item));
	}
	
	$podcast["episodes"] = $episodes;
	array_push($podcasts["podcasts"], $podcast);
	
	$path = $GLOBALS['podcast_data_path'] . $feed_md5 . '.json';
	save_json_data($podcasts, $path);
	return;
}

function get_feed_url($feed_md5)
{
	
}

function update_all_feeds()
{
	
}
?>