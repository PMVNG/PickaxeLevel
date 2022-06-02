<?php

namespace DavidGlitch04\PMVNGPickaxe\ui;

use DavidGlitch04\PMVNGPickaxe\Pickaxe;
use pocketmine\player\Player;
use Vecnavium\FormsUI\SimpleForm;

class MainForm{

    protected Pickaxe $pickaxe;

    public function __construct(Player $player)
    {
        $this->pickaxe = Pickaxe::getInstance();
        $this->openForm($player);
    }

    private function openForm(Player $player): void{
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
		$form->setTitle($this->pickaxe->getConfig()->get("Title"));
		$form->setContent($this->pickaxe->getConfig()->get("Content"));
		$form->addButton($this->pickaxe->getConfig()->get("ButtonINFO"), $type, $this->pickaxe->getConfig()->get("PNGINFO"));
		$form->addButton($this->pickaxe->getConfig()->get("ButtonTOP"), $type, $this->pickaxe->getConfig()->get("PNGTOP"));
		$form->addButton($this->pickaxe->getConfig()->get("ButtonPOPUP"), $type, $this->pickaxe->getConfig()->get("PNGPOPUP"));
		$player->sendForm($form);
        return;
    }
}