<?php
/*
 * Copyright (c) Jan Sohn / xxAROX
 * All rights reserved.
 * I don't want anyone to use my source code without permission.
 */

declare(strict_types=1);
namespace HQGames\player;
use pocketmine\entity\Skin;
use Ramsey\Uuid\UuidInterface;


/**
 * Class ExtendedPlayerInfo
 * @package HQGames\player
 * @author Jan Sohn / xxAROX
 * @date 23. July, 2022 - 22:46
 * @ide PhpStorm
 * @project Bridge
 */
class ExtendedPlayerInfo extends \pocketmine\player\PlayerInfo implements \JsonSerializable{
	private string $xuid;

	/**
	 * PlayerInfo constructor.
	 * @param string $xuid
	 * @param string $username
	 * @param UuidInterface $uuid
	 * @param Skin $skin
	 * @param string $locale
	 * @param array $extraData
	 */
	public function __construct(string $xuid, string $username, UuidInterface $uuid, Skin $skin, string $locale, array $extraData = []){
		parent::__construct($username, $uuid, $skin, $locale, $extraData);
		$this->xuid = $xuid;
	}

	public function getXuid(): string{
		return $this->xuid;
	}

	/**
	 * Function withoutXboxData
	 * @return \pocketmine\player\PlayerInfo
	 */
	public function withoutXboxData(): \pocketmine\player\PlayerInfo{
		return new \pocketmine\player\PlayerInfo($this->getUsername(), $this->getUuid(), $this->getSkin(), $this->getLocale(), $this->getExtraData());
	}

	/**
	 * Function getServerAddress
	 * @return string
	 */
	public function getServerAddress(): string{
		return $this->getExtraData()["ServerAddress"] ?? "";
	}

	/**
	 * Function getDeviceOS
	 * @return int
	 */
	public function getDeviceOS(): int{
		return $this->getExtraData()["DeviceOS"] ?? -1;
	}

	/**
	 * Function getDeviceOS__toString
	 * @return string
	 */
	public function getDeviceOS__toString(): string{
		return "os.{$this->getDeviceOS()}";
	}

	/**
	 * Function getCurrentInputMode
	 * @return int
	 */
	public function getCurrentInputMode(): int{
		return $this->getExtraData()["CurrentInputMode"] ?? 0;
	}

	/**
	 * Function getCurrentInputMode
	 * @return string
	 */
	public function getCurrentInputMode__toString(): string{
		return "inputMode.{$this->getCurrentInputMode()}";
	}

	/**
	 * Function getDefaultInputMode
	 * @return int
	 */
	public function getDefaultInputMode(): int{
		return $this->getExtraData()["DefaultInputMode"] ?? 0;
	}

	/**
	 * Function getDefaultInputMode__toString
	 * @return string
	 */
	public function getDefaultInputMode__toString(): string{
		return "inputMode.{$this->getDefaultInputMode()}";
	}
}
