<?php
/*
 * Copyright (c) Jan Sohn / xxAROX
 * All rights reserved.
 * I don't want anyone to use my source code without permission.
 */

declare(strict_types=1);
namespace HQGames\player;
use HQGames\Core\player\Player;
use HQGames\player\locker\PlayerLocker;
use JetBrains\PhpStorm\ArrayShape;


/**
 * Trait BridgePlayerTrait
 * @package HQGames\player
 * @author Jan Sohn / xxAROX
 * @date 23. July, 2022 - 20:58
 * @ide PhpStorm
 * @project Bridge
 */
trait BridgePlayerTrait{
	protected string $identifier;
	protected int $coins;
	protected int $default_coins;
	protected int $online_time;
	protected int $last_login;
	protected PlayerSettings $settings;
	protected PlayerLocker $locker;


	public function bridge_loadData(array $data): void{
		/** @var Player $this */
		$this->identifier = $data["identifier"];
		$this->coins = intval($data["coins"]);
		$this->default_coins = intval($data["default_coins"]);
		$this->online_time = intval($data["online_time"]);
		$this->last_login = intval($data["last_login"]);
		$this->settings = PlayerSettings::fromArray($this, $data["settings"]);
		$this->locker = new PlayerLocker($this, $data["settings"]);
	}

	#[ArrayShape([
		"coins"       => "int",
		"language"    => "int",
		"locale_code" => "string",
		"online_time" => "int",
		"last_login"  => "int",
		"settings"    => "array"
	])]
	public function bridge_saveData(): array{
		return [
			"coins" => $this->coins,
			"language" => $this->coins,
			"online_time" => $this->online_time,
			"last_login" => $this->last_login,
			"settings" => $this->settings->jsonSerialize(),
		];
	}

	/**
	 * Function getIdentifier
	 * @return string
	 */
	public function getIdentifier(): string{
		return $this->identifier;
	}

	/**
	 * Function getCoins
	 * @return int
	 */
	public function getCoins(): int{
		return $this->coins;
	}

	/**
	 * Function getLocaleCode
	 * @return string
	 */
	public function getLocaleCode(): string{
		return $this->locale_code;
	}

	/**
	 * Function getOnlineTime
	 * @return int
	 */
	public function getOnlineTime(): int{
		return $this->online_time;
	}

	/**
	 * Function getDefaultCoins
	 * @return int
	 */
	public function getDefaultCoins(): int{
		return $this->default_coins;
	}

	/**
	 * Function getLastLogin
	 * @return int
	 */
	public function getLastLogin(): int{
		return $this->last_login;
	}

	/**
	 * Function getSettings
	 * @return PlayerSettings
	 */
	public function getSettings(): PlayerSettings{
		return $this->settings;
	}

	/**
	 * Function setCoins
	 * @param int $coins
	 * @return void
	 */
	public function setCoins(int $coins): void{
		$this->coins = $coins;
	}

	/**
	 * Function setDefaultCoins
	 * @param int $default_coins
	 * @return void
	 */
	public function setDefaultCoins(int $default_coins): void{
		$this->default_coins = $default_coins;
	}
}
