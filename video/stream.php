<?php

require '../vendor/autoload.php';

use React\Http\Server;
use React\Http\Response;
use Psr\Http\Message\ServerRequestInterface;

$server = new Server(function (ServerRequestInterface $request) use ($loop) {
    $video = new \React\Stream\ReadableResourceStream(fopen('bunny.mp4', 'r'), $loop);

    return new Response(200, ['Content-Type' => 'video/mp4'], $video);
});