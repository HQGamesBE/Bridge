<?php
/*
 * Copyright (c) Jan Sohn / HQGames
 * All rights reserved.
 * I don't want anyone to use my source code without permission.
 */

declare(strict_types=1);
namespace HQGames\Bridge;
use pocketmine\plugin\PluginBase;
use pocketmine\utils\SingletonTrait;


/**
 * Class Bridge
 * @package HQGames\Bridge
 * @author Jan Sohn / HQGames
 * @date 04. July, 2022 - 00:10
 * @ide PhpStorm
 * @project Bridge
 */
class Bridge extends PluginBase{
	use SingletonTrait;


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
		$this->getLogger()->info("Bridge is enabled!");
	}

	/**
	 * Function onDisable
	 * @return void
	 */
	public function onDisable(): void{
		$this->getLogger()->info("Bridge is disabled!");
	}
}
