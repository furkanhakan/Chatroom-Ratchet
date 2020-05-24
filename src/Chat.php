<?php

namespace MyApp;

use Ratchet\MessageComponentInterface;
use Ratchet\ConnectionInterface;

require_once __DIR__."\..\db\chatrooms.php";
require_once __DIR__."\..\db\users.php";
class Chat implements MessageComponentInterface
{
    protected $clients;
    protected $users;

    public function __construct()
    {
        $this->clients = new \SplObjectStorage;
        echo "Server Started.";
        date_default_timezone_set('Europe/Istanbul');
    }

    public function onOpen(ConnectionInterface $conn)
    {
        // Store the new connection to send messages to later
        $this->clients->attach($conn);
        
        echo "New connection! ({$conn->resourceId})\n";
    }

    public function onMessage(ConnectionInterface $from, $msg)
    {
        $data = json_decode($msg, true);
        if ($data['type'] == "message") {
            $numRecv = count($this->clients) - 1;
            echo sprintf(
                'Connection %d sending message "%s" to %d other connection%s' . "\n",
                $from->resourceId,
                $msg,
                $numRecv,
                $numRecv == 1 ? '' : 's'
            );
            $objChatroom = new \chatrooms;
            $objChatroom->setUserId($data['userId']);
            $objChatroom->setMsg($data['msg']);
            $objChatroom->setCreatedOn(date("Y-m-d H:i:s"));
            if ($objChatroom->saveChatRoom()) {
                $objUser = new \users;
                $objUser->setId($data['userId']);
                $user = $objUser->getUserById();
                $data['from'] = $user['name'];
                $data['msg']  = $data['msg'];
                $data['dt']  = date("H:i");
            }

            foreach ($this->clients as $client) {
                if ($this->users[$from->resourceId] == $this->users[$client->resourceId]) {
                    $data['from']  = "Me";
                } else {
                    $data['from']  = $user['name'];
                }
                $client->send(json_encode($data));
            }
        } else {
            $objUser = new \users;
            $objUser->setId($data['msg']);
            if ($data['type'] == "connect") {
                $objUser->setLoginStatus(1);
                $objUser->setLastLogin(date("d-m-Y H:i:s"));
                $objUser->updateLoginStatus();
            } else {
                $objUser->setLoginStatus(0);
                $objUser->setLastLogin(date("d-m-Y H:i:s"));
                $objUser->updateLoginStatus();
            }
            $this->users[$from->resourceId] = $data['msg'];
            foreach ($this->clients as $client) {
                if (!($from == $client)) {
                    $client->send(json_encode($data));
                }
            }
        }
    }

    public function onClose(ConnectionInterface $conn)
    {
        $data['type']  = "disconnect";
        $data['msg'] = $this->users[$conn->resourceId];
        $data['date'] = date("Y-m-d H:i:s");
        foreach ($this->clients as $client) {
            if (!($conn == $client)) {
                $client->send(json_encode($data));
            }
        }
        // The connection is closed, remove it, as we can no longer send it messages
        unset($this->users[$conn->resourceId]);
        $this->clients->detach($conn);


        echo "Connection {$conn->resourceId} has disconnected\n";
    }

    public function onError(ConnectionInterface $conn, \Exception $e)
    {
        echo "An error has occurred: {$e->getMessage()}\n";

        $conn->close();
    }
}
