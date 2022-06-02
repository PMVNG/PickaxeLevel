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
		$form->setTitle("§6§lTOP PICKAXE");
		arsort($all);
		$i = 1;
		foreach($all as $name => $level){
			$form->addLabel("§l§bHạng §e".$i."§b thuộc về §c".$name."§f Với §e".$level." §cCấp\n");
			if($i >= 10) break;
			++$i;
		}
		$player->sendForm($form);
        return;
    }
}