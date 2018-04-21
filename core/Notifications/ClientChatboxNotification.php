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
     * @param string $clientData
     * @return string
     */
    public function message($messageBox, $messageType, $clientData) : string {
        $messageBox = json_decode($messageBox);

        if (!isset($messageBox->username) && !isset($messageBox->message) || $messageBox->message == '') 
            return '';

        switch($messageType) {
            case CLIENT_CHATBOX:
                $message = '<div class="col-md-2 bg-secondary text-white rounded d-inline-block align-top p-2 mr-2">' . 
                                $messageBox->username . ': 
                            </div>
                            <div class="col-md-9 bg-primary text-white rounded d-inline-block p-2">' . 
                                $messageBox->message .
                            '</div>';
                break;
            default:
                $message = '';
        }
					
        $generatedMessage = ['message' => $message];
        
		return json_encode($generatedMessage);
    }

}