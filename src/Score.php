<?php

declare(strict_types=1);

namespace DavidGlitch04\PMVNGPickaxe;

use pocketmine\scheduler\Task;
use pocketmine\Server;
use ScoreMC\ScoreMC;

class Score extends Task {
	public function onRun(int $tick) : void {
		foreach (Server::getInstance()->getOnlinePlayers() as $player) {
			if ($player->getLevel()->getName() == "sb") {
				$lv = Pickaxe::getInstance()->getLevel($player);
				$xp = Pickaxe::getInstance()->getExp($player);
				$nxp = Pickaxe::getInstance()->getNextExp($player);
				ScoreMC::getInstance()->createScore($player, '§l§ePICKAXE LEVEL');
				ScoreMC::getInstance()->setScoreLines($player, ["", "§c§l⊱ §bCấp Cúp: §a {$lv}", "§c§l⊱ §bKinh Nghiệm:§a {$xp}§3/§a{$nxp}", "", "§fIP: §ewww.example.com"]);
			}
		}
	}
}