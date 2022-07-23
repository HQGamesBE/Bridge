<?php
/*
 * Copyright (c) Jan Sohn / xxAROX
 * All rights reserved.
 * I don't want anyone to use my source code without permission.
 */

declare(strict_types=1);
namespace HQGames\player\stats;
use JetBrains\PhpStorm\Pure;


/**
 * Class Stats
 * @package HQGames\player\stats
 * @author Jan Sohn / xxAROX
 * @date 23. July, 2022 - 20:30
 * @ide PhpStorm
 * @project Bridge
 */
class Stats{
	protected array $data = [];


	#[Pure] public static function fromArray(array $data): Stats{
		$stats = new Stats();
		$stats->data = $data;
		return $stats;
	}

	/**
	 * Function getData
	 * @return array
	 */
	public function getData(): array{
		return $this->data;
	}
}
