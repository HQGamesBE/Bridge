<?php
/*
 * Copyright (c) Jan Sohn / xxAROX
 * All rights reserved.
 * I don't want anyone to use my source code without permission.
 */
declare(strict_types=1);
namespace HQGames\Bridge\utils;
use InvalidArgumentException;
use pocketmine\Server;
use pocketmine\utils\Config;
use pocketmine\utils\SingletonTrait;


/**
 * Class BackendProperties
 * @package HQGames\Bridge\utils
 * @author Jan Sohn / xxAROX
 * @date 11. July, 2022 - 17:45
 * @ide PhpStorm
 * @project Bridge
 */
final class BackendProperties{
	use SingletonTrait {
		setInstance as private;
		reset as private;
	}


	private Config $config;

	private ?string $identifier = null;
	private ?string $template = null;
	private ?string $display_name = null;
	private ?string $image = null;
	private ?string $authToken = null;
	private ?string $backend_address = null;
	private ?int $backend_port = null;

	/**
	 * BackendProperties constructor.
	 */
	private function __construct(){
		$this->config = new Config(Server::getInstance()->getDataPath() . "backend.json", Config::JSON, [
			"AuthToken"       => "TOKEN HERE",
			"backend_address" => "IPv4 ADDRESS HERE",
			"backend_port"    => -1,
		]);
		$this->load();
	}

	/**
	 * Function load
	 * @return void
	 */
	private function load(): void{
		$this->authToken = $this->config->get("AuthToken", null);
		$this->backend_address = $this->config->get("backend_address", null);
		$this->backend_port = intval($this->config->get("backend_port", -1));
		$this->identifier = $this->config->get("identifier", null);
		$this->template = $this->config->get("template", null);
		$this->display_name = $this->config->get("display_name", null);
		$this->image = $this->config->get("image", null);

		$this->validate();
	}

	/**
	 * Function reload
	 * @return void
	 */
	public function reload(): void{
		$this->config->reload();
		$this->load();
	}

	public function overwriteIdentifier(string $identifier): void{
		$this->identifier = $identifier;
		$this->config->set("identifier", $identifier);
		$this->config->save();
	}

	/**
	 * Function validate
	 * @return void
	 */
	public function validate(): void{
		$error = match (true) {
			default => null,
			is_null($this->authToken) || str_contains($this->authToken, " HERE")							=> "AuthToken is not set",
			is_null($this->backend_address) || str_contains($this->backend_address, " HERE")		=> "backend_address is not set",
			is_null($this->backend_port) => "backend_port is not set",
			is_null($this->identifier) => "identifier is not set",
			is_null($this->template) => "template is not set",
			is_null($this->display_name) => "display_name is not set",
			($this->backend_port <= 0 || $this->backend_port > 65535) => "Port is out of range",
		};
		if (!is_null($error)) throw new InvalidArgumentException($error);
	}

	/**
	 * Function getBackendAddress
	 * @return ?string
	 */
	public function getBackendAddress(): ?string{
		return $this->backend_address;
	}

	/**
	 * Function getBackendPort
	 * @return ?int
	 */
	public function getBackendPort(): ?int{
		return $this->backend_port;
	}

	/**
	 * Function getIdentifier
	 * @return ?string
	 */
	public function getIdentifier(): ?string{
		return $this->identifier;
	}

	/**
	 * Function getDisplayName
	 * @return ?string
	 */
	public function getDisplayName(): ?string{
		return $this->display_name;
	}

	/**
	 * Function getImage
	 * @return ?string
	 */
	public function getImage(): ?string{
		return $this->image;
	}

	/**
	 * Function getAuthToken
	 * @return ?string
	 */
	public function getAuthToken(): ?string{
		return $this->authToken;
	}

	/**
	 * Function getTemplate
	 * @return ?string
	 */
	public function getTemplate(): ?string{
		return $this->template;
	}
}
