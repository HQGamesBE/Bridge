<?php
/*
 * Copyright (c) Jan Sohn / xxAROX
 * All rights reserved.
 * I don't want anyone to use my source code without permission.
 */

declare(strict_types=1);
namespace HQGames\player;
/**
 * Trait BridgePlayerTrait
 * @package HQGames\player
 * @author Jan Sohn / xxAROX
 * @date 23. July, 2022 - 20:58
 * @ide PhpStorm
 * @project Bridge
 */
trait BridgePlayerTrait{
	protected int $identifier;
	protected int $coins;
	protected int $locale_code;

	public function bridge_loadData(array $data): void{
		$this->identifier = $data["identifier"];
		$this->coins = $data["coins"] ;
		$this->locale_code = $data["locale_code"];
	}

	public function bridge_saveData(array $data = []): array{
		return [
			"coins" => $this->coins,
			"language" => $this->coins,
			"locale_code" => $this->locale_code,
		];
	}
}
