<?php

namespace App\Core\Handlers;

use App\Core\Handlers\Handler;
use App\Core\Notifications\NotificationInterface as Notification;

class ChatHandler extends Handler 
{
	
	public function __construct() {
        //
	}

	/**
	 * Creating of a new socket and binding to a port
	 *
	 * @return resource
	 */
	public function createSocket() {
        $socket = socket_create(AF_INET, SOCK_STREAM, SOL_TCP);
        socket_set_option($socket, SOL_SOCKET, SO_REUSEADDR, 1);
        socket_bind($socket, 0, PORT);
		socket_listen($socket);
		
		$this->clientSockets = [$socket];

        return $socket;
	}
	
	/**
	 * Remove specific resource from resource collection
	 *
	 * @param string $socket
	 * @param array $clientSockets
	 * @return array
	 */
	public function destroySocket($socket, $clientSockets) : array {
		$index = array_search($socket, $clientSockets);
		unset($clientSockets[$index]);

		return $clientSockets;
	}
	
	/**
	 * Socket initialization
	 *
	 * @param string $header
	 * @param string $socket
	 * @param string $host
	 * @param string $port
	 * @return void
	 */
	public function doHandshake($header, $socket, $host, $port) {
		$headers = [];
		$lines = preg_split("/\r\n/", $header);

		foreach($lines as $line) {
			$line = chop($line);
			if(preg_match('/\A(\S+): (.*)\z/', $line, $matches)) {
				$headers[$matches[1]] = $matches[2];
			}
		}

		$secKey = $headers['Sec-WebSocket-Key'];
		$RFCMagicString = '258EAFA5-E914-47DA-95CA-C5AB0DC85B11';
		$secAccept = base64_encode(pack('H*', sha1($secKey . $RFCMagicString)));
		$buffer = "HTTP/1.1 101 Web Socket Protocol Handshake\r\n" .
					"Upgrade: websocket\r\n" .
					"Connection: Upgrade\r\n" .
					"WebSocket-Origin: $host\r\n" .
					"WebSocket-Location: ws://$host:$port\r\n".
					"Sec-WebSocket-Accept:$secAccept\r\n\r\n";

		socket_write($socket, $buffer, strlen($buffer));
	}

	/**
	 * Assigning proper notification message
	 *
	 * @param Notification $notification
	 * @param string $socketResource
	 * @param string $messageType
	 * @return void
	 */
	public function message(Notification $notification, $socketResource, $messageType) {
		switch($messageType) {
			case CLIENT_CHATBOX:
				$socketResource = $this->unmask($socketResource);
				$message = $notification->message($socketResource, $messageType);
				break;
			default:
				$message = $notification->message($socketResource, $messageType);
		}

		$message = $this->mask($message);
		$this->send($message);
	}

	/**
	 * Display notification
	 *
	 * @param string $message
	 * @return void
	 */
	private function send($message) {
		foreach($this->clientSockets as $clientSocket)
		{
			@socket_write($clientSocket, $message, strlen($message));
		}
	}

	/**
	 * Transform binary data into readable string
	 *
	 * @param string $socketData
	 * @return string
	 */
	private function unmask($socketData) : string {
		$length = ord($socketData[1]) & 127;

		if($length == 126) {
			$masks = substr($socketData, 4, 4);
			$data = substr($socketData, 8);
		} elseif($length == 127) {
			$masks = substr($socketData, 10, 4);
			$data = substr($socketData, 14);
		} else {
			$masks = substr($socketData, 2, 4);
			$data = substr($socketData, 6);
		}

		$socketData = '';

		for ($i = 0; $i < strlen($data); ++$i) {
			$socketData .= $data[$i] ^ $masks[$i%4];
		}

		return $socketData;
	}

	/**
	 * Transform readable string into binary
	 *
	 * @param string $socketData
	 * @return void
	 */
	private function mask($socketData) : string {
		$b1 = 0x80 | (0x1 & 0x0f);
		$length = strlen($socketData);
		
		if($length <= 125)
			$header = pack('CC', $b1, $length);
		elseif($length > 125 && $length < 65536)
			$header = pack('CCn', $b1, 126, $length);
		elseif($length >= 65536)
			$header = pack('CCNN', $b1, 127, $length);

		return $header.$socketData;
	}

}