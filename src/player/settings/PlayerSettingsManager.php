<?php
/*
 * Copyright (c) Jan Sohn / xxAROX
 * All rights reserved.
 * I don't want anyone to use my source code without permission.
 */

declare(strict_types=1);
namespace HQGames\player\settings;
use HQGames\Permissions;
use HQGames\player\PlayerSettings;
use InvalidArgumentException;
use JetBrains\PhpStorm\Pure;
use pocketmine\permission\DefaultPermissions;
use pocketmine\permission\Permission;
use pocketmine\permission\PermissionManager;
use ReflectionClass;


/**
 * Class PlayerSettingsManager
 * @package HQGames\player\settings
 * @author Jan Sohn / xxAROX
 * @date 24. July, 2022 - 19:07
 * @ide PhpStorm
 * @project Bridge
 */
class PlayerSettingsManager{
	private static PlayerSettingsManager $instance;
	/** @var PlayerSettingsEntry[] */
	private static array $entries = [];

	private function __construct(){
	}

	/**
	 * Function getInstance
	 * @return PlayerSettingsManager
	 */
	public static function getInstance(): PlayerSettingsManager{
		return self::$instance ?? self::$instance = new PlayerSettingsManager();
	}

	/**
	 * Function getSettingsEntry
	 * @param string $controller
	 * @return null|PlayerSettingsEntry
	 */
	public static function getSettingsEntry(string $controller): ?PlayerSettingsEntry{
		return PlayerSettingsManager::$entries[mb_strtolower($controller)] ?? null;
	}

	/**
	 * Function isEntry
	 * @param string $controller
	 * @return bool
	 */
	public static function isEntry(string $controller): bool{
		return isset(PlayerSettingsManager::$entries[mb_strtolower($controller)]);
	}

	/**
	 * Function getEntries
	 * @return array
	 */
	public static function getEntries(): array{
		return self::$entries;
	}

	public static function registerSettingsEntry(PlayerSettingsEntry $entry): void{
		if (isset(PlayerSettingsManager::$entries[mb_strtolower($entry->getController())]))
			throw new InvalidArgumentException("Player settings entry with identifier '" . $entry->getController() . "' already exists.");

		PlayerSettingsManager::$entries[mb_strtolower($entry->getController())] = $entry;
	}

	public static function isSettingsEntry(string $controller): bool{
		return isset(PlayerSettingsManager::$entries[mb_strtolower($controller)]);
	}

	public static function unregisterSettingsEntry(string $controller): void{
		if (!isset(PlayerSettingsManager::$entries[mb_strtolower($controller)]))
			throw new InvalidArgumentException("Controller '$controller' is not registered");

		unset(PlayerSettingsManager::$entries[mb_strtolower($controller)]);
	}
}
