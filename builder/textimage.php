<?php

$width = getenv('TEXTIMAGE_WIDTH') ? getenv('TEXTIMAGE_WIDTH') : 100;
$font_aspect = 2.0;

// line
for($i=0; $i<$width; $i++){ echo '-'; }
echo "\n";

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
$file = '/tmp/target.img';
file_put_contents($file, $data);
list($img_width, $img_height, $mime_type, $attr) = getimagesize($file);

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
	if(@$url) { echo $url; }
	exit;
}


global $map;
global $map_cache;
$map = getColorMap();


$map_cache = array();

// var_dump($image);

#imagejpeg($image, '00.jpg');
// imagetruecolortopalette($image, true, 255);
#imagejpeg($image, '01.jpg');

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
		$true_color = imagecolorat(
			$image,
			(($img_width / ($x-1)) * $step_x ) - ($step_x==$x-1?1:0), # <- なぜかMAX値の時だけ正常に取れないので-1px
			(($img_height / ($y-1)) * $step_y ) - ($step_y==$y-1?1:0) # <-
		);
		$index_color = toIndexColor($true_color);
// printf('(%d), ', $true_color);
// printf('(%s), ', str_pad(dechex($true_color), 6, '0', STR_PAD_LEFT));
// printf('(%d), ', $index_color);
		echo "\e[38;05;${index_color}m ";
	}
	echo "\n";
}

echo "\e[m";
// line
for($i=0; $i<$width; $i++){ echo '-'; }
echo "\n";
echo "finish\n";

function toIndexColor($true_color){
	global $map;
	global $map_cache;

	// cache
	if(isset($map_cache[$true_color])) { return $map_cache[$true_color]; }

	// search near color
	$min_diff = 256*3; # MAX value
	$near_index = 0;
	for($i=0; $i<count($map); $i++){
		if($map[$i]==='') { next; }
		// RGB
// echo dechex($true_color) .','.$map[$i];

		if(!preg_match('/^([0-9a-f]{2})([0-9a-f]{2})([0-9a-f]{2})$/i', str_pad(dechex($true_color), 6, '0', STR_PAD_LEFT), $pixel_rgb)) {
echo dechex($true_color) .','.$map[$i];
exit;
		}
		if(!preg_match('/^([0-9a-f]{2})([0-9a-f]{2})([0-9a-f]{2})$/i', str_pad($map[$i], 6, '0', STR_PAD_LEFT), $map_rgb)) {
echo dechex($true_color) .','.$map[$i];
exit;
		}
		$diff = (
			abs(hexdec($pixel_rgb[1])-hexdec($map_rgb[1]))+ # 175
			abs(hexdec($pixel_rgb[2])-hexdec($map_rgb[2]))+ # 182
			abs(hexdec($pixel_rgb[3])-hexdec($map_rgb[3]))  # 188
		);

		// $diff = abs($true_color-$map[$i]);
		if( $min_diff>$diff ){ $min_diff = $diff; $near_index = $i; }

// echo ($pixel_rgb[0] . ':' . hexdec($pixel_rgb[0]) . "\n");

#echo "$min_diff>$diff\n";
	}
	$map_cache[$true_color] = $near_index;

// var_dump(array(
// 	$true_color,
// 	$near_index
// )); exit;


	return $near_index;
	// return ($true_color % 256);
}

