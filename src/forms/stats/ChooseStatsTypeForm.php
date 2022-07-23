<?php
/*
 * Copyright (c) Jan Sohn / xxAROX
 * All rights reserved.
 * I don't want anyone to use my source code without permission.
 */

declare(strict_types=1);
namespace HQGames\forms\stats;
use Frago9876543210\EasyForms\elements\FunctionalButton;
use Frago9876543210\EasyForms\forms\MenuForm;


/**
 * Class ChooseStatsTypeForm
 * @package HQGames\forms\stats
 * @author Jan Sohn / xxAROX
 * @date 23. July, 2022 - 20:38
 * @ide PhpStorm
 * @project Bridge
 */
class ChooseStatsTypeForm extends MenuForm{
	public function __construct(){
		parent::__construct(
			"%forms.stats.title.choose",
			"%forms.stats.text.choose",
			[
				new FunctionalButton()
			]
		);
	}
}
