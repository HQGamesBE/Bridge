<?php
/*
 * Copyright (c) Jan Sohn / xxAROX
 * All rights reserved.
 * I don't want anyone to use my source code without permission.
 */

declare(strict_types=1);
namespace HQGames\player;
use HQGames\player\stats\StatsCollection;
use HQGames\ReportManager\ReportHistory;


/**
 * Interface IVirtuellPlayer
 * @package HQGames\player
 * @author Jan Sohn / xxAROX
 * @date 23. July, 2022 - 20:28
 * @ide PhpStorm
 * @project Bridge
 */
interface IVirtuellPlayer{
	public function getIdentifier(): string;

	public function getGroup(): string;

	/**
	 * Function getPunishments
	 * @return Punishment[]
	 */
	public function getPunishments(): array;

	public function getReportHistory(): ReportHistory;

	public function getRankedStats(): StatsCollection;

	public function getUnrankedStats(): StatsCollection;
}
