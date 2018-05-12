<?php

namespace App\Core;

use App\Core\Handlers\ChatHandler;
use App\Core\Notifications\MessageNotification;
use App\Core\Notifications\ClientChatboxNotification;

class Server
{
    
    /**
     * Start server
     *
     * @return void
     */
    public static function run() {
        set_time_limit(0);
        $write = $except = NULL;
        
        $chatHandler = new ChatHandler;
        $socket = $chatHandler->createSocket();
        
        while (true) {
            $clientSockets = $chatHandler->clientSockets;
            socket_select($clientSockets, $write, $except, 0);
        
            if (in_array($socket, $clientSockets)) {
                $newSocket = socket_accept($socket);
                $header = socket_read($newSocket, 1024);
                $chatHandler->doHandshake($header, $newSocket, HOST_NAME, PORT);

                socket_recv($newSocket, $clientData, 1024, 0);
                $chatHandler->clientSockets[] = $newSocket;
                $chatHandler->clients[] = $clientData;
            
                $chatHandler->message(new MessageNotification, $newSocket, CLIENT_CONNECTION, $clientData);
                $clientSockets = $chatHandler->destroySocket($socket, $clientSockets);
            }

            foreach ($clientSockets as $clientSocket) {
                while(socket_recv($clientSocket, $socketData, 1024, 0) > 0) {
                    $chatHandler->message(new ClientChatboxNotification, $socketData, CLIENT_CHATBOX);
                    break 2;
                }

                $socketData = @socket_read($clientSocket, 1024, PHP_NORMAL_READ);

                if ($socketData === false) {
                    $clientData = $chatHandler->destroyClientData($clientSocket);
                    $chatHandler->message(new MessageNotification, $clientSocket, CLIENT_DISCONNECTION, $clientData);
                    $chatHandler->clientSockets = $chatHandler->destroySocket($clientSocket, $chatHandler->clientSockets);
                }
            }
        }
        socket_close($socket);
    }

}