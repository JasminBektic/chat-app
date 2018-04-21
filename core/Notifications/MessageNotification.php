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
     * @param string $clientData
     * @return string
     */
    public function message($socketResource, $messageType, $clientData) : string {
        socket_getpeername($socketResource, $clientIPAddress);
        $clientData = json_decode($clientData);

        switch($messageType) {
            case CLIENT_CONNECTION:
                $message = '<span class="text-success">' . $clientData->username . ' &nbsp;[' . $clientIPAddress . ']&nbsp; joined the room</span>';
                break;
            case CLIENT_DISCONNECTION:
                $message = '<span class="text-danger">' . $clientData->username . ' &nbsp;[' . $clientIPAddress . ']&nbsp; left the room</span>';
                break;
            default:
                $message = '';
        }

        $generatedMessage = ['message' => $message];
        
		return json_encode($generatedMessage);
    }
    
}