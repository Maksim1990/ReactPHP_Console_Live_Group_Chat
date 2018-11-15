<?php

use React\Socket\ConnectionInterface;

/**
 * Created by PhpStorm.
 * User: narus
 * Date: 15.11.2018
 * Time: 20:35
 */
class ConnectionsPool
{
    private $connections;

    /**
     * ConnectionsPool constructor.
     */
    public function __construct()
    {
        $this->connections = new SplObjectStorage();
    }

    public function add(ConnectionInterface $connection)
    {

        $connection->write("Welcome to Console Chat\n");
        $connection->write("Enter your name\n");
        $this->setConnectionName($connection, '');

        $this->initEvent($connection);
    }

    private function getNewUser(ConnectionInterface $connection, $data)
    {
        $name = str_replace(["\r", "\n"], '', $data);
        $this->setConnectionName($connection, $name);
        $this->sendAll($connection, "User $name joined the chat\n");

    }

    private function initEvent(ConnectionInterface $connection){

        $connection->on('data', function ($data) use ($connection) {
            $name = $this->getConnectionName($connection);

            if (empty($name)) {
                $this->getNewUser($connection, $data);
                return;
            }

            $this->sendAll($connection, $name . ": " . $data);

        });

        $connection->on('close', function () use ($connection) {
            $name = $this->getConnectionName($connection);
            $this->connections->offsetUnset($connection);

            $this->sendAll($connection, "Users  $name left the chat\n");

        });
    }
    private function getConnectionName(ConnectionInterface $connection)
    {
        return $this->connections->offsetGet($connection);
    }

    private function setConnectionName(ConnectionInterface $connection, $name)
    {
        $this->connections->offsetSet($connection, $name);
    }

    private function sendAll($connection, $message)
    {
        foreach ($this->connections as $conn) {
            if ($conn != $connection) {
                $conn->write($message);
            }
        }

    }

}