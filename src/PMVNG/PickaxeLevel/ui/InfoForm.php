<?php

declare(strict_types=1);

namespace PMVNG\PickaxeLevel\ui;

use PMVNG\PickaxeLevel\Pickaxe;
use pocketmine\player\Player;
use Vecnavium\FormsUI\SimpleForm;

class InfoForm {

	protected Pickaxe $pickaxe;

	public function __construct(Player $player) {
		$this->pickaxe = Pickaxe::getInstance();
		$this->openForm($player);
	}

	private function openForm(Player $player): void {
		$form = new SimpleForm(function (Player $player, int|null $data) {
			if (!isset($data)) {
				return;
			}
		});
		$type = $this->pickaxe->getConfig()->get("Type");
		$form->setTitle($this->pickaxe->getConfig()->getNested("InfoForm.title"));
		$form->setContent($this->pickaxe->getConfig()->getNested("InfoForm.content"));
		$form->addButton($this->pickaxe->getConfig()->getNested("InfoForm.button_back"), $type, $this->pickaxe->getConfig()->getNested("InfoForm.png_back"));
		$player->sendForm($form);
		return;
	}
}
