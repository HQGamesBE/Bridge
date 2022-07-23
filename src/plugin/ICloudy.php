<?php
/*
 * Copyright (c) Jan Sohn / xxAROX
 * All rights reserved.
 * I don't want anyone to use my source code without permission.
 */

declare(strict_types=1);
namespace HQGames\plugin;
/**
 * Interface ICloudy
 * @package HQGames\plugin
 * @author Jan Sohn / xxAROX
 * @date 23. July, 2022 - 15:33
 * @ide PhpStorm
 * @project Bridge
 */
interface ICloudy{
	/**
	 * Function onCloudConnect
	 * @return void
	 */
	function onCloudConnect(): void;

	/**
	 * Function onCloudDisconnect
	 * @return void
	 */
	function onCloudDisconnect(): void;

	/**
	 * Function onCloudError
	 * @param string $message
	 * @return void
	 */
	function onCloudError(string $message): void;
}
