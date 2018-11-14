<?php
require 'vendor/autoload.php';

$loop=\React\EventLoop\Factory::create();


//-- Similar as setInterval as in JS

$counter=0;
$timer=$loop->addPeriodicTimer(1,function () use(&$counter, &$timer,$loop){
    $counter++;

    if($counter==5){
        $loop->cancelTimer($timer);
    }
    echo "Repeat timeout \n";
});



echo "Before timeout \n";

$loop->run();