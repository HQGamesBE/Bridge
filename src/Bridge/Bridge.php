<?php
/*
 * Copyright (c) Jan Sohn / HQGames
 * All rights reserved.
 * I don't want anyone to use my source code without permission.
 */
declare(strict_types=1);
namespace HQGames\Bridge;
use HQGames\Bridge\utils\BackendProperties;
use HQGames\network\CloudConnection;
use HQGames\network\Packets;
use HQGames\network\protocol\types\HandshakeData;
use HQGames\plugin\ICloudy;
use pocketmine\plugin\PluginBase;
use pocketmine\plugin\PluginDescription;
use pocketmine\plugin\PluginLoader;
use pocketmine\plugin\ResourceProvider;
use pocketmine\scheduler\Task;
use pocketmine\Server;
use pocketmine\utils\SingletonTrait;


/**
 * Class Bridge
 * @package HQGames\Bridge
 * @author Jan Sohn / HQGames
 * @date 04. July, 2022 - 00:10
 * @ide PhpStorm
 * @project Bridge
 */
class Bridge extends PluginBase implements ICloudy{
	const MAGIC = 0x2ab;
	use SingletonTrait {
		setInstance as private;
		reset as private;
	}


	private ?CloudConnection $cloudConnection = null;

	/**
	 * Bridge constructor.
	 * @param PluginLoader $loader
	 * @param Server $server
	 * @param PluginDescription $description
	 * @param string $dataFolder
	 * @param string $file
	 * @param ResourceProvider $resourceProvider
	 */
	public function __construct(PluginLoader $loader, Server $server, PluginDescription $description, string $dataFolder, string $file, ResourceProvider $resourceProvider){
		self::setInstance($this);
		parent::__construct($loader, $server, $description, $dataFolder, $file, $resourceProvider);
	}

	/**
	 * Function onCloudConnect
	 * @return void
	 */
	public function onCloudConnect(): void{
		$this->getLogger()->notice("Connected to cloud!");
	}

	/**
	 * Function onLoad
	 * @return void
	 */
	public function onLoad(): void{
		$this->getLogger()->info("Bridge is loading...");
	}

	/**
	 * Function onEnable
	 * @return void
	 */
	public function onEnable(): void{
		$this->cloudConnection = new CloudConnection($this->getServer()->getLogger(),
			new HandshakeData(
				BackendProperties::getInstance()->getIdentifier(),
				BackendProperties::getInstance()->getAuthToken()
			)
		);
		$this->getLogger()->info("Bridge is enabled!");
		Packets::getInstance()->connect();
	}

	public function getBackendProperties(): BackendProperties{
		return BackendProperties::getInstance();
	}

	/**
	 * Function onDisable
	 * @return void
	 */
	public function onDisable(): void{
		$this->cloudConnection->shutdown();
		$this->getLogger()->info("Bridge is disabled!");
	}

	/**
	 * Function getCloudConnection
	 * @return ?CloudConnection
	 */
	public function getCloudConnection(): ?CloudConnection{
		return $this->cloudConnection;
	}
}
