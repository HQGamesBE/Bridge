<?php
/*
 * Copyright (c) Jan Sohn / xxAROX
 * All rights reserved.
 * I don't want anyone to use my source code without permission.
 */

declare(strict_types=1);
namespace HQGames\Bridge;
use HQGames\Bridge\utils\BackendProperties;
use HQGames\network\protocol\ConnectPacket;
use HQGames\network\protocol\DisconnectPacket;
use HQGames\plugin\ICloudy;
use pocketmine\Server;


/**
 * Class BridgePacketHandler
 * @package HQGames\Bridge
 * @author Jan Sohn / xxAROX
 * @date 23. July, 2022 - 16:32
 * @ide PhpStorm
 * @project Bridge
 */
class BridgePacketHandler implements \HQGames\network\IPacketHandler{
	public function handleConnectPacket(ConnectPacket $packet): void{
		Cache::getInstance()->secret = $packet->secret;
		BackendProperties::getInstance()->overwriteIdentifier($data["identifier"]);
		$this->cloudConnection->getLogger()->debug("Connected to cloud");
		foreach (Server::getInstance()->getPluginManager()->getPlugins() as $plugin) {
			if ($plugin instanceof ICloudy) $plugin->onCloudConnect();
		}
	}

	public function handleDisconnectPacket(DisconnectPacket $packet): void{
		// TODO: Implement handleDisconnectPacket() method.
	}
}
