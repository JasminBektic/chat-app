<?php

namespace App\Core\Notifications;

use App\Core\Notifications\NotificationInterface as Notification;

class MessageNotification implements Notification 
{

    /**
     * Generate proper message
     *
     * @param resource $socketResource
     * @param string $messageType
     * @return string
     */
    public function message($socketResource, $messageType) : string {
        socket_getpeername($socketResource, $clientIPAddress);

        switch($messageType) {
            case CLIENT_CONNECTION:
                $message = '<span class="text-success">New client ' . $clientIPAddress.' joined</span>';
                break;
            case CLIENT_DISCONNECTION:
                $message = '<span clasS="text-danger">Client ' . $clientIPAddress.' disconnected</span>';
                break;
            default:
                $message = '';
        }

        $generatedMessage = ['message' => $message];
        
		return json_encode($generatedMessage);
    }
    
}