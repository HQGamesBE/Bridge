<?php
/*
 * Copyright (c) Jan Sohn / xxAROX
 * All rights reserved.
 * I don't want anyone to use my source code without permission.
 */
declare(strict_types=1);
namespace HQGames;
use HQGames\Core\Options;
use HQGames\Core\player\Player;
use HQGames\utils\MySQLDatabase;
use pocketmine\plugin\PluginBase;
use pocketmine\scheduler\ClosureTask;
use pocketmine\scheduler\Task;
use pocketmine\utils\SingletonTrait;
use RuntimeException;


/**
 * Class MySQLConnection
 * @package HQGames
 * @author Jan Sohn / xxAROX
 * @date 23. July, 2022 - 20:41
 * @ide PhpStorm
 * @project Bridge
 */
class MySQLConnection extends MySQLDatabase{
	use SingletonTrait {
		setInstance as private static;
		reset as private;
	}


	/**
	 * MySQLConnection constructor.
	 * @param PluginBase $registrant
	 */
	public function __construct(protected PluginBase $registrant){
		parent::__construct(getenv("MYSQL_USER") ?? throw new RuntimeException("MYSQL_USER not set in environment"), getenv("MYSQL_PASSWORD") ?? throw new RuntimeException("No MYSQL_PASSWORD set in environment"), getenv("MYSQL_DATABASE") ?? throw new RuntimeException("No MYSQL_DATABASE set in environment"), getenv("MYSQL_HOST") ?? "localhost", intval(getenv("MYSQL_PORT") ?? 3306),);
		$this->createTables();
		$this->registrant->getScheduler()->scheduleRepeatingTask(new ClosureTask(function(): void{
			$this->medoo->select("GLOBAL", ["interactive_timeout"]);
		}), 20);
	}

	/**
	 * Function createTables
	 * @return void
	 */
	private function createTables(): void{
		// create players table
		$this->medoo->create("players", [
			"index" => ["INTEGER", "UNSIGNED", "NOT NULL","AUTO_INCREMENT"],
			"identifier" => ["VARCHAR(32)", "PRIMARY", "NOT NULL"],
			"gamertag" => ["VARCHAR(32)", "NOT NULL"],
			"last_online" => ["INTEGER", "NOT NULL", "DEFAULT 0"],
			"online_time" => ["INTEGER", "NOT NULL", "DEFAULT 0"],
			"coins" => ["INTEGER", "NOT NULL", "DEFAULT " . Options::$default_coins],
			"default_coins" => ["INTEGER", "NOT NULL", "DEFAULT " . Options::$default_coins],
			"settings" => ["TEXT"],
			"locker" => ["TEXT"],
			"xuid" => ["VARCHAR(32)", "NOT NULL"],
			"nicked" => ["VARCHAR(32)", "NOT NULL", "DEFAULT false"],
		]);
		// create ips table
		$this->medoo->create("ips", [
			"index" => ["INTEGER", "UNSIGNED", "PRIMARY", "NOT NULL","AUTO_INCREMENT"],
			"identifier" => ["VARCHAR(32)", "NOT NULL"],
			"ip" => ["VARCHAR(128)", "NOT NULL"],
			"version" => ["INTEGER", "NOT NULL", "DEFAULT 4"],
			"timestamp" => ["INTEGER", "NOT NULL"],
		]);
		// create stats table
		$this->medoo->create("stats", [
			"identifier" => ["VARCHAR(32)", "NOT NULL", "PRIMARY"],
			"stats" => ["TEXT"],
		]);
		// create groups table
		$this->medoo->create("groups", [
			"index" => ["INTEGER", "UNSIGNED", "PRIMARY", "NOT NULL","AUTO_INCREMENT"],
			"name" => ["VARCHAR(32)", "NOT NULL"],
			"emoji" => ["VARCHAR(7)", "NOT NULL", "DEFAULT ''"],
			"color" => ["VARCHAR(3)", "NOT NULL", "DEFAULT '§7'"],
			"chat_format" => ["VARCHAR(128)", "NOT NULL", "DEFAULT '§o{COLOR}{GROUP} §8| {COLOR}{NAME} » §f{MESSAGE}'"],
			"nametag_format" => ["VARCHAR(128)", "NOT NULL", "DEFAULT '§l§8  « §r{COLOR}{NAME}§8§l »  §r'"],
			"permissions" => ["TEXT"],
			"parents" => ["TEXT"],
			"nick_permission" => ["VARCHAR(128)", "DEFAULT NULL"], // NOTE: null = no permission => group is not a nick group
		]);
	}

	public function loadPlayer(Player $player): void{
		if ($this->medoo->has("players", ["identifier" => $player->getIdentifier()])) { // TODO: switch to PlayerManager::hasPlayer($player->getIdentifier())
			$this->medoo->update("players", [
				"gamertag" => $player->getName(),
				"last_login" => time()
			], ["identifier" => $player->getIdentifier()]);
		} else {
			$this->medoo->insert("players", [
				"identifier" => $player->getIdentifier(),
				"gamertag" => $player->getName(),
				"last_login" => time(),
				"online_time" => 1,
			]);
		}
		$data = $this->medoo->select("players", [
			"index",
			"identifier",
			"gamertag",
			"last_login",
			"online_time",
			"coins",
			"default_coins",
			"settings [JSON]",
			"locker [JSON]",
		], ["identifier" => $player->getIdentifier()]);
		if (is_null($data)) throw new RuntimeException("Failed to load player data");
		$player->loadData($data);
	}

	public function savePlayer(Player $player): void{
		$this->medoo->update("players", [
			"gamertag" => $player->getName(),
			"last_login" => time(),
			"online_time" => $player->getOnlineTime(),
			"coins" => $player->getCoins(),
			"default_coins" => $player->getDefaultCoins(),
			"settings" => $player->getSettings(),
			"locker" => $player->getLocker(),
		], ["identifier" => $player->getIdentifier()]);
	}

	public function registerIp(string $identifier, string $ip): void{
		if ($this->medoo->has("ips", ["identifier" => $identifier, "ip" => $ip])) return;
		$this->medoo->insert("ips", [
			"identifier" => $identifier,
			"ip" => $ip,
			"version" => str_contains($ip, ":") ? 4 : 6,
			"timestamp" => time(),
		]);
	}

	public function getIps(string $identifier): array{
		return $this->medoo->select("ips", ["ip", "version", "timestamp"], ["identifier" => $identifier]);
	}
}
