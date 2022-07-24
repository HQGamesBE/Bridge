<?php
/*
 * Copyright (c) Jan Sohn / xxAROX
 * All rights reserved.
 * I don't want anyone to use my source code without permission.
 */

declare(strict_types=1);
namespace HQGames\logger;
use pocketmine\utils\SingletonTrait;


/**
 * Class Logger
 * @package HQGames\logger
 * @author Jan Sohn / xxAROX
 * @date 24. July, 2022 - 15:48
 * @ide PhpStorm
 * @project Bridge
 */
class Logger{
	use SingletonTrait{
		setInstance as private static;
		reset as private;
	}

	protected array $entries = [];

	private function __construct(){
	}

	public function log(string $webhook_name, string $message, array $params = []): void{
		$this->entries[] = LoggerEntry::new($webhook_name, $message, $params);
	}
}
