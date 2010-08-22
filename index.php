<?php
/**
 * Created by PhpStorm.
 * User: Stefan
 * Date: 22.08.2010
 * Time: 00:28:15
 * To change this template use File | Settings | File Templates.
 */

header("Content-Type: text/html; charset=utf-8");
class Image {
	private $height;
	private $width;
	private $data;
	private $sourceUri;
	private $url;

	function __construct($srcUri) {
		if($srcUri){
			$this->setSourceUri($srcUri);
		} else {
			$this->setSourceUri('http://cdn.last.fm/flatness/catalogue/noimage/2/default_artist_mega.png');
		}
	}

	public function getHeight() {
		if ($this->height == null) {
			$this->height = @imagesy($this->data);
		}
		return $this->height;
	}

	public function getWidth() {
		if ($this->width == null) {
			$this->width = @imagesx($this->data);
		}
		return $this->width;
	}

	public function getUrl() {
		return base64_encode(imagepng($this->data));
	}

	protected function setData($data) {
		$this->data = $data;
	}

	public function getSourceUri() {
		return $this->sourceUri;
	}

	protected function setSourceUri($sourceUri) {
		$this->sourceUri = "temp/" . base64_encode( $sourceUri ) . ".png";
		if(!file_exists($this->sourceUri)) {
			$ch = curl_init($sourceUri);
			curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
			curl_setopt($ch, CURLOPT_HEADER, 0); // DO NOT RETURN HTTP HEADERS
			curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1); // RETURN THE CONTENTS OF THE CALL
			$this->setData(imagecreatefromstring(curl_exec($ch)));
			imagepng($this->data, $this->sourceUri);
			chmod ($this->sourceUri, 777);
		} else {
			$this->setData(imagecreatefrompng($this->sourceUri));
		}
	}

}


class TopArtist {
	private $name;
	private $url;
	private $playcount;
	private $rank;

	/**
	 * @var Image
	 */
	private $image;

	public function __construct() {
		// TODO: Implement __construct() method.
	}

	public function getName() {
		return $this->name;
	}

	public function setName($name) {
		$this->name = $name;
		return $this;
	}

	public function getUrl() {
		return $this->url;
	}

	public function setUrl($url) {
		$this->url = $url;
		return $this;
	}

	public function getPlaycount() {
		return $this->playcount;
	}

	public function setPlaycount($playcount) {
		$this->playcount = $playcount;
		return $this;
	}

	/**
	 * @return Image
	 */
	public function getImage() {
		return $this->image;
	}

	public function setImage($srcUri) {
		$this->image = new Image($srcUri);
		return $this;
	}

	public function getRank() {
		return $this->rank;
	}

	public function setRank($rank) {
		$this->rank = $rank;
		return $this;
	}
}


$user = (isset($_GET['user'])) ? $_GET['user'] : "LeutnantGuck";



function getData($user) {
//	$ch = curl_init("http://ws.audioscrobbler.com/2.0/user/A-G-D/topartists.xml");
	$ch = curl_init("http://ws.audioscrobbler.com/2.0/user/" . $user . "/topartists.xml");
	curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
	curl_setopt($ch, CURLOPT_HEADER, 0); // DO NOT RETURN HTTP HEADERS
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1); // RETURN THE CONTENTS OF THE CALL
	return curl_exec($ch);
}

$topArtistsXml = @simplexml_load_string(getData($user));

$topArtists = array();
if($topArtistsXml !== false){
	$counter = 0;
	foreach ($topArtistsXml as $artist) {
		if ($counter <= 100) {
			$a = new TopArtist();
			$a->setImage($artist->image[3] . '')->setName($artist->name . '')->setRank($artist['rank'] . '')->setPlaycount($artist->playcount . '')->setUrl($artist->url . '');
			array_push($topArtists, $a);
		}
		$counter++;
	}
}

?><!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN"
		"http://www.w3.org/TR/html4/loose.dtd">
<html>
<head>
	<title></title>
	<link href='http://fonts.googleapis.com/css?family=Droid+Sans:bold' rel='stylesheet' type='text/css' />
	<style type="text/css">
		html, body, ul, li {
			margin: 0;
			padding: 0;
			font-family: 'Droid Sans', arial, serif;
			background: #111;
		}

		body * {
			color: rgba(255, 255, 255, .7);
		}

		h1, p {
			padding-left: 15px;
		}

		input {
			color: #333;
		}

		li {
			float: left;
			position: relative;
			list-style: none;
			max-width:
		}

		li * {
			display: block;
			border: none;

		}

		li a {
			border: none;
			text-decoration: none;
		}
		li a:before {
			content: attr(title);
			position: absolute;
			bottom: 0;
			right: 0;
			left: 0;
			background: rgba(0, 0, 0, .3);
			display: block;
			padding: 0.32em;
			color: rgba(255, 255, 255, .7);
			overflow: hidden;
			white-space: nowrap;
		}

<?php  if(count($topArtists) != 0) : ?>


<?php  $liWidth =  $topArtists[0]->getImage()->getWidth();?>
		li {
			width: <?php echo $liWidth ?>px;
			overflow: hidden;
		}


<?php for ($i=1;$i<20;$i++) : ?>
		@media all and (min-width: <?php echo ($i)*$liWidth - $liWidth +1 ?>px) and (max-width: <?php echo ($i)*$liWidth ?>px) {
			li:nth-child(<?php echo $i-1 ?>n+1) {
				clear: both;
			};

		}
<?php endfor ?>
<?php endif ?>

	</style>
</head>
<body>
<h1><a href="http://last.fm" target="_blank">LastFm</a> User-Topartists Overall</h1>
<form action="index.php">
<p><label for="user">LastFm-Username</label> <input type="text" id="user" name="user" value="<?php echo $user ?>" /><input type="submit" value="send" /> </p>
</form>

<?php  if(count($topArtists) != 0) : ?>

<ul>
<?php foreach ($topArtists as $artist): ?>
	<li style="height:<?php echo $artist->getImage()->getHeight(); ?>px;">
		<a href="<?php echo $artist->getUrl(); ?>" title="<?php echo $artist->getRank(); ?>. <?php echo $artist->getName(); ?> (Playcount: <?php echo $artist->getPlaycount(); ?>)" target="_blank">
			<img src="<?php echo $artist->getImage()->getSourceUri(); ?>" alt="" width="<?php echo $artist->getImage()->getWidth(); ?>" height="<?php echo $artist->getImage()->getHeight(); ?>"/>
		</a>
	</li>
<?php endforeach ?>
</ul>
<?php else : ?>
<p>Please use an exist <a href="http://last.fm" target="_blank">LastFm</a>-User!</p>
<?php endif ?>
</body>
</html>