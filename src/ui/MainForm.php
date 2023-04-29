<?php

namespace DavidGlitch04\PMVNGPickaxe\ui;

use DavidGlitch04\PMVNGPickaxe\Pickaxe;
use pocketmine\player\Player;
use Vecnavium\FormsUI\SimpleForm;

class MainForm {

	protected Pickaxe $pickaxe;

	public function __construct(Player $player) {
		$this->pickaxe = Pickaxe::getInstance();
		$this->openForm($player);
	}

	private function openForm(Player $player): void {
		$form = new SimpleForm(function (Player $player, int|null $data) {
			$result = $data;
			if (!isset($data)) {
				return;
			}
			switch ($result) {
				case 0:
					new InfoForm($player);
					break;
				case 1:
					new TopForm($player);
					break;
				case 2:
					new PopupForm($player);
					break;
			}
		});
		$type = $this->pickaxe->getConfig()->get("Type");
		$form->setTitle($this->pickaxe->getConfig()->getNested("MainForm.title"));
		$form->setContent($this->pickaxe->getConfig()->getNested("MainForm.content"));
		$form->addButton($this->pickaxe->getConfig()->getNested("MainForm.button_info"), $type, $this->pickaxe->getConfig()->getNested("MainForm.png_info"));
		$form->addButton($this->pickaxe->getConfig()->getNested("MainForm.button_top"), $type, $this->pickaxe->getConfig()->getNested("MainForm.png_top"));
		$form->addButton($this->pickaxe->getConfig()->getNested("MainForm.button_popup"), $type, $this->pickaxe->getConfig()->getNested("MainForm.png_popup"));
		$player->sendForm($form);
		return;
	}
}
