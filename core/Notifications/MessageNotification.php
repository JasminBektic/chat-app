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
                $message = 'New client ' . $clientIPAddress.' joined';
                break;
            case CLIENT_DISCONNECTION:
                $message = 'Client ' . $clientIPAddress.' disconnected';
                break;
            default:
                $message = '';
        }

        $generatedMessage = ['message' => $message];
        
		return json_encode($generatedMessage);
    }
    
}