<?php
// http://feed.thisamericanlife.org/talpodcast?format=xml
// http://feeds.wnyc.org/radiolab
// http://resources.h9blog.com/podcast-feeds/h9cast

ini_set('display_errors',1);
ini_set('display_startup_errors',1);
error_reporting(-1);

// Allows better downloading
ini_set("memory_limit","256M");

$root_path = getcwd() . '/';
$podcast_file_path = $root_path . 'podcasts/podcast_files/';
$podcast_data_path = $root_path . 'podcasts/podcast_data/';
$podcasts_head_file = $root_path . 'podcasts/podcasts.json';
$episode_limit = 30;

// Loading in simplepie
include_once($root_path . '/libs/simplepie/autoloader.php');

// Loading in the podcast manager libraries.
include_once($root_path . '/libs/podcast_manager/php/fetch_podcasts.php');
include_once($root_path . '/libs/podcast_manager/php/manage_podcast_files.php');

if(isset($_POST['action']) && !empty($_POST['action'])) {
    $action = $_POST['action'];
    switch($action) {
		case 'download_episode' : download_episode($_POST['podcast_md5'], $_POST['episode_md5']);break;
		case 'update_feed' : update_feed($_POST['feed_url']);break;
		case 'update_all_feeds' : update_all_feeds();break;
		case 'save_time' : save_time($_POST['podcast_md5'], $_POST['episode_md5'], $_POST['time']);break;
	}
}
		
// Loads in a simplepie object to parse RSS feeds
function load_feed_xml($url)
{
	$feed = new SimplePie();
	$feed->set_feed_url($url);
	$feed->init();
	$feed->handle_content_type();
	$feed->set_cache_location($GLOBALS['root_path'] . '/cache');
	
	return $feed;
}

// Loads in the JSON data
function load_json_data($local_path)
{
	$json_data = json_decode(file_get_contents($local_path), true);
	return $json_data;
}

// Saves the data as a JSON file
function save_json_data($json_data, $path)
{
	file_put_contents($path, json_encode($json_data));
}
?>