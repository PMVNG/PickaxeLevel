<?php

namespace DavidGlitch04\PMVNGPickaxe\item;

use DavidGlitch04\PMVNGPickaxe\Pickaxe;
use pocketmine\item\Item;
use pocketmine\player\Player;

class PickaxeManager{

    protected Pickaxe $pickaxe;

    public function __construct()
    {
        $this->pickaxe = Pickaxe::getInstance();
    }

    
	public function getPickaxeName(Player $player) {
		$username = $player->getName();
		$name = "§l§a⚒§b PMVNG PICKAXE §6 §r§l[§cLevel: §b " . $this->pickaxe->getProvider()->getData($player)["Level"] . " §r§l]§a§l " . $username;
		return $name;
	}

	public function getPickaxeLore(Player $player) {
		$player = $player->getName();
		$lore = "§b§l⇲ Thông Tin:\n§e§lChiếc Cúp Được Rèn Từ\n§e§l§cMột Vị Thần tài Giỏi Đã Chiến Thắng §eCuộc Thời Chiến Tranh\n§e§l✦ §6Cậu Đã Triệu Hồi Ta?, Thế Cậu Đã sẵn Sàng Đối Đầu Chưa?\n\n§9§l↦ §bChủ Nhân: §a" . $player . "!";
		return $lore;
	}

	public function isPMVNGPickaxe(Item $item) : bool {
		return $item->getNamedTag()->getTag("PMVNGPickaxe") !== null;
	}
}