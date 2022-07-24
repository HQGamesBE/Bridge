<?php
/*
 * Copyright (c) Jan Sohn / xxAROX
 * All rights reserved.
 * I don't want anyone to use my source code without permission.
 */

declare(strict_types=1);
namespace HQGames\Bridge\player;
use HQGames\LanguageManager\player\LanguagePlayerTrait;
use HQGames\player\BridgePlayerTrait;
use HQGames\player\ExtendedPlayerInfo;
use HQGames\player\IVirtuellPlayer;
use HQGames\player\ReportPlayerTrait;
use InvalidArgumentException;
use JetBrains\PhpStorm\Pure;
use pocketmine\entity\Location;
use pocketmine\nbt\tag\CompoundTag;
use pocketmine\network\mcpe\NetworkSession;
use pocketmine\player\Player;
use pocketmine\player\PlayerInfo;
use pocketmine\player\XboxLivePlayerInfo;
use pocketmine\Server;


/**
 * Class BridgePlayer
 * @package HQGames\Bridge\player
 * @author Jan Sohn / xxAROX
 * @date 12. July, 2022 - 15:12
 * @ide PhpStorm
 * @project Bridge
 */
class BridgePlayer extends Player implements IVirtuellPlayer{
	use BridgePlayerTrait;
	use ReportPlayerTrait;
	use LanguagePlayerTrait;


	protected ExtendedPlayerInfo $extendedPlayerInfo;

	public function __construct(Server $server, NetworkSession $session, PlayerInfo $playerInfo, bool $authenticated, Location $spawnLocation, ?CompoundTag $namedtag){
		if (!$playerInfo instanceof XboxLivePlayerInfo)
			throw new InvalidArgumentException("PlayerInfo must be an XboxLivePlayerInfo instance");
		$this->extendedPlayerInfo = new ExtendedPlayerInfo($playerInfo->getXuid(), $playerInfo->getUsername(), $playerInfo->getUuid(), $playerInfo->getSkin(), $playerInfo->getLocale(), $playerInfo->getExtraData());
		parent::__construct($server, $session, $playerInfo, $authenticated, $spawnLocation, $namedtag);
	}

	public function loadData(array $data): void{
		$this->bridge_loadData($data);
		$this->report_loadData($data);
		$this->language_loadData($data);
	}

	public function saveData(array $data = []): array{
		return array_merge(
			[],
			$this->bridge_saveData(),
			$this->report_saveData(),
			$this->language_saveData()
		);
	}

	/**
	 * Function getExtendedPlayerInfo
	 * @return ExtendedPlayerInfo
	 */
	public function getExtendedPlayerInfo(): ExtendedPlayerInfo{
		return $this->extendedPlayerInfo;
	}
}
