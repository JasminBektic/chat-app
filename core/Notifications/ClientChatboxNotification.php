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