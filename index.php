<?php
/**
 * Created by PhpStorm.
 * User: Stefan
 * Date: 22.08.2010
 * Time: 00:28:15
 * To change this template use File | Settings | File Templates.
 */

//header("Content-Type: text/plain; charset=utf-8");
class Image {
	private $height;
	private $width;
	private $data;

	function __construct() {
		// TODO: Implement __construct() method.
	}

}


class TopArtist {
	private $name;
	private $url;
	private $playcount;

	/**
	 * @var Image
	 */
	private $image;
}



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
	array_push($topArtists, array(
		"url"=>$artist->url,
		"name"=>$artist->name,
		"playcount"=>$artist->url,
		"url"=>$artist->url,
	));
	echo "<li>";
	echo '<a href="' . $artist->url . '" title="' . $artist->name . ' (' . $artist->playcount . ')"><img src="' . $artist->image[3] . '" alt="" /></a>';
/*	echo $artist->name."\n";
	echo $artist->playcount."\n";
	echo $artist->url."\n";
	echo $artist->image[3]."\n";
*/	echo "</li>";
	//print_r($artist);
}

 ?><!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN"
		"http://www.w3.org/TR/html4/loose.dtd">
<html>
<head>
	<title></title>
</head>
<body>
<ul>

</ul>
</body>
</html>