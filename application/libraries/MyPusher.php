<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed'); 

require_once APPPATH . "libraries/pusher-http-php/lib/Pusher.php";

class MyPusher{

	const APP_ID     = '163140';
	const APP_KEY    = 'b3c7fc474d668cd4563e';
	const APP_SECRET = '221d49143b9fcdd747ef';

	private $channel;
	private $event;
	private $data;
	private $pusher;

	public function __construct()
	{
	   $this->pusher = new Pusher(self::APP_KEY, self::APP_SECRET, self::APP_ID);
	}

	public function Message($channel, $event, $data)
	{
		$this->channel = $channel;
		$this->event   = $event;
		$this->data    = $data;
		$this->pusher->trigger($this->channel, $this->event, $this->data);

		return TRUE;
	}

}
