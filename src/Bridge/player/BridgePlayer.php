<?php
/*
 * Copyright (c) Jan Sohn / xxAROX
 * All rights reserved.
 * I don't want anyone to use my source code without permission.
 */

declare(strict_types=1);
namespace HQGames\Bridge\player;
use pocketmine\player\Player;


/**
 * Class BridgePlayer
 * @package HQGames\Bridge\player
 * @author Jan Sohn / xxAROX
 * @date 12. July, 2022 - 15:12
 * @ide PhpStorm
 * @project Bridge
 */
class BridgePlayer extends Player{
	use ReportPlayerTrait;
	use LangaugePlayerTrait;
	protected int $identifier;
	protected int $coins;
	protected int $locale_code;


	public function loadData(array $data): void{
		$this->identifier = $data["identifier"];
		$this->coins = $data["coins"] ;
	}

	public function saveData(array $data = []): array{
		return array_merge([
			"coins" => $this->coins,
			"language" => $this->coins,
		], $data);
	}
}
