<?php
require_once('lib/ImageResize.php');

class ImageHandler {	
	function createCompressedProductPreview($src, $dest) {
		$image = new \Gumlet\ImageResize($src);
		$image->resize(360, 360);
		$image->quality_jpg = 85;
		$image->save($dest, IMAGETYPE_JPEG);
	}
}
?>