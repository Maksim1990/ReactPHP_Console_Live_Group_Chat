<?php
require 'vendor/autoload.php';

$loop=\React\EventLoop\Factory::create();



$timer=$loop->addPeriodicTimer(1,function () use(&$counter, &$timer,$loop){
    $counter++;

    if($counter==5){
        $loop->cancelTimer($timer);
    }
    echo "Repeat timeout $counter\n";
});


$loop->addTimer(1,function (){
    sleep(5);
});



$loop->run();
