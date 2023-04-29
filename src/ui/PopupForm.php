<?php

namespace PMVNG\PickaxeLevel\ui;

use PMVNG\PickaxeLevel\Pickaxe;
use pocketmine\player\Player;
use Vecnavium\FormsUI\CustomForm;

class PopupForm {

	protected Pickaxe $pickaxe;

	public function __construct(Player $player) {
		$this->pickaxe = Pickaxe::getInstance();
		$this->openForm($player);
	}

	private function openForm(Player $player): void {
		$form = new CustomForm(function (Player $player, array|null $data) {
			if (!isset($data)) {
				new MainForm($player);
			}
			$this->pickaxe->getProvider()->setStatusPopup($player, boolval($data[0]));
		});
		$form->setTitle("§6§lPoppup Pickaxe");
		$form->addToggle("§1§l↣ §aKéo sang phải để bật", false);
		$player->sendForm($form);
		return;
	}
}
