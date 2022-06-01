<?php

namespace DavidGlitch04\PMVNGPickaxe;

use pocketmine\scheduler\Task;
use pocketmine\utils\TextFormat;
use pocketmine\Server;
use ScoreMC\ScoreMC;
use pocketmine\level\Level;
use pocketmine\Player;

class Score extends Task{

	public function onRun(int $tick) : void{
		foreach(Server::getInstance()->getOnlinePlayers() as $player){
			if($player->getLevel()->getName() == "sb"){
				$lv = Pickaxe::getInstance()->getLevel($player);
				$xp = Pickaxe::getInstance()->getExp($player);
				$nxp = Pickaxe::getInstance()->getNextExp($player);
				ScoreMC::getInstance()->createScore($player, '§l§ePICKAXE LEVEL');
				ScoreMC::getInstance()->setScoreLines($player, ["", "§c§l⊱ §bCấp Cúp: §a {$lv}", "§c§l⊱ §bKinh Nghiệm:§a {$xp}§3/§a{$nxp}", "", "§fIP: §ewww.example.com"]);
			}
		}
	}
}