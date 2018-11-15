<?php

require_once __DIR__ . '/../vendor/autoload.php';

class UdpChatServer
{
    protected $clients = [];

    /**
     * @var \React\Datagram\Socket
     */
    protected $socket;

    public function process($data, $address)
    {
        $data = json_decode($data, true);

        if ($data['type'] == 'enter') {
            $this->addClient($data['name'], $address);
            return;
        }

        if ($data['type'] == 'leave') {
            $this->removeClient($address);
            return;
        }

        $this->sendMessage($data['message'], $address);
    }

    protected function addClient($name, $address)
    {
        if (array_key_exists($address, $this->clients)) return;

        $this->clients[$address] = $name;

        $this->broadcast(
            $this->getColoredMessage("0;32", "$name enters chat"),
            $address
        );
    }

    protected function removeClient($address)
    {
        $name = $this->clients[$address] ?? '';

        unset($this->clients[$address]);

        $this->broadcast($this->getColoredMessage("1;31", "$name leaves chat"));
    }

    protected function broadcast($message, $except = null)
    {
        foreach ($this->clients as $address => $name) {
            if ($address == $except) continue;

            $this->socket->send($message, $address);
        }
    }

    protected function sendMessage($message, $address)
    {
        $name = $this->clients[$address] ?? '';

        $this->broadcast(
            $this->getColoredMessage("0;36", "$name:") . " $message", $address
        );
    }

    private function getColoredMessage($hexColor, $message)
    {
        return "\033[{$hexColor}m{$message}\033[0m";
    }

    public function run()
    {
        $loop = React\EventLoop\Factory::create();
        $factory = new React\Datagram\Factory($loop);
        $address = '127.0.0.1:1234';

        $factory->createServer($address)
            ->then(
                function (React\Datagram\Socket $server) {
                    $this->socket = $server;
                    $server->on('message', [$this, 'process']);
                },
                function (Exception $error) {
                    echo "ERROR: {$error->getMessage()}\n";
                }
            );

        echo "Listening on $address\n";
        $loop->run();
    }
}

(new UdpChatServer())->run();

