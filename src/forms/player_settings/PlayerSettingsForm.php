<?php
/*
 * Copyright (c) Jan Sohn / xxAROX
 * All rights reserved.
 * I don't want anyone to use my source code without permission.
 */
declare(strict_types=1);
namespace HQGames\forms\player_settings;
use HQGames\Core\player\Player;
use HQGames\forms\elements\Element;
use HQGames\forms\types\CustomForm;
use HQGames\forms\types\CustomFormResponse;
use HQGames\player\settings\PlayerSettingsEntry;


/**
 * Class PlayerSettingsForm
 * @package HQGames\forms\player_settings
 * @author Jan Sohn / xxAROX
 * @date 24. July, 2022 - 18:01
 * @ide PhpStorm
 * @project Bridge
 */
class PlayerSettingsForm extends CustomForm{
	protected Player $player;

	public function __construct(Player $player){
		$this->player = $player;
		parent::__construct("%forms.player-settings.title", array_map(function (PlayerSettingsEntry $entry): Element{
				return $entry->getFormElement();
			}, $player->getSettingsEntries()), function (Player $player, CustomFormResponse $response): void{
		},);
	}
}
