<?php

require '../vendor/autoload.php';
$mySpider = new \Tspider\Spider([
    'startUrl' => [
        'http://www.csdn.net/'
    ],
    'itemObj' => new \TspiderTest\MyItem()
]);

$mySpider->run();