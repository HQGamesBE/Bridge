<?php
/*
 * Copyright (c) Jan Sohn / xxAROX
 * All rights reserved.
 * I don't want anyone to use my source code without permission.
 */

declare(strict_types=1);
namespace HQGames\event\packet;
use HQGames\network\CloudConnection;
use pocketmine\event\CancellableTrait;
use pocketmine\event\Event;


/**
 * Class CloudPacketResponseEvent
 * @package HQGames\event\packet
 * @author Jan Sohn / xxAROX
 * @date 23. July, 2022 - 14:51
 * @ide PhpStorm
 * @project Bridge
 */
class CloudPacketResponseEvent extends Event{
	use CancellableTrait;

	protected CloudConnection $cloudConnection;
	public array $data;


	public function __construct(CloudConnection $cloudConnection, array $data){
		$this->cloudConnection = $cloudConnection;
		$this->data = $data;
	}

	/**
	 * Function getCloudConnection
	 * @return CloudConnection
	 */
	public function getCloudConnection(): CloudConnection{
		return $this->cloudConnection;
	}
}
