<?php
/*
 * Copyright (c) Jan Sohn / xxAROX
 * All rights reserved.
 * I don't want anyone to use my source code without permission.
 */

declare(strict_types=1);
namespace HQGames\logger;
/**
 * Class LoggerEntry
 * @package HQGames\logger
 * @author Jan Sohn / xxAROX
 * @date 24. July, 2022 - 15:49
 * @ide PhpStorm
 * @project Bridge
 */
class LoggerEntry{
	protected string $webhook_name;
	protected string $message;
	protected array $params = [];

	/**
	 * Function new
	 * @param string $webhook_name
	 * @param string $message
	 * @param array $params
	 * @return static
	 */
	static function new(string $webhook_name, string $message, array $params = []): self{
		$self = new self;
		$self->webhook_name = $webhook_name;
		$self->message = $message;
		$self->params = $params;
		return $self;
	}

	/**
	 * LoggerEntry constructor.
	 * @param string $message
	 * @param array $params
	 */
	private function __construct(){
	}

	/**
	 * Function getMessage
	 * @return string
	 */
	public function getMessage(): string{
		return $this->message;
	}

	/**
	 * Function getParams
	 * @return array
	 */
	public function getParams(): array{
		return $this->params;
	}
}
