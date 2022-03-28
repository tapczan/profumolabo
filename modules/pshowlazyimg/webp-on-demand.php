<?php

/**
 * File from https://prestashow.pl
 *
 * DISCLAIMER
 * Do not edit or add to this file if you wish to upgrade this module to newer
 * versions in the future.
 *
 * @authors     PrestaShow.pl <kontakt@prestashow.pl>
 * @copyright   2021 PrestaShow.pl
 * @license     https://prestashow.pl/license
 */

$showReport = isset($_COOKIE['showReport']);

ob_start();

define('_SHOP_ROOT_DIR_', dirname(dirname(dirname(__FILE__))));

require_once dirname(__FILE__) . '/../../config/defines.inc.php';
require_once dirname(__FILE__) . '/../../config/autoload.php';
require dirname(__FILE__) . '/config.php';

use Prestashow\PShowLazyImg\Service\UrlDispatcher;
use WebPConvert\WebPConvert;

$urlDispatcherService = UrlDispatcher::getInstance();

if (isset($_SERVER['REQUEST_URI'])) {
	$_SERVER['REQUEST_URI'] = str_replace('//', '/', $_SERVER['REQUEST_URI']);
    $source = $urlDispatcherService->dispatch($_SERVER['REQUEST_URI']);
} elseif (isset($_GET['source'])) {
    list($source) = explode('?', urldecode($_GET['source']));
    if (stripos($source, _PS_ROOT_DIR_) !== false) {
        $source = str_replace(_PS_ROOT_DIR_, '', $source);
    }
} else {
    http_response_code(404);
    exit;
}

// for url like: .../public_html/20128-home_default
for ($i = 1; $i < 10; ++$i) {
    $path = '';
    for ($x = 1; $x <= $i; ++$x) {
        $path .= '$' . $x;
    }
    $source = preg_replace(
        '/^\/' . str_repeat('([0-9])', $i) . '-[_a-zA-Z0-9-]+$/',
        '/img/p' . str_replace('$', '/$', $path) . '/' . $path . '.jpg',
        $source
    );
    if ($showReport) {
        var_dump($source);
        var_dump('/img/p' . str_replace('$', '/$', $path) . '/' . $path . '.jpg');
        echo '<hr>';
    }
}

if (!file_exists($source) && stripos($source, _SHOP_ROOT_DIR_) === false) {
    $source = _SHOP_ROOT_DIR_ . $source;
}

if ($showReport) {
    var_dump(
        _SHOP_ROOT_DIR_,
        $source,
        file_exists($source)
    );
}

if (!file_exists($source)) {
    http_response_code(404);
    exit;
}

// detect google crawler (e.g. gmc) and return non-webp
if (($_REQUEST['HTTP_USER_AGENT'] ?? $_SERVER['HTTP_USER_AGENT'] ?? '') === 'google-xrawler') {
    header('Content-Type: image/' . pathinfo($source, PATHINFO_EXTENSION));
    echo readfile($source);
    exit;
}

$ext = pathinfo($source, PATHINFO_EXTENSION);
$destination = str_replace('.' . $ext, '.webp', $source);
if (file_exists($destination) && !$showReport) {
    ob_end_clean();
    $urlToImg = str_replace(_SHOP_ROOT_DIR_, '', $destination);
    $urlToImg = $urlDispatcherService->getShopBaseUri() . $urlToImg;
    $urlToImg = preg_replace('/[\/]+/', '/', $urlToImg);
    http_response_code(301);
    header('Location: ' . $urlToImg);
    exit;
}

$options = array(
    'fail' => 'original', // ('original' | 404' | 'throw' | 'report')
    'fail-when-fail-fails' => 'report', // ('original' | 404' | 'throw' | 'report')

    // options influencing the decision process of what to be served
    'reconvert' => $showReport, // if true, existing (cached) image will be discarded
    'serve-original' => false, // if true, the original image will be served rather than the converted
    'show-report' => $showReport, // if true, a report will be output rather than the raw image

    // warning handling
    'suppress-warnings' => !$showReport, // if you set to false, make sure that warnings are not echoed out!

    // options when serving an image (be it the webp or the original, if the original is smaller than the webp)
    'serve-image' => [
        'headers' => [
            'cache-control' => true,
            'content-length' => true,
            'content-type' => true,
            'expires' => false,
            'last-modified' => true,
            'vary-accept' => false
        ],
        'cache-control-header' => 'public, max-age=31536000',
    ],

    // redirect tweak
    'redirect-to-self-instead-of-serving' => true, // if true, a redirect will be issues rather than serving

    'convert' => [
        // options for converting goes here
        'quality' => 'auto',
    ]
);

if (!$showReport) {
    ob_end_clean();
}

try {
//    WebPConvert::convert($source, $destination, $options);
//    if (file_exists($destination) && !$showReport) {
//        ob_end_clean();
//        $urlToImg = str_replace([_PS_ROOT_DIR_, '//'], ['', '/'], $destination);
//        http_response_code(301);
//        header('Location: ' . $urlToImg);
//        exit;
//    }
    WebPConvert::serveConverted($source, $destination, $options);
} catch (Exception $e) {
    header('Content-Type: image/' . pathinfo($source, PATHINFO_EXTENSION));
    echo readfile($source);
    exit;
}

if ($showReport) {
    exit;
}

//header('Content-Type: image/' . pathinfo($source, PATHINFO_EXTENSION));
//echo readfile($source);
//exit;
