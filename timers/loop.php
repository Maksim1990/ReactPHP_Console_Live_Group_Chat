<?php
require 'vendor/autoload.php';

$loop=\React\EventLoop\Factory::create();

//-- Similar as SetTimeout() as in JS

$loop->addTimer(1,function (){
    echo "After timeout \n";
});

$loop->addTimer(3,function (){
    echo "After timeout 3 \n";
});

echo "Before timeout \n";

$loop->run();
