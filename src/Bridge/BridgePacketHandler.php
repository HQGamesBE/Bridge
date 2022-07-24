<?php
/*
 * Copyright (c) Jan Sohn / xxAROX
 * All rights reserved.
 * I don't want anyone to use my source code without permission.
 */

declare(strict_types=1);
namespace HQGames\Bridge;
use HQGames\Bridge\utils\BackendProperties;
use HQGames\network\CloudConnection;
use HQGames\network\IPacketHandler;
use HQGames\network\PingPacket;
use HQGames\network\protocol\ConnectedPacket;
use HQGames\network\protocol\ConnectPacket;
use HQGames\network\protocol\DisconnectPacket;
use HQGames\network\protocol\ErrorPacket;
use HQGames\network\protocol\PingPongPacket;
use HQGames\network\protocol\PlayerLoadPacket;
use HQGames\network\protocol\ReportCreatePacket;
use HQGames\network\protocol\ReportSolvePacket;
use HQGames\network\protocol\UnknownPacket;
use HQGames\PlayerManager;
use HQGames\plugin\ICloudy;
use JetBrains\PhpStorm\Pure;
use pocketmine\scheduler\ClosureTask;
use pocketmine\Server;
use PrefixedLogger;


/**
 * Class BridgePacketHandler
 * @package HQGames\Bridge
 * @author Jan Sohn / xxAROX
 * @date 23. July, 2022 - 16:32
 * @ide PhpStorm
 * @project Bridge
 */
class BridgePacketHandler implements IPacketHandler{
	protected CloudConnection $cloudConnection;
	protected PrefixedLogger $logger;

	/**
	 * BridgePacketHandler constructor.
	 */
	#[Pure] public function __construct(CloudConnection $cloudConnection) {
		$this->cloudConnection = $cloudConnection;
		$this->logger = new PrefixedLogger($cloudConnection->getLogger(), "PacketHandler");
	}

	/**
	 * Function getCloudConnection
	 * @return CloudConnection
	 */
	public function getCloudConnection(): CloudConnection{
		return $this->cloudConnection;
	}

	public function getLogger(): PrefixedLogger{
		return $this->logger;
	}

	/**
	 * Function handleConnectPacket
	 * @param ConnectPacket $packet
	 * @return void
	 */
	public function handleConnectPacket(ConnectPacket $packet): void{
		$this->logger->info("Received ConnectPacket, this is a server, ignoring..");
	}

	/**
	 * Function handleDisconnectPacket
	 * @param DisconnectPacket $packet
	 * @return void
	 */
	public function handleDisconnectPacket(DisconnectPacket $packet): void{
		$this->cloudConnection->getLogger()->info("Disconnected fom cloud");
		Server::getInstance()->shutdown();
	}

	/**
	 * Function handleUnknownPacket
	 * @param UnknownPacket $packet
	 * @return void
	 */
	public function handleUnknownPacket(UnknownPacket $packet): void{
		$this->logger->notice("Received unknown packet, ignoring..");
	}

	public function handleConnectedPacket(ConnectedPacket $packet): void{
		Cache::getInstance()->secret = $packet->secret;
		PlayerManager::getInstance()->fromArray($packet->players);
		BackendProperties::getInstance()->overwriteIdentifier($packet->identifier);
		$this->cloudConnection->getLogger()->debug("Connected to cloud");
	}

	public function handleErrorPacket(ErrorPacket $packet): void{
		$this->cloudConnection->getLogger()->error("Error received: " . $packet->error);
	}

	/**
	 * Function handlePingPongPacket
	 * @param PingPongPacket $packet
	 * @return void
	 */
	public function handlePingPongPacket(PingPongPacket $packet): void{
		Bridge::getInstance()->last_pong = time();
		$this->cloudConnection->getLogger()->info("Pong received");
		Bridge::getInstance()->getScheduler()->scheduleDelayedTask(new ClosureTask(function (): void{
			$this->cloudConnection->sendPacket(PingPongPacket::build(time() * 1000, 0));
		}), 20);
	}

	/**
	 * Function handleReportCreatePacket
	 * @param ReportCreatePacket $packet
	 * @return void
	 */
	public function handleReportCreatePacket(ReportCreatePacket $packet): void{
	}

	/**
	 * Function handleReportSolvePacket
	 * @param ReportSolvePacket $packet
	 * @return void
	 */
	public function handleReportSolvePacket(ReportSolvePacket $packet): void{
	}

	public function handlePlayerLoadPacket(PlayerLoadPacket $packet): void{
		if (isset($packet->data["from"])) {
			$player = PlayerManager::getInstance()->getPlayerByIdentifier($packet->data["from"]);
			$player->
		}
	}
}
