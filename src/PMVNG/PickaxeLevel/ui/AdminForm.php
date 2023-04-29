<?php

declare(strict_types=1);

namespace PMVNG\PickaxeLevel\ui;

use PMVNG\PickaxeLevel\Pickaxe;
use pocketmine\player\Player;
use Vecnavium\FormsUI\CustomForm;

class AdminForm {

	protected Pickaxe $pickaxe;

	public function __construct(Player $player) {
		$this->pickaxe = Pickaxe::getInstance();
		$this->openForm($player);
	}

	private function openForm(Player $player): void {
		$form = new CustomForm(function (Player $player, array|null $data) {
			if (!isset($data)) {
				return;
			}
			if (!isset($data[0]) || !isset($data[1]) || !isset($data[2])) {
				$player->sendMessage("§cVui lòng nhập đầy đủ thông tin!");
				return;
			}
			if (!is_numeric($data[0]) || !is_numeric($data[1]) || !is_numeric($data[2])) {
				$player->sendMessage("§cThông tin phải là số!");
				return;
			}
			$this->pickaxe->getProvider()->setData($player, [
				"Level" => $data[0],
				"Exp" => $data[1],
				"NextExp" => $data[2],
				"Popup" => false
			]);
		});
		$form->setTitle("§c§lAdmin Pickaxe");
		$form->addInput("§1§l↣ §aLevel:", "0");
		$form->addInput("§1§l↣ §aExp:", "0");
		$form->addInput("§1§l↣ §aNextExp:", "0");
		$player->sendForm($form);
		return;
	}
}
