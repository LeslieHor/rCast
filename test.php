<?php
ini_set('display_errors',1);
ini_set('display_startup_errors',1);
error_reporting(-1);

include_once('common.php');
echo "Worked";

update_feed('http://feed.thisamericanlife.org/talpodcast?format=xml');

echo "complete";

function update_feed($url)
{
	$feed = load_feed_xml($url);
	$feed_md5 = md5($url);
	
	$podcasts = [];
	$podcasts["podcasts"] = [];
	
	$podcast = podcast($feed, $url);
	
	$episodes = [];
	
	foreach ($feed->get_items() as $item)
	{
		array_push($episodes, episode($item));
	}
	
	$podcast["episodes"] = $episodes;
	array_push($podcasts["podcasts"], $podcast);
	
	file_put_contents('podcasts/podcast_data/podcasts.json', json_encode($podcasts));
	return;
}

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

?>