<?php

namespace App\Core\Notifications;

use App\Core\Notifications\NotificationInterface as Notification;

class ClientChatboxNotification implements Notification 
{

    /**
     * Generate proper message
     *
     * @param string $messageBox
     * @param string $messageType
     * @return string
     */
    public function message($messageBox, $messageType) : string {
        $messageBox = json_decode($messageBox);

        switch($messageType) {
            case CLIENT_CHATBOX:
                $message = $messageBox->username . ': <div>' . $messageBox->message . '</div>';
                break;
            default:
                $message = '';
        }
					
        $generatedMessage = ['message' => $message];
        
		return json_encode($generatedMessage);
    }

}