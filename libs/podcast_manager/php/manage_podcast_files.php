<?php
function download_episode($feed_md5, $episode_md5)
{
	log_event("Beginning download of " . $episode_md5);
	
	$path = $GLOBALS['podcast_data_path'] . $feed_md5 . '.json';
	$episodes = load_json_data($path);
	foreach ($episodes['episodes'] as &$episode)
	{
		if (strcmp($episode['md5'], $episode_md5) == 0)
		{
			log_event("Episode successfully found: " . $episode_md5);
			$raw_publish_date = strtotime($episode["publish_date"]);
			$publish_date = date('Ymd',$raw_publish_date);
			$extension = $episode["extension"];
			$filename = $publish_date . make_url_safe($episode['title']);
			$podcast_folder = make_url_safe(get_podcast_name($feed_md5));
			$local_path = $GLOBALS['podcast_file_path'] . $podcast_folder . '/' . $filename . '.' . $extension;
			
			$episode['status'] = 1;
			save_json_data($episodes, $path);
			log_event("Downloading: " . $episode_md5 . "-" . $episode['download_url']);
			$episode_file = file_get_contents($episode['download_url']);
			
			$directory = $GLOBALS['podcast_file_path'] . $podcast_folder;
			if (!is_dir($directory)) {
				// dir doesn't exist, make it
				mkdir($directory);
			}
			
			file_put_contents($local_path, $episode_file);
			log_event("Episode saved: " . $episode_md5 . "-" . $local_path);
			
			$episode['local_path'] = $podcast_folder . '/' . $filename . '.' . $extension;
			$episode['status'] = 2;
			save_json_data($episodes, $path);
			log_event("Json data updated: " . $path);
			log_event("Json data updated: " . $episode_md5);
			
			echo $episode['local_path'];
			
			return;
		}
	}
	
	log_event("No episode found for " . $episode_md5);
}

function get_podcast_name($feed_md5)
{
	$podcasts = load_json_data($GLOBALS['podcasts_head_file']);
	foreach ($podcasts['podcasts'] as $podcast)
	{
		if (strcmp($podcast['md5'], $feed_md5) == 0)
		{
			return $podcast['name'];
		}
	}
	return "";
}

function make_url_safe($string)
{
	$safe = preg_replace('/^-+|-+$/', '', strtolower(preg_replace('/[^a-zA-Z0-9]+/', '_', $string)));
	return $safe;
}

function save_time($podcast_md5, $episode_md5, $time)
{
	$path = $GLOBALS['podcast_data_path'] . $podcast_md5 . '.json';
	$episodes = load_json_data($path);
	foreach ($episodes['episodes'] as &$episode)
	{
		if (strcmp($episode['md5'], $episode_md5) == 0)
		{
			$episode['status'] = 3;
			$episode['bookmark'] = $time;
			save_json_data($episodes, $path);
		}
	}
}
?>