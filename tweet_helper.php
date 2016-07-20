<?php

	require_once('config/database.php');
	require_once('class/DatabaseConnection.php');
	require_once('TwitterAPIExchange.php');
// 	ini_set('display_errors', 1);
// ini_set('display_startup_errors', 1);
// error_reporting(E_ALL);

	$obj = DatabaseConnection::create_connection($db['master']);
	$id = $_POST['id'];
	$num_of_tweets = $_POST['num_tweets'];
	// $id = 13;
	// $num_of_tweets = 2;

	$query = 'SELECT twitter_name FROM employee WHERE id = ' . $id;
	$result_query = DatabaseConnection::db_query($query);
	$row = DatabaseConnection::db_fetch_array($result_query);
	$twitter_name = $row['twitter_name'];

	$settings = array(
		'oauth_access_token' => '755592390543482880-lSbmjPI8ZxxoWNBuH9kFISWkjE6zMFO',
		'oauth_access_token_secret' => 'P8xhwiVpwNvTso2GkmRnyOLHBEqgg2lmZctxiDLKdZxEX',
		'consumer_key' => 'cqpLUGFTLnUI8IqastrAuMA8H',
		'consumer_secret' => '6auOmkIxi43kCu8yUSeQlxXxVVrtuPyIBuQoBjsIgofLYELero'
	);
	$url = "https://api.twitter.com/1.1/statuses/user_timeline.json";

	$requestMethod = "GET";

	$getfield = '?screen_name=' . $twitter_name . '&count=' . $num_of_tweets;

	$twitter = new TwitterAPIExchange($settings);

	$string = json_decode($twitter->setGetfield($getfield)
																->buildOauth($url, $requestMethod)
																->performRequest(),$assoc = TRUE);
	if($string["errors"][0]["message"] != "")
	{
		// echo "<h3>Sorry, there was a problem.</h3><p>Twitter returned the following error message:</p><p><em>".$string[errors][0]["message"]."</em></p>";
		echo '{"err_msg" : "Error occured", "err_val" : "1"}';
		exit();
	}

	$tweet_display = '';
	$image = '';
	$user_name = '';

	if($string[0]['text'] != '')
	{
		$image = str_replace("normal","400x400",$string[0]['user']['profile_image_url']);
		$user_name = $string[0]['user']['name'];
		// $tweet_display = '<div style="text-align: center"><img src="' . $image . '" style="border-radius:20%;width:100px;height:100px;"></div><hr>';
	}
	

	for($i=0; $i<$num_of_tweets; $i++)
	{
		if($string[$i]['text'] != '')
		{
			$result[$i] = $string[$i]['text'];
			// $tweet_display .= '<p>' . $string[$i]['text'] . '</p><hr>';
		}
	}
	// $tweet_display = preg_replace('/<hr>$/', '', $tweet_display);

	// echo $tweet_display;

	$tweet_data = array("tweet_results" => $result, "image" => $image, "user_name" => $user_name);
	echo json_encode($tweet_data, true)

?>