<?php
require '../vendor/autoload.php';

use React\Http\Server;
use React\Http\Response;
use React\EventLoop\Factory;
use Psr\Http\Message\ServerRequestInterface;

// init the event loop
$loop = Factory::create();

// set up the components
$server = new Server(function (ServerRequestInterface $request) use ($loop) {
    return new Response(200, ['Content-Type' => 'text/plain'],  "Hello world\n");

//    $video = new \React\Stream\ReadableResourceStream(fopen('bunny.mp4', 'r'), $loop);
//
//    return new Response(200, ['Content-Type' => 'video/mp4'], $video);
});

$socket = new \React\Socket\Server('127.0.0.1:8000', $loop);
$server->listen($socket);

echo 'Listening on ' . str_replace('tcp:', 'http:', $socket->getAddress()) . "\n";

// run the application
$loop->run();