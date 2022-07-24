<?php
/*
 * Copyright (c) Jan Sohn / xxAROX
 * All rights reserved.
 * I don't want anyone to use my source code without permission.
 */
declare(strict_types=1);
namespace HQGames\player\settings;
use HQGames\forms\elements\Button;
use HQGames\forms\elements\Dropdown;
use HQGames\forms\elements\Element;
use HQGames\forms\elements\Image;
use HQGames\forms\elements\Input;
use HQGames\forms\elements\Label;
use HQGames\forms\elements\Slider;
use HQGames\forms\elements\StepSlider;
use HQGames\forms\elements\Toggle;
use HQGames\Core\player\Player;
use HQGames\Permissions;
use InvalidArgumentException;
use JetBrains\PhpStorm\ArrayShape;
use JetBrains\PhpStorm\Pure;
use JsonSerializable;


/**
 * Class PlayerSettingsEntry
 * @package HQGames\player\settings
 * @author Jan Sohn / xxAROX
 * @date 24. July, 2022 - 16:47
 * @ide PhpStorm
 * @project Bridge
 */
class PlayerSettingsEntry implements JsonSerializable{
	public string $controller;
	public ?string $permission = null;
	public Element|Dropdown|Slider|StepSlider|Label|Toggle|Input|Button $form_element;
	public mixed $default;
	public mixed $value;

	/**
	 * Function create
	 * @param string $controller
	 * @param Element $form_element
	 * @param null|string|int|float|bool $default
	 * @param null|string $permission
	 * @return static
	 */
	public static function create(string $controller, Element $form_element, null|string|int|float|bool $default, ?string $permission = null): self{
		$self = new self;
		$self->controller = $controller;
		$self->form_element = $form_element;
		if (is_null($default)) $default = $form_element->getDefault();
		if (is_null($form_element->getDefault())) $form_element->setDefault($default);
		$self->default = $default;
		$self->permission = $permission;
		return $self;
	}

	/**
	 * Function fromArray
	 * @param array $data
	 * @return static
	 */
	static function fromArray(array $data): self{
		if (!isset($data["controller"])) throw new InvalidArgumentException("Missing controller");
		if (!isset($data["form_element"])) throw new InvalidArgumentException("Missing form_element");
		$entry = new self;
		$entry->controller = $data["controller"];
		$entry->default = $data["default"] ?? null;
		if (is_string($data["permission"]) && !Permissions::isRegistered($data["permission"])) throw new InvalidArgumentException("Permission {$data["permission"]} not registered");
		$entry->permission = $data["permission"] ?? null;
		$entry->form_element = $entry->elementFromArray($data["form_element"]);
		return $entry;
	}

	/**
	 * Function hasPermission
	 * @param Player $player
	 * @return bool
	 */
	public function hasPermission(Player $player): bool{
		return $this->permission === null || $player->hasPermission($this->permission);
	}

	/**
	 * Function getForPlayer
	 * @param Player $player
	 * @return Element
	 */
	public function getElementForPlayer(Player $player): Element{
		$element = clone $this->form_element;
		$element->setLocked($this->hasPermission($player));
		return $element;
	}

	/**
	 * Function elementFromArray
	 * @param array $_data
	 * @return Element
	 */
	private function elementFromArray(array $_data): Element{
		$class = $_data["class"];
		if (!class_exists($class) || !is_subclass_of($class, Element::class)) throw new InvalidArgumentException("Class $class is not a subclass of Element");
		$data = $_data["form_element"] ?? [];
		return match ($class) {
			Input::class => new Input($_data["name"], $data["placeholder"] ?? "", $data["default"] ?? ""),
			Dropdown::class => new Dropdown($_data["name"], $data["options"] ?? [], $data["default"] ?? ""),
			StepSlider::class => new StepSlider($_data["name"], $data["options"] ?? [], $data["default"] ?? ""),
			Slider::class => new Slider($_data["name"], (float)($data["min"] ?? 0), (float)($data["max"] ?? 100), (float)($data["step"] ?? 1.0), (float)($data["default"] ?? 0)),
			Toggle::class => new Toggle($_data["name"], (bool)($data["default"] ?? false)),
			Label::class => new Label($_data["name"]),
			Button::class => new Button($_data["name"], null, (isset($data["image"]) ? new Image($data["image"]["data"], $data["image"]["type"] ?? Image::TYPE_URL) : null)),
			default => new Label("%raw.error§f:   §f"),
		};
	}

	#[ArrayShape([ "controller" => "string", "permission" => "null|string", "form_element" => "array" ])]
	public function jsonSerialize(): array{
		return [
			"controller"   => $this->controller,
			"permission"   => $this->permission,
			"form_element" => array_merge($this->form_element->jsonSerialize(), [ "class" => get_class($this->form_element) ]),
		];
	}

	/**
	 * Function getController
	 * @return string
	 */
	public function getController(): string{
		return $this->controller;
	}

	/**
	 * Function getFormElement
	 * @return Element
	 */
	public function getFormElement(): Element{
		return $this->form_element;
	}

	/**
	 * Function getPermission
	 * @return ?string
	 */
	public function getPermission(): ?string{
		return $this->permission;
	}
}
