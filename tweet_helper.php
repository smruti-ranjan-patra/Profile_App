<?php
	require_once('config/database.php');
	require_once('class/DatabaseConnection.php');
	require_once('TwitterAPIExchange.php');

	$obj = DatabaseConnection::create_connection($db['master']);
	$id = $_POST['id'];
	$num_of_tweets = $_POST['num_tweets'];
	$query = 'SELECT twitter_name FROM employee WHERE id = ' . $id;
	$result = DatabaseConnection::db_query($query);
	$row = DatabaseConnection::db_fetch_array($result);
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
		echo "<h3>Sorry, there was a problem.</h3><p>Twitter returned the following error message:</p><p><em>".$string[errors][0]["message"]."</em></p>";
		exit();
	}

	$tweet_display = '';

	for($i=0; $i<$num_of_tweets; $i++)
	{
		if($string[$i]['text'] != '')
		{
			$tweet_display .= '<p>' . $string[$i]['text'] . '</p><hr>';
		}
	}

	echo $tweet_display;

?>