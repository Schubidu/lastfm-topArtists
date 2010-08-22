<?php
/**
 * Created by PhpStorm.
 * User: Stefan
 * Date: 22.08.2010
 * Time: 17:34:33
 * To change this template use File | Settings | File Templates.
 */
foreach(glob('temp/*') as $file){
	unlink($file);
}
if(count(glob('temp/*')) == 0){
	echo "DONE";
} else {
	echo "ERROR";
}
 ?>
