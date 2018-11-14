<?php
require '../vendor/autoload.php';
$loop = \React\EventLoop\Factory::create();

//-- Create writable stream
//-- STDIN - standard input
$writable = new \React\Stream\WritableResourceStream(STDOUT, $loop);
$readable = new \React\Stream\ReadableResourceStream(STDIN, $loop);
$toUpper = new \React\Stream\ThroughStream(function ($chunk){
    return strtoupper($chunk);
});

//$readable->on('data', function ($chunk) use($writable){
//    $writable->write($chunk);
//
//});

//-- The same
$readable->pipe($toUpper)->pipe($writable);

$loop->run();