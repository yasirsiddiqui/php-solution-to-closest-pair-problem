<?php
require_once 'ClosestPair.php';

// Create points array and fill x and y axis randomly
$points = array();
for($i=0;$i<=25;$i++) {
	// Make sure range of rand function is less then image width otherwise points will not be drawn
	$xaxis = rand(5, 490);
	// Make sure range of rand function is less then image height otherwise points will not be drawn
	$yaxis = rand(5, 490);
	$points[] = array('x' => $xaxis, 'y' => $yaxis);
}
// Create class object
$obj = new ClosestPair();

// create a blank image
$image = imagecreatetruecolor(500, 500);

// choose a color for the ellipse
$col_ellipse = imagecolorallocate($image, 255, 255, 255);

// draw the white ellipse
foreach ($points as $p) {
	// Draw point on image
	imagefilledellipse($image, $p['x'], $p['y'], 2, 2, $col_ellipse);
}
// Calculate closest pair using divide and conquer approcah
$results =  $obj->findClosestPairUsingDivideAndConquer($points);

$col_line = imagecolorallocate($image, 255, 0, 0);
// Draw line between two closest points
imageline($image, $results[1]['x'], $results[1]['y'], $results[2]['x'], $results[2]['y'], $col_line);

header("Content-type: image/png");
imagepng($image);