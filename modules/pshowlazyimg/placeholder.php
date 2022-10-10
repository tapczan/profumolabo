<?php

declare(strict_types=1);

define('PLACEHOLDER_BG_COLOR', [255, 255, 255]);

define('PLACEHOLDER_FILE_EXT', 'png');

define('PLACEHOLDER_DIR', dirname(__FILE__) . '/../../img/placeholder/');
@mkdir(PLACEHOLDER_DIR, 0755, true);
if (!is_dir(PLACEHOLDER_DIR)) {
    http_response_code(404);
    exit;
}

$width = (isset($_GET['w']) && is_numeric($_GET['w'])) ? (int)$_GET['w'] : 100;
$height = (isset($_GET['h']) && is_numeric($_GET['h'])) ? (int)$_GET['h'] : 100;

define('PLACEHOLDER_PATH', sprintf(
    '%s%dx%d.%s',
    PLACEHOLDER_DIR,
    $width,
    $height,
    PLACEHOLDER_FILE_EXT
));

if (!file_exists(PLACEHOLDER_PATH)) {
    $margin = 0;
    $im = imagecreatetruecolor($width, $height);

    $white = imagecolorallocate($im, PLACEHOLDER_BG_COLOR[0], PLACEHOLDER_BG_COLOR[1], PLACEHOLDER_BG_COLOR[2]);
    imagefilledrectangle($im, $margin, $margin, $width - ($margin * 2), $height - ($margin * 2), $white);
//    $color = imagecolorallocatealpha($im, 0, 0, 0, 127);
//    imagefill($im, 0, 0, $color);
    
    $success = imagepng($im, PLACEHOLDER_PATH);
    if (!$success) { // if failed to write image to file
        header("Content-type: image/" . PLACEHOLDER_FILE_EXT);
        imagepng($im);
        imagedestroy($im);
        exit;
    } else {
        imagedestroy($im);
    }
}

http_response_code(301);
header(sprintf('Location: ../../img/placeholder/%dx%d.%s', $width, $height, PLACEHOLDER_FILE_EXT));
exit;