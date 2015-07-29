<?php
ini_set('display_errors',1);
ini_set('display_startup_errors',1);
error_reporting(-1);

include_once('common.php');
//include_once('podcasts/get_podcasts.php');
echo "Worked";

update_feed('http://feed.thisamericanlife.org/talpodcast?format=xml');

echo "complete";

function update_feed($url)
{
	$feed = load_feed_xml($url);
	$feed_md5 = md5($url);
	
	$podcasts = [];
	$podcasts["podcasts"] = [];
	
	$podcast = [];
	$podcast["name"] = $feed->get_title();
	$podcast["url"] = $url;
	$podcast["md5"] = md5($url);
	
	$episodes = [];
	
	foreach ($feed->get_items() as $item)
	{
		$title = $item->get_title();
		$link = $item->get_link();
		$publish_date = $item->get_date();
		$md5 = md5($link);
		$status = 0; // FOR TESTING
		$enclosure = $item->get_enclosure();
		$download_url = $enclosure->get_link();
		
		array_push($episodes, episode($title, $download_url, $publish_date, $md5, $status));
	}
	
	$podcast["episodes"] = $episodes;
	array_push($podcasts["podcasts"], $podcast);
	
	file_put_contents('podcasts/podcast_data/podcasts.json', json_encode($podcasts));
	return;
}

function episode($title, $download_url, $publish_date, $md5, $status){
	$new_episode = [];
	$new_episode["title"] = $title;
	$new_episode["download_url"] = $download_url;
	$new_episode["publish_date"] = $publish_date;
	$new_episode["md5"] = $md5;
	$new_episode["status"] = $status;
	
	return $new_episode;
}

?>