<?php
/**
 * Created by PhpStorm.
 * User: Stefan
 * Date: 22.08.2010
 * Time: 00:28:15
 * To change this template use File | Settings | File Templates.
 */

header("Content-Type: text/plain; charset=utf-8");
function getData(){
	$ch = curl_init("http://ws.audioscrobbler.com/2.0/user/LeutnantGuck/topartists.xml");
	curl_setopt($ch, CURLOPT_FOLLOWLOCATION,1);
	curl_setopt($ch, CURLOPT_HEADER ,0);  // DO NOT RETURN HTTP HEADERS
	curl_setopt($ch, CURLOPT_RETURNTRANSFER,1);  // RETURN THE CONTENTS OF THE CALL
	return curl_exec($ch);
}

$topArtistsXml = simplexml_load_string(getData());
$topArtists = array();

foreach($topArtistsXml as $artist){
	echo $artist->name."\n";
	echo $artist->playcount."\n";
	echo $artist->url."\n";
	echo $artist->image[3]."\n";
	print_r($artist);
}

 ?>
