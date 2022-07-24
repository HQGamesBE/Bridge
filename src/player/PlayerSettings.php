<?php
/*
 * Copyright (c) Jan Sohn / xxAROX
 * All rights reserved.
 * I don't want anyone to use my source code without permission.
 */

declare(strict_types=1);
namespace HQGames\player;
use HQGames\Bridge\Bridge;
use HQGames\Core\player\Player;
use HQGames\forms\elements\Label;
use HQGames\forms\elements\Toggle;
use HQGames\player\settings\PlayerSettingsEntry;
use HQGames\PlayerManager;
use JsonSerializable;
use pocketmine\block\Element;


/**
 * Class PlayerSettings
 * @package HQGames\player
 * @author Jan Sohn / xxAROX
 * @date 24. July, 2022 - 01:15
 * @ide PhpStorm
 * @project Bridge
 */
class PlayerSettings implements JsonSerializable{
	/** @var PlayerSettingsEntry[] */
	protected array $entries = [];

	/**
	 * Function fromArray
	 * @param Player $player
	 * @param array $array
	 * @return static
	 */
	static function fromArray(Player $player, array $array): self{
		$self = new self($player);
		foreach ($array as $key => $value) {
			if (PlayerManager::getInstance()->isSettingsEntry($key))
				$self->entries[mb_strtolower($key)] = PlayerSettingsEntry::fromArray($value);
			else
				Bridge::getInstance()->getLogger()->error("Player settings entry with controller name '" . $key . "' does not exist.");
		}
		return $self;
	}

	/**
	 * PlayerServerSettings constructor.
	 * @param Player $player
	 */
	public function __construct(protected Player $player){
	}

	/**
	 * Function getEntries
	 * @return array
	 */
	public function getEntries(): array{
		return $this->entries;
	}

	public function getEntry(string $controller): ?PlayerSettingsEntry{
		return $this->entries[mb_strtolower($controller)] ?? null;
	}

	/**
	 * Function jsonSerialize
	 * @return array
	 */
	public function jsonSerialize(): array{
		$arr = [];
		foreach ($this->entries as $entry) $arr[mb_strtolower($entry->getController())] = $entry->jsonSerialize();
		return $arr;
	}

	/**
	 * Function toServerSettingsForm
	 * @return Element[]
	 */
	public function toServerSettingsForm(): array{
		$array = [];
		foreach ($this->jsonSerialize() as $name => $value) {
			if (is_bool($value)) {
				$array[] = new Toggle("%ui.playersettings.${name}", $value);
			} else {
				$array[] = new Label("§4Error for ${name}: §cUndefined type §6" . gettype($value));
			}
		}
		return $array;
	}
}
