<?php

namespace DavidGlitch04\PMVNGPickaxe\ui;

use DavidGlitch04\PMVNGPickaxe\Pickaxe;
use pocketmine\player\Player;
use Vecnavium\FormsUI\CustomForm;

class TopForm{

    protected Pickaxe $pickaxe;

    public function __construct(Player $player)
    {
        $this->pickaxe = Pickaxe::getInstance();
        $this->openForm($player);
    }

    private function openForm(Player $player): void{
        $all = $this->pickaxe->getProvider()->getAllUsers();
		$form = new CustomForm(function($player, array|null $data){
			if(!isset($data)) {
                return;
            }
		});
		$form->setTitle($this->pickaxe->getConfig()->getNested("TopForm.title"));
		arsort($all);
		$i = 1;
		foreach($all as $name => $level){
            $search = [
                "{top}",
                "{username}",
                "{level}"
            ];
            $level = $all[$name]["Level"];
            $replace = [
                $i,
                $name,
                $level
            ];
            $label = str_replace($search, $replace, $this->pickaxe->getConfig()->getNested("TopForm.label"));
			$form->addLabel($label."\n");
			if($i >= 10) break;
			++$i;
		}
		$player->sendForm($form);
        return;
    }
}