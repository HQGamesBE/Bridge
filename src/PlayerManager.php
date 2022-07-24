<?php
/*
 * Copyright (c) Jan Sohn / xxAROX
 * All rights reserved.
 * I don't want anyone to use my source code without permission.
 */

declare(strict_types=1);
namespace HQGames;
use HQGames\Core\player\Player;
use HQGames\player\IVirtuellPlayer;
use HQGames\player\settings\PlayerSettingsEntry;
use InvalidArgumentException;
use pocketmine\utils\SingletonTrait;


/**
 * Class PlayerManager
 * @package HQGames
 * @author Jan Sohn / xxAROX
 * @date 24. July, 2022 - 01:00
 * @ide PhpStorm
 * @project Bridge
 */
class PlayerManager{
	use SingletonTrait{
		setInstance as private static;
		reset as private;
	}

	/** @var array<identifier, IVirtuellPlayer> */
	protected array $players = [];

	private function __construct(){
		self::$instance = $this;
	}

	public function registerSettingsEntries(): void{
	}

	public function getSettingsEntry(string $controller): ?PlayerSettingsEntry{
		return $this->settings_entries[mb_strtolower($controller)] ?? null;
	}

	/**
	 * Function getEntries
	 * @return array
	 */
	public function getEntries(): array{
		return $this->settings_entries;
	}

	public function registerSettingsEntry(PlayerSettingsEntry $entry): void{
		if (isset($this->settings_entries[mb_strtolower($entry->getController())]))
			throw new InvalidArgumentException("Player settings entry with identifier '" . $entry->getController() . "' already exists.");

		$this->settings_entries[mb_strtolower($entry->getController())] = $entry;
	}

	public function isSettingsEntry(string $controller): bool{
		return isset($this->settings_entries[mb_strtolower($controller)]);
	}

	public function unregisterSettingsEntry(string $controller): void{
		if (!isset($this->settings_entries[mb_strtolower($controller)]))
			throw new InvalidArgumentException("Controller '$controller' is not registered");

		unset($this->settings_entries[mb_strtolower($controller)]);
	}

	public function addPlayer(IVirtuellPlayer $player): void{
		$this->players[$player->getIdentifier()] = $player;
	}

	/**
	 * Function getPlayers
	 * @return array
	 */
	public function getPlayers(): array{
		return $this->players;
	}

	public function getPlayerByName(string $name): ?IVirtuellPlayer{
		foreach($this->players as $player){
			if ($player->getName() === $name){
				return $player;
			}
		}
		return null;
	}

	public function add(Player $player): void{
		$player->loadData();
		$this->players[$player->getIdentifier()] = $player;
	}
	public function offline(IVirtuellPlayer $player): void{
		$player->saveData();
		$this->players[$player->getIdentifier()] = $player;
	}

	/**
	 * Function getPlayerByIdentifier
	 * @param string $identifier
	 * @return null|IVirtuellPlayer|Player
	 */
	public function getPlayerByIdentifier(string $identifier): null|IVirtuellPlayer|Player{
		$player = $this->players[$identifier] ?? null;
	}
}
