<?php

date_default_timezone_set("America/New_York");

function curlUsingGet($url) {
	if(empty($url))
		return 'Error: invalid Url';
	$ch = curl_init();
	curl_setopt($ch, CURLOPT_HEADER, 0);
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1); //Set curl to return the data instead of printing it to the browser.
	curl_setopt($ch, CURLOPT_CONNECTTIMEOUT,10); //timeout after 10 seconds, you can increase it
	curl_setopt($ch, CURLOPT_USERAGENT , "Mozilla/4.0 (compatible; MSIE 8.0; Windows NT 6.1)");
	curl_setopt($ch, CURLOPT_URL, $url ); //set the url and get string together
	$return = curl_exec($ch);
	curl_close($ch);
	return $return;
}

function getJSONdata() {
	if(!isset($_GET['example']) || (isset($_GET['example']) && $_GET['example'] == 1))
		$url = "https://rumobile.rutgers.edu/1/rutgers-dining.txt";
	elseif($_GET['example'] == 2) // Sample  with no Mac and Cheese
		$url = "http://hackru.kaiserdesigns.com/2.txt";
	elseif($_GET['example'] == 3) // Sample with all Mac and Cheese
		$url = "http://hackru.kaiserdesigns.com/3.txt";
	return curlUsingGet($url);
}

function findCheesyArray($foodJSON) {
	$macAndCheese = ["Mac and Cheese", "Mac & Cheese", "Macaroni and Cheese", "Macaroni & Cheese", "Baked Mac and Cheese", "Baked Mac & Cheese", "Baked Macaroni and Cheese", "Baked Macaroni & Cheese"];
	$foodData = json_decode($foodJSON);
	$found = array('date' => 0, 'count' => 0, 'matches' => array());
	foreach($foodData as $i => $dh) {
		$found['date'] = $dh->date;
		foreach($dh->meals as $i => $meal) {
			foreach($meal->genres as $i => $genre) {
				foreach($genre->items as $i => $item) {
					foreach($macAndCheese as $i => $mac) {
						similar_text($mac, $item, $percent);
						if($percent > 90) {
							$found_item = array('dining_hall' => $dh->location_name, 'meal_name' => $meal->meal_name, 'genre' => $genre->genre_name, 'type' => $item);
							if(!$found['matches'][$dh->location_name])
								$found['matches'][$dh->location_name] = array();
							array_push($found['matches'][$dh->location_name], $found_item);
							$found['count']++;
							break;
						}
					}
				}
			}
		}
	}
	return $found;
}

function printMacAndCheese($found) {
	if($found['count'] > 0)
		echo $found['count'] . " Mac & Cheese found!<br /><ul style=\"padding:0px;\">";
	else
		echo "<li style=\"list-style-type: none;\">No Mac and Cheese today :(</li>";
	foreach($found['matches'] as $i => $dh) {
		echo "<li class=\"dhall\">
				<div class=\"header\">" . $i . "</div>
				<table cellpadding=\"0\" cellspacing=\"0\" class=\"macs\">";
		foreach($dh as $i => $mac) {
			echo "<tr class=\"mac\"><td class=\"meal\">" . $mac['meal_name'] . "</td><td class=\"type\">" . $mac['type'] . "</td></tr>";
		}
		echo "</table></li>";
	}
	echo "</ul>";
}

function getCountdownTo() {
	$nowWeekDay = date("w");
	$nowHour = date("H");
	if($nowWeekDay == 1 && $nowHour < 17) {
		$countdownto = mktime(17, 0, 0);
	}
	else if($nowWeekDay == 1 && $nowHour >= 17) {
		$countdownto = -1;
	}
	else {
		$countdownto = strtotime("next monday +17 hours");
	}
	return $countdownto;
}

function getCountdown($countdownto) {
	$countdown = array();
	if($countdownto > 0) {
		$diff = $countdownto - time();
		$countdown['running'] = "yes";
		$countdown['days'] = floor($diff / (60 * 60 * 24));
		$countdown['hours'] = floor( ($diff - ($countdown['days'] * 24 * 60 * 60)) / (60 * 60));
		$countdown['minutes'] = floor( ($diff - ($countdown['days'] * 24 * 60 * 60) - ($countdown['hours'] * 60 * 60) ) / 60 );
		$countdown['seconds'] = $diff - ($countdown['days'] * 24 * 60 * 60) - ($countdown['hours'] * 60 * 60) - ($countdown['minutes'] * 60);
	}
	else {
		$countdown['running'] = "no";
		$countdown['message'] = "THERE IS MAC AND CHEESE RIGHT NOW!";
	}
	return $countdown;
}

?>