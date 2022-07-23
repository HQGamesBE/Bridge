<?php
/*
 * Copyright (c) Jan Sohn / xxAROX
 * All rights reserved.
 * I don't want anyone to use my source code without permission.
 */

declare(strict_types=1);
namespace HQGames\player\locker;
use HQGames\Bridge\player\BridgePlayer;
use InvalidArgumentException;
use JsonSerializable;
use pocketmine\block\BlockFactory;
use pocketmine\block\BlockIdentifier;
use pocketmine\item\Item;
use pocketmine\item\ItemFactory;
use pocketmine\item\ItemIdentifier;
use pocketmine\utils\Filesystem;


/**
 * Class PlayerLockerEntry
 * @package HQGames\player\locker
 * @author Jan Sohn / xxAROX
 * @date 23. July, 2022 - 21:00
 * @ide PhpStorm
 * @project Bridge
 */
class PlayerLockerEntry implements JsonSerializable{
	private const SEPARATOR = '⁽¦⁽';
	const         TYPE_ITEM = 0;
	const         TYPE_BLOCK = 1;

	static function createFromKey(string $key, $owned = true): self{
		$ex = explode(self::SEPARATOR, $key);
		$category = array_shift($ex);
		$description = array_shift($ex);
		$price = intval(array_shift($ex));
		$activated = boolval(array_shift($ex));
		$type = intval(array_shift($ex));
		$args = $ex;
		$object = match ($type) {
			default => throw new InvalidArgumentException("Object '$type' is not supported yet"),
			self::TYPE_ITEM => new ItemIdentifier(intval($args[0]), intval($args[1])),
			self::TYPE_BLOCK => new BlockIdentifier(intval($args[0]), intval($args[1])),
		};
		return new self($object, $price, $owned, $activated, $description, $category);
	}

	/**
	 * PlayerLockerEntry constructor.
	 * @param object $object
	 * @param int $price
	 * @param bool $owned
	 * @param bool $activated
	 * @param null|string $description
	 * @param null|string $category
	 */
	public function __construct(private object $object, private int $price, private bool $owned, private bool $activated, private ?string $description = null, public ?string $category = null){
		if (!$this->owned && $this->activated) {
			$this->activated = false;
		}
	}

	/**
	 * Function purchase
	 * @param BridgePlayer $player
	 * @return void
	 */
	public function purchase(BridgePlayer $player): void{
		if ($this->owned) {
			return;
		}
		if ($this->price == 0) {
			$this->owned = true;
			return;
		}
		if ($player->getCoins() >= $this->price) {
			$player->removeCurrentWindow();
			$player->sendForm(new PurchaseForm($this->name(), $this->price, function ($player): void{
				$this->owned = $this->activated = true;
				(new PlayerLockerInventory($this->category))->send($player);
			}));
		}
	}

	public function sell(BridgePlayer $seller, ?BridgePlayer $customer, ?int $amount): void{
		if ($this->price > 0) {
			return;
		}
		if (is_null($amount)) {
			$amount = intval(round($this->price / 2));
		}
		if ($amount > $this->price) {
			$amount = $this->price;
		}
		if (is_null($customer)) {
			$seller->sendForm(new ConfirmationForm("Sell {$this->name()} for {$amount}", function (BridgePlayer $player): void{
			}, function (BridgePlayer $player): void{
			}));
		}
		/* TODO: sell requests:
		 * 	1. $seller send sell request, at $customer will open a form, IMPORTANT: only works if Bridge::getState() == ServerStatePacket::STATE_LOBBY
		 * 	2. $seller gets cooldown for $customer, if he deny the request
		 */
	}

	public function gift(BridgePlayer $owner, BridgePlayer $target): void{
		if (($thisEntryFromTarget = $target->getLocker()->fromKey($this->key()))->isOwned()) {
			$owner->sendMessage("%message.locker.player.already.own", [$target->getNickname(), $this->name()]);
			return;
		}
		$thisEntryFromTarget->owned = true;
		$this->owned = false;
		$target->sendMessage("%message.locker.player.gift", [$owner->getNickname(), $this->name()]);
	}

	/**
	 * Function key
	 * @param bool $includeActive
	 * @return string
	 */
	public function key(bool $includeActive = false): string{
		return implode(self::SEPARATOR, $this->jsonSerialize($includeActive));
	}

	/**
	 * Function description
	 * @return string
	 */
	public function description(): string{
		return $this->description ?? "";
	}

	/**
	 * Function name
	 * @return string
	 */
	public function name(): string{
		$o = $this->object;
		return match (true) {
			default => throw new InvalidArgumentException("Object '" . Filesystem::cleanPath(get_class($o)) . "' is not supported yet"),
			$o instanceof ItemIdentifier => ItemFactory::getInstance()->get($o->getId(), $o->getMeta(), 1)->getVanillaName(),
			$o instanceof BlockIdentifier => BlockFactory::getInstance()->get($o->getBlockId(), $o->getVariant())->getName(),
		};
	}

	/**
	 * Function getObject
	 * @return object
	 */
	public function getObject(): object{
		$o = $this->object;
		return match (true) {
			default => throw new InvalidArgumentException("Object '" . Filesystem::cleanPath(get_class($o)) . "' is not supported yet"),
			$o instanceof ItemIdentifier => ItemFactory::getInstance()->get($o->getId(), $o->getMeta(), 1),
			$o instanceof BlockIdentifier => BlockFactory::getInstance()->get($o->getBlockId(), $o->getVariant()),
		};
	}

	/**
	 * Function getObjectAsItem
	 * @return Item
	 */
	public function getObjectAsItem(): Item{
		$o = $this->object;
		return match (true) {
			default => throw new InvalidArgumentException("Object '" . Filesystem::cleanPath(get_class($o)) . "' is not supported yet"),
			$o instanceof ItemIdentifier => ItemFactory::getInstance()->get($o->getId(), $o->getMeta(), 1),
			$o instanceof BlockIdentifier => BlockFactory::getInstance()->get($o->getBlockId(), $o->getVariant())->asItem(),
		};
	}

	/**
	 * Function getPrice
	 * @return int
	 */
	public function getPrice(): int{
		return $this->price;
	}

	/**
	 * Function getOwned
	 * @return bool
	 */
	public function isOwned(): bool{
		return $this->owned;
	}

	/**
	 * Function setOwned
	 * @param bool $value
	 * @return void
	 */
	public function setOwned(bool $value): void{
		$this->owned = $value;
	}

	/**
	 * Function isActivated
	 * @return bool
	 */
	public function isActivated(): bool{
		return $this->activated;
	}

	/**
	 * Function setActivated
	 * @param bool $value
	 * @return void
	 */
	public function setActivated(bool $value): void{
		$this->activated = $value;
	}

	/**
	 * Function jsonSerialize
	 * @param null|bool $includeActive
	 * @return array
	 */
	public function jsonSerialize(?bool $includeActive = true): array{
		$o = $this->object;
		return array_merge([
			$this->category,
			$this->description,
			$this->price,
			($includeActive ? $this->activated : false),
		], match (true) {
			default => throw new InvalidArgumentException("PlayerLockerEntry::jsonSerialize object '" . Filesystem::cleanPath(get_class($o)) . "' is not supported yet"),
			$o instanceof ItemIdentifier => [self::TYPE_ITEM, $o->getId(), $o->getMeta()],
			$o instanceof BlockIdentifier => [self::TYPE_BLOCK, $o->getBlockId(), $o->getVariant()],
		});
	}
}
