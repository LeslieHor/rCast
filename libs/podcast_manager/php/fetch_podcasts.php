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
	$new_episode["local_path"] = "";
	$new_episode["bookmark"] = 0;
	
	return $new_episode;
}

function update_feed($feed_url)
{
	$feed_xml = load_feed_xml($feed_url);
	$feed_md5 = md5($feed_url);
	$path = $GLOBALS['podcast_data_path'] . $feed_md5 . '.json';
	
	// Create an empty array of episode md5s to look through
	$episode_md5s = [];
	
	// Initialising an empty array to contain all the podcast episodes
	$episodes = [];
	$new_episodes = [];
	
	echo $path;
	if (!file_exists($path))
	{
		// Create a new empty array
		$podcast = podcast($feed_xml, $feed_url);
		
		// Add podcast to podcast.json
		$podcasts_json = load_json_data($GLOBALS['podcasts_head_file']);
		array_push($podcasts_json['podcasts'], $podcast);
		save_json_data($podcasts_json, $GLOBALS['podcasts_head_file']);
	}
	else
	{
		// Load in podcast array
		$podcast = load_json_data($path);
		$episodes = $podcast["episodes"];
		// Grab the md5s from the episodes
		foreach ($podcast["episodes"] as $episode)
		{
			array_push($episode_md5s, $episode["md5"]);
		}
	}
	
	$episode_counter = 0;
	
	foreach ($feed_xml->get_items() as $item)
	{
		$episode_counter++;
		$enclosure = $item->get_enclosure();
		$episode_md5 = md5($enclosure->get_link());
		
		if (!in_array($episode_md5, $episode_md5s))
		{
			array_push($new_episodes, episode($item));
		}
		else
		{
			break;
		}
		
		// Only grab a certain number of episodes (limit is set globally)
		if ($episode_counter > $GLOBALS['episode_limit'])
		{
			break;
		}
	}
	
	$episodes = array_merge($new_episodes, $episodes);
	
	$podcast["episodes"] = $episodes;
	
	$save_item = [];
	$save_item["episodes"] = $episodes;
	
	save_json_data($save_item, $path);
	return;
}

function episode_exists($episodes, $podcasts)
{
	
}

function podcast_exists($feed_md5)
{
	$podcasts = load_json_data($GLOBALS['podcasts_head_file']);
}

function get_feed_url($feed_md5)
{
	
}

function update_all_feeds()
{
	$podcasts_json = load_json_data($GLOBALS['podcasts_head_file']);
	
	foreach ($podcasts_json['podcast'] as $podcast)
	{
		update_feed($podcast['url']);
	}
}
?>