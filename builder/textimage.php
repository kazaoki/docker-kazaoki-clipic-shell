<?php

$width = 100;
$font_aspect = 2.0;

echo "PHP run ok\n";
echo "------------------------------------------------------------\n";
$data = '';
while( !feof(STDIN) ) {
	$data .= fgets(STDIN);
}
if(preg_match('/^https?\:\/\//', $data)) {
	$url = trim($data);
	$data = file_get_contents($url);
}

// echo $data;
// var_dump(gd_info());
$file = '/opt/target.img';
file_put_contents($file, $data);
list($img_width, $img_height, $mime_type, $attr) = getimagesize('/opt/target.img');

$x = $width;
$y = Floor((($img_height/$img_width)*$width)/$font_aspect);

// var_dump(array($img_width, $img_height, $mime_type, $attr));
switch($mime_type){
	case IMAGETYPE_GIF:
		$image = @imagecreatefromgif($file);
		break;
	case IMAGETYPE_JPEG:
		$image = @imagecreatefromjpeg($file);
		break;
	case IMAGETYPE_PNG:
		$image = @imagecreatefrompng($file);
		break;
	case IMAGETYPE_WBMP:
		$image = @imagecreatefromwbmp($file);
		break;
	case IMAGETYPE_XBM:
		$image = @imagecreatefromxbm($file);
		break;
}

if(!$image) {
	echo "can't load image file.\n";
	if($url) { echo $url; }
	exit;
}

// var_dump($image);
imagetruecolortopalette($image, true, 255);
#$p=imagecolorat($image, 759, 759);
#echo "\e[38;07;${p}m";
#var_dump($p);
#echo "\e[m";

echo "\e[38;07m";

for($step_y=0; $step_y<$y; $step_y++){
	for($step_x=0; $step_x<$x; $step_x++){
		// printf('(%dx%d), ',
		// 	($img_width / ($x-1)) * $step_x,
		// 	($img_height / ($y-1)) * $step_y
		// );
		$color = imagecolorat(
			$image,
			(($img_width / ($x-1)) * $step_x ) - ($step_x==$x-1?1:0), # <- なぜかMAX値の時だけ正常に取れないので-1
			(($img_height / ($y-1)) * $step_y ) - ($step_y==$y-1?1:0) # <-
		);
		// printf('(%d), ', $color) ;
		echo "\e[38;05;${color}m ";
	}
	echo "\n";
}

echo "\e[m";
echo "------------------------------------------------------------\n";
echo "finish\n";
