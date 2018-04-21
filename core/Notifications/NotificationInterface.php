<?php

namespace App\Core\Notifications;

interface NotificationInterface 
{

    /**
     * Show corresponding message
     *
     * @param string $socketResource
     * @param string $messageType
     * @param string $clientData - optional
     * @return void
     */
    public function message($socketResource, $messageType, $clientData);

}