function getColorMap(){
	$map = array(
		# 0-15
		'4a4948', 'c05350', '729533', 'ba8a33', '4b8cba', 'ba5586', '4d9994', 'cbc7bb', '73716c', 'e95c59', '8cbe33', 'e3a533', '52a6e3', 'e3609e', '58c2ba', 'f4efdf',
		# 16-51
		'000000', '00005f', '000087', '0000af', '0000d7', '0000ff', '005f00', '005f5f', '005f87', '005faf', '005fd7', '005fff', '008700', '00875f', '008787', '0087af', '0087d7', '0087ff', '00af00', '00af5f', '00af87', '00afaf', '00afd7', '00afff', '00d700', '00d75f', '00d787', '00d7af', '00d7d7', '00d7ff', '00ff00', '00ff5f', '00ff87', '00ffaf', '00ffd7', '00ffff',
		# 52-87
		'5f0000', '5f005f', '5f0087', '5f00af', '5f00d7', '5f00ff', '5f5f00', '5f5f5f', '5f5f87', '5f5faf', '5f5fd7', '5f5fff', '5f8700', '5f875f', '5f8787', '5f87af', '5f87d7', '5f87ff', '5faf00', '5faf5f', '5faf87', '5fafaf', '5fafd7', '5fafff', '5fd700', '5fd75f', '5fd787', '5fd7af', '5fd7d7', '5fd7ff', '5fff00', '5fff5f', '5fff87', '5fffaf', '5fffd7', '5fffff',
		# 88-123
		'870000', '87005f', '870087', '8700af', '8700d7', '8700ff', '875f00', '875f5f', '875f87', '875faf', '875fd7', '875fff', '878700', '87875f', '878787', '8787af', '8787d7', '8787ff', '87af00', '87af5f', '87af87', '87afaf', '87afd7', '87afff', '87d700', '87d75f', '87d787', '87d7af', '87d7d7', '87d7ff', '87ff00', '87ff5f', '87ff87', '87ffaf', '87ffd7', '87ffff',
		# 124-159
		'af0000', 'af005f', 'af0087', 'af00af', 'af00d7', 'af00ff', 'af5f00', 'af5f5f', 'af5f87', 'af5faf', 'af5fd7', 'af5fff', 'af8700', 'af875f', 'af8787', 'af87af', 'af87d7', 'af87ff', 'afaf00', 'afaf5f', 'afaf87', 'afafaf', 'afafd7', 'afafff', 'afd700', 'afd75f', 'afd787', 'afd7af', 'afd7d7', 'afd7ff', 'afff00', 'afff5f', 'afff87', 'afffaf', 'afffd7', 'afffff',
		# 160-195
		'd70000', 'd7005f', 'd70087', 'd700af', 'd700d7', 'd700ff', 'd75f00', 'd75f5f', 'd75f87', 'd75faf', 'd75fd7', 'd75fff', 'd78700', 'd7875f', 'd78787', 'd787af', 'd787d7', 'd787ff', 'd7af00', 'd7af5f', 'd7af87', 'd7afaf', 'd7afd7', 'd7afff', 'd7d700', 'd7d75f', 'd7d787', 'd7d7af', 'd7d7d7', 'd7d7ff', 'd7ff00', 'd7ff5f', 'd7ff87', 'd7ffaf', 'd7ffd7', 'd7ffff',
		# 196-231
		'ff0000', 'ff005f', 'ff0087', 'ff00af', 'ff00d7', 'ff00ff', 'ff5f00', 'ff5f5f', 'ff5f87', 'ff5faf', 'ff5fd7', 'ff5fff', 'ff8700', 'ff875f', 'ff8787', 'ff87af', 'ff87d7', 'ff87ff', 'ffaf00', 'ffaf5f', 'ffaf87', 'ffafaf', 'ffafd7', 'ffafff', 'ffd700', 'ffd75f', 'ffd787', 'ffd7af', 'ffd7d7', 'ffd7ff', 'ffff00', 'ffff5f', 'ffff87', 'ffffaf', 'ffffd7', 'ffffff',
		# 232-255
		'080808', '121212', '1c1c1c', '262626', '303030', '3a3a3a', '444444', '4e4e4e', '585858', '626262', '6c6c6c', '767676', '808080', '8a8a8a', '949494', '9e9e9e', 'a8a8a8', 'b2b2b2', 'bcbcbc', 'c6c6c6', 'd0d0d0', 'dadada', 'e4e4e4', 'eeeeee'
	);
	// foreach($map as &$value) {
	// 	$value = hexdec($value);
	// }
	return $map;
}
