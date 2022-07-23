<?php
/*
 * Copyright (c) Jan Sohn / xxAROX
 * All rights reserved.
 * I don't want anyone to use my source code without permission.
 */

declare(strict_types=1);
namespace HQGames\player\locker;
use pocketmine\block\BlockIdentifier;
use pocketmine\block\BlockLegacyIds;
use pocketmine\item\ItemIdentifier;
use pocketmine\item\ItemIds;


/**
 * Class PlayerLocker
 * @package HQGames\player\locker
 * @author Jan Sohn / xxAROX
 * @date 23. July, 2022 - 20:59
 * @ide PhpStorm
 * @project Bridge
 */
class PlayerLocker implements \JsonSerializable{
	const CATEGORY_STICK           = '%locker.stick';
	const CATEGORY_BLOCK           = '%locker.block';
	const CATEGORY_ITEM_PROJECTILE = '%locker.item_projectile';

	private function init(): void{
		#Sticks:
		$this->register(self::CATEGORY_STICK, new PlayerLockerEntry(new ItemIdentifier(ItemIds::STICK, 0), 0, true, true));
		$this->register(self::CATEGORY_STICK, new PlayerLockerEntry(new ItemIdentifier(ItemIds::BLAZE_ROD, 0), 100, false, false));
		#Blocks:
		$this->register(self::CATEGORY_BLOCK, new PlayerLockerEntry(new BlockIdentifier(BlockLegacyIds::SANDSTONE, 0), 0, true, true));
		#Item-Projectile:
		$this->register(self::CATEGORY_ITEM_PROJECTILE, new PlayerLockerEntry(new ItemIdentifier(ItemIds::IRON_AXE, 0), 0, true, true));
	}

	/** @var PlayerLockerEntry[][] */
	private array $categories = [];

	/**
	 * PlayerLocker constructor.
	 * @param array $lockerData
	 */
	public function __construct(array $lockerData){
		$this->init();
		$this->sync($lockerData);
	}

	/**
	 * Function getEntry
	 * @param string $category
	 * @return PlayerLockerEntry
	 */
	public function getEntry(string $category): PlayerLockerEntry{
		if (!isset($this->categories[$category])) {
			throw new InvalidArgumentException("Category '$category' is not supported yet");
		}
		$forCategory = [];
		foreach ($this->categories[$category] as $entry) {
			if ($entry->isActivated()) {
				$forCategory[] = $entry;
			}
		}
		if (count($forCategory) == 1) {
			return $forCategory[0];
		}
		return $forCategory[array_rand($forCategory)];
	}

	/**
	 * Function getEntries
	 * @param string $category
	 * @return PlayerLockerEntry[]
	 */
	public function getEntries(string $category): array{
		if (!isset($this->categories[$category])) {
			throw new InvalidArgumentException("Category '$category' is not supported yet");
		}
		return $this->categories[$category];
	}

	/**
	 * Function toSaveFormat
	 * @return array
	 */
	public function toSaveFormat(): array{
		$arr = [];
		foreach ($this->getAllEntries() as $entry) {
			$arr[] = $entry->key();
		}
		return $arr;
	}

	/**
	 * Function sync
	 * @param array $keys
	 * @return void
	 */
	private function sync(array $keys): void{
		foreach ($keys as $key) {
			$entry = $this->fromKey($key);
			if (is_null($entry)) {
				$entry = PlayerLockerEntry::createFromKey($key);
				$this->register($entry->category, $entry);
			}
		}
	}

	/**
	 * Function getAllEntries
	 * @return PlayerLockerEntry[]
	 */
	public function getAllEntries(): array{
		return array_merge(...array_values($this->categories));
	}

	/**
	 * Function fromKey
	 * @param string $key
	 * @return null|PlayerLockerEntry
	 */
	public function fromKey(string $key): ?PlayerLockerEntry{
		/** @var PlayerLockerEntry $entry */
		foreach (array_merge(...array_values($this->categories)) as $entry) {
			if ($entry->key() === $key) {
				return $entry;
			} else if ($entry->key(true) === $key) {
				return $entry;
			}
		}
		return null;
	}

	/**
	 * Function jsonSerialize
	 * @return array
	 */
	public function jsonSerialize(): array{
		$arr = [];
		foreach ($this->categories as $category => $entries) {
			$arr[$category] = array_map(fn(PlayerLockerEntry $entry) => $entry->key(), $entries);
		}
		return $arr;
	}

	/**
	 * Function register
	 * @param string $category
	 * @param PlayerLockerEntry $entry
	 * @return void
	 */
	private function register(string $category, PlayerLockerEntry $entry): void{
		$entry->category = $category;
		if (!isset($this->categories[mb_strtolower($category)])) {
			$this->categories[mb_strtolower($category)] = [];
		}
		$this->categories[mb_strtolower($category)][] = $entry;
	}

	/**
	 * Function getCategoryNames
	 * @return array
	 */
	static function getCategoryNames(): array{
		return array_values((new ReflectionClass(PlayerLocker::class))->getConstants(ReflectionClassConstant::IS_PUBLIC));
	}
}
