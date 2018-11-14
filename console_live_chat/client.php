<?php

use React\Socket\ConnectionInterface;

require '../vendor/autoload.php';


$loop = React\EventLoop\Factory::create();
$connector = new React\Socket\Connector($loop);
$input = new React\Stream\ReadableResourceStream(STDIN, $loop);
$output = new React\Stream\WritableResourceStream(STDOUT, $loop);

$connector->connect('127.0.0.1:8080')
    ->then(function (ConnectionInterface $connection) use ($input, $output) {

//        $input->pipe($connection);
//
//
//        //$connection->on('data', function ($data) use($output){
//        //   $output->write($data);
//        //});

//        SIMPLIFIED VERSION OF ABOVE CODE
        $input->pipe($connection)->pipe($output);

    }, function (Exception $exception) {
        echo $exception->getMessage() . PHP_EOL;
    });


$loop->run();