<?php
/*
 * Copyright (c) Jan Sohn / xxAROX
 * All rights reserved.
 * I don't want anyone to use my source code without permission.
 */

declare(strict_types=1);
namespace HQGames\player;
use HQGames\player\locker\PlayerLocker;
use HQGames\player\stats\StatsCollection;
use HQGames\ReportManager\ReportHistory;
use pocketmine\player\Player;


/**
 * Class OfflinePlayer
 * @package HQGames\player
 * @author Jan Sohn / xxAROX
 * @date 24. July, 2022 - 01:03
 * @ide PhpStorm
 * @project Bridge
 */
class OfflinePlayer implements IVirtuellPlayer{
	public function getReportHistory(): ReportHistory{
		// TODO: Implement getReportHistory() method.
	}

	public function getIdentifier(): string{
		// TODO: Implement getIdentifier() method.
	}

	public function getLangCode(): string{
		// TODO: Implement getLangCode() method.
	}

	public function getLastSeen(): int{
		// TODO: Implement getLastSeen() method.
	}

	public function getLocker(): PlayerLocker{
		// TODO: Implement getLocker() method.
	}

	public function getSettings(): PlayerSettings{
		// TODO: Implement getSettings() method.
	}

	public function getGroup(): Group{
		// TODO: Implement getGroup() method.
	}

	public function getOnlineTime(): int{
		// TODO: Implement getOnlineTime() method.
	}

	/**
	 * @inheritDoc
	 */
	public function getPunishments(): array{
		// TODO: Implement getPunishments() method.
	}

	public function getRankedStats(): StatsCollection{
		// TODO: Implement getRankedStats() method.
	}

	public function getUnrankedStats(): StatsCollection{
		// TODO: Implement getUnrankedStats() method.
	}

	public function getName(): string{
		// TODO: Implement getName() method.
	}

	public function getXuid(): string{
		// TODO: Implement getXuid() method.
	}

	public function setGroup(Group $group, Player $by): Group{
		// TODO: Implement setGroup() method.
	}

	public function isNicked(): bool{
		// TODO: Implement isNicked() method.
	}

	public function setNicked(bool $value = false): void{
		// TODO: Implement setNicked() method.
	}
}
