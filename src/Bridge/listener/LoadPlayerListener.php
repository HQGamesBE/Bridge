<?php
/*
 * Copyright (c) Jan Sohn / xxAROX
 * All rights reserved.
 * I don't want anyone to use my source code without permission.
 */

declare(strict_types=1);
namespace HQGames\Bridge\listener;
use HQGames\MySQLConnection;
use pocketmine\event\player\PlayerCreationEvent;


/**
 * Class LoadPlayerListener
 * @package HQGames\Bridge\listener
 * @author Jan Sohn / xxAROX
 * @date 24. July, 2022 - 01:58
 * @ide PhpStorm
 * @project Bridge
 */
class LoadPlayerListener implements \pocketmine\event\Listener{
	public function PlayerCreationEvent(PlayerCreationEvent $event): void{
		$session = $event->getNetworkSession();
		MySQLConnection::getInstance()->getMedoo()->select("players", "*", ["identifier" => $session->getIdentifier()]);
	}
}
