<?php
require '../vendor/autoload.php';

$user="name";

function http($url, $method)
{
    $response = [
        'data'=>'Data',
        'urlContent'=>file_get_contents($url),
        'url'=>$url,
        'method'=>$method,
    ];

    $deffered = new \React\Promise\Deferred();

    if ($response) {
        $deffered->resolve($response);
    } else {
        $deffered->reject(new Exception("Error"));
    }

    return $deffered->promise();
}

http('http://google.com', "GET",$user)
    ->then(function ($response){
        //throw new Exception("Error here");
        $response['data']=strtoupper($response['data']);
        //var_dump($response['urlContent']);
        return $response;
    })
    ->then(function ($response)use(&$user) {

        $user=strtoupper($user);
        echo $response['data'] . PHP_EOL;
    })

    ->otherwise(function (Exception $exception) {
    echo $exception->getMessage() . PHP_EOL;
});

echo $user."\n";