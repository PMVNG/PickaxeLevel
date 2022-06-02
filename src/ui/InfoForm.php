<?php

namespace DavidGlitch04\PMVNGPickaxe\ui;

use DavidGlitch04\PMVNGPickaxe\Pickaxe;
use pocketmine\player\Player;
use Vecnavium\FormsUI\SimpleForm;

class InfoForm{

    protected Pickaxe $pickaxe;

    public function __construct(Player $player)
    {
        $this->pickaxe = Pickaxe::getInstance();
        $this->openForm($player);
    }

    private function openForm(Player $player): void{
		$form = new SimpleForm(function (Player $player, int|null $data) {
			if (!isset($data)) {
				return;
			}
		});
		$type = $this->pickaxe->getConfig()->get("Type");
		$form->setTitle($this->pickaxe->getConfig()->get("Title"));
		$form->setContent($this->pickaxe->getConfig()->get("Contentinfo"));
		$form->addButton($this->pickaxe->getConfig()->get("ButtonBACK"), $type, $this->pickaxe->getConfig()->get("PNGBACK"));
		$player->sendForm($form);
        return;
    }
}