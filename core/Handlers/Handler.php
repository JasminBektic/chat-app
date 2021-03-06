<?php

namespace App\Core\Handlers;

abstract class Handler
{
    
    /**
     * Initialized sockets
     *
     * @var array
     */
    public $clientSockets;

    /**
     * Clients data
     *
     * @var array
     */
    public $clients;

}
