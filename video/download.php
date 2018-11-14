<?php
require '../vendor/autoload.php';

$loop = React\EventLoop\Factory::create();
$client = new React\HttpClient\Client($loop);
$file = new \React\Stream\WritableResourceStream(fopen('sample_name.mp4', 'w'), $loop);

$request = $client->request('GET', 'https://www.sample-videos.com/video123/mp4/720/big_buck_bunny_720p_2mb.mp4');

$request->on('response', function (\React\HttpClient\Response $response) use ($file) {
    $size = $response->getHeaders()['Content-Length'];
    $currentSize = 0;

    $progress = new \React\Stream\ThroughStream();
    $progress->on('data', function($data) use ($size, &$currentSize){
        $currentSize += strlen($data);
        echo "\033[1A", "Downloading: ", number_format($currentSize / $size * 100), "%".PHP_EOL;
    });

    $response->pipe($progress)->pipe($file);
});

$request->end();
$loop->run();

echo "\n";