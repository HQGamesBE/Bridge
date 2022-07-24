<?php
/*
 * Copyright (c) Jan Sohn / xxAROX
 * All rights reserved.
 * I don't want anyone to use my source code without permission.
 */

declare(strict_types=1);
namespace HQGames\player;
use HQGames\LanguageManager\player\IVirtualLanguagePlayer;
use HQGames\player\locker\PlayerLocker;
use HQGames\player\stats\StatsCollection;
use HQGames\ReportManager\ReportHistory;
use pocketmine\player\Player;


/**
 * Interface IVirtuellPlayer
 * @package HQGames\player
 * @author Jan Sohn / xxAROX
 * @date 23. July, 2022 - 20:28
 * @ide PhpStorm
 * @project Bridge
 */
interface IVirtuellPlayer extends
	IVirtualReportPlayer,
	IVirtualLanguagePlayer
{
	public function getIdentifier(): string;

	public function getName(): string;

	public function isOnline(): bool;

	public function getXuid(): string;

	public function loadData(array $data): void;
	public function saveData(): array;

	// TODO: player selectable timezone

	public function getGroup(): Group;// TODO: move to IVirtualGroupPlayer
	public function setGroup(Group $group, Player $by): Group;// TODO: move to IVirtualGroupPlayer
	public function isNicked(): bool; // TODO: move to IVirtualGroupPlayer
	public function setNicked(bool $value = false): void; // TODO: move to IVirtualGroupPlayer

	public function getLastSeen(): int;

	public function getLocker(): PlayerLocker;

	public function getSettings(): PlayerSettings;

	public function getOnlineTime(): int;

	/**
	 * Function getPunishments
	 * @return Punishment[]
	 */
	public function getPunishments(): array; // TODO: move to IVirtualPunishmentPlayer

	public function getRankedStats(): StatsCollection;

	public function getUnrankedStats(): StatsCollection;
}
