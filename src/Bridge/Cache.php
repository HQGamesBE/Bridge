<?php
/*
 * Copyright (c) Jan Sohn / xxAROX
 * All rights reserved.
 * I don't want anyone to use my source code without permission.
 */

declare(strict_types=1);
namespace HQGames\Bridge;
use pocketmine\utils\SingletonTrait;


/**
 * Class Cache
 * @package HQGames\Bridge
 * @author Jan Sohn / xxAROX
 * @date 22. July, 2022 - 22:11
 * @ide PhpStorm
 * @project Bridge
 */
final class Cache{
	use SingletonTrait{
		setInstance as private static;
		reset as private;
	}

	public string $secret = "null";

	private function __construct(){
		$this->secret = bin2hex(random_bytes(16));
	}

}
