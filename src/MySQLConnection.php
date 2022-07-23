<?php
/*
 * Copyright (c) Jan Sohn / xxAROX
 * All rights reserved.
 * I don't want anyone to use my source code without permission.
 */
declare(strict_types=1);
namespace HQGames;
use pocketmine\plugin\PluginBase;
use pocketmine\scheduler\ClosureTask;
use pocketmine\scheduler\Task;
use pocketmine\utils\SingletonTrait;
use xxAROX\xxTOOLS\utils\MySQLDatabase;


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
		parent::__construct(getenv("MYSQL_USEr") ?? throw new \RuntimeException("MYSQL_USER not set in environment"), getenv("MYSQL_PASSWORD") ?? throw new \RuntimeException("No MYSQL_PASSWORD set in environment"), getenv("MYSQL_DATABASE") ?? throw new \RuntimeException("No MYSQL_DATABASE set in environment"), getenv("MYSQL_HOST") ?? "localhost", intval(getenv("MYSQL_PORT") ?? 3306),);
		$this->registrant->getScheduler()->scheduleRepeatingTask(new ClosureTask(function(): void{
			$this->medoo->select("GLOBAL", ["interactive_timeout"]);
		}), 20);
	}
}
