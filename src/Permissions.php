<?php
/*
 * Copyright (c) Jan Sohn / xxAROX
 * All rights reserved.
 * I don't want anyone to use my source code without permission.
 */

declare(strict_types=1);
namespace HQGames;
use InvalidArgumentException;
use JetBrains\PhpStorm\Pure;
use pocketmine\permission\DefaultPermissions;
use pocketmine\permission\Permission;
use pocketmine\permission\PermissionManager;
use pocketmine\utils\SingletonTrait;


/**
 * Class Permissions
 * @package HQGames
 * @author Jan Sohn / xxAROX
 * @date 22. July, 2022 - 22:01
 * @ide PhpStorm
 * @project Bridge
 */
class Permissions{
	use SingletonTrait{
		setInstance as private static;
		reset as private;
	}
	/** @var Permission[] */
	private array $permissions = [];

	private function __construct(){
		self::setInstance($this);
		$this->registerPermissions();
	}

	private function registerPermissions(): void{
	}

	/**
	 * Function registerPermission
	 * @param Permission $permission
	 * @return void
	 */
	public function registerPermission(Permission $permission): void{
		if ($this->isRegistered($permission->getName())) throw new InvalidArgumentException("Permission '{$permission->getName()}' is already registered");
		$this->permissions[mb_strtolower($permission->getName())] = $permission;
		$opRoot = PermissionManager::getInstance()->getPermission(DefaultPermissions::ROOT_OPERATOR);
		PermissionManager::getInstance()->addPermission($permission);
		$opRoot->addChild($permission->getName(), true);
	}

	/**
	 * Function unregisterPermission
	 * @param Permission $permission
	 * @return void
	 */
	public function unregisterPermission(Permission $permission): void{
		if (!$this->isRegistered($permission->getName())) throw new InvalidArgumentException("Permission '{$permission->getName()}' is not registered");
		unset($this->permissions[mb_strtolower($permission->getName())]);
		PermissionManager::getInstance()->removePermission($permission);
	}

	/**
	 * Function isRegistered
	 * @param Permission|string $name
	 * @return bool
	 */
	#[Pure] public function isRegistered(Permission|string $name): bool{
		return isset($this->permissions[mb_strtolower(($name instanceof Permission ? $name->getName() : $name))]);
	}

	/**
	 * Function getPermission
	 * @param string $name
	 * @return null|Permission
	 */
	#[Pure] public function getPermission(string $name): ?Permission{
		if (!$this->isRegistered($name)) return null;
		return $this->permissions[mb_strtolower($name)] ?? null;
	}

	/**
	 * Function getPermissions
	 * @return Permission[]
	 */
	public function getPermissions(): array{
		return $this->permissions;
	}
}
