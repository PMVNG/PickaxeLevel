<?php

namespace DavidGlitch04\PMVNGPickaxe\ui;

use DavidGlitch04\PMVNGPickaxe\Pickaxe;
use pocketmine\player\Player;
use TungstenVn\Clothes\libs\jojoe77777\FormAPI\CustomForm;
use Vecnavium\FormsUI\SimpleForm;

class AdminForm{

    protected Pickaxe $pickaxe;

    public function __construct(Player $player)
    {
        $this->pickaxe = Pickaxe::getInstance();
        $this->openForm($player);
    }

    private function openForm(Player $player): void{
		$form = new CustomForm(function (Player $player, array|null $data) {
			if (!isset($data)) {
				return;
			}
			if (!isset($data[0]) || !isset($data[1]) || !isset($data[2])) {
				$player->sendMessage("§cVui lòng nhập đầy đủ thông tin!");
				return false;
			}
			if (!is_numeric($data[0]) || !is_numeric($data[1]) || !is_numeric($data[2])) {
				$player->sendMessage("§cThông tin phải là số!");
				return false;
			}
			$this->pickaxe->pic->set(($player->getName()), [
				"Exp" => $data[1],
				"Level" => $data[0],
				"NextExp" => $data[2],
				"Popup" => true
			]);
			$this->pickaxe->pic->save();
		});
		$form->setTitle("§c§lAdmin Pickaxe");
		$form->addInput("§1§l↣ §aLevel:", "0");
		$form->addInput("§1§l↣ §aExp:", "0");
		$form->addInput("§1§l↣ §aNextExp:", "0");
		$player->sendForm($form);
		return;
    }
}