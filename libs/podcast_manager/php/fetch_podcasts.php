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
	$description = $item->get_content();
	$description = strip_tags($description);
	$description = html_entity_decode($description);
	$new_episode["description"] = $description;
	
	return $new_episode;
}

function update_feed($feed_url)
{
	if (!is_valid_feed($feed_url))
	{
		echo false;
		return;
	}
	
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

// Checks if the feed is a valid podcast feed
function is_valid_feed($feed_url)
{
	// Load in the document as a simplepie object
	$feed_xml = load_feed_xml($feed_url);
	
	// If the feed has any errors, return false
	if ($feed_xml->error())
	{
		return false;
	}
	
	// Get the number of feed items in the feed
	$episode_count = $feed_xml->get_item_quantity();
	
	// Get the first feed item
	$episode = $feed_xml->get_item();

	// See if the first feed item has any media to download
	$enclosure = $episode->get_enclosure();
	$download_url = $enclosure->get_link();
	$total_time = $enclosure->get_duration();
	
	// Set the validity flags
	$valid_episode_count = $episode_count > 0;
	$valid_download_url = strlen($download_url) > 0;
	$valid_total_time = $total_time > 0;
	
	// All flags must be true to be a valid podcast feed
	if ($valid_episode_count && $valid_download_url && $valid_total_time)
	{
		return true;
	}
	else
	{
		return false;
	}
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