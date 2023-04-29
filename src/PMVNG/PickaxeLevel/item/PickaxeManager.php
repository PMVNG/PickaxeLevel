<?php

namespace PMVNG\PickaxeLevel\item;

use PMVNG\PickaxeLevel\Pickaxe;
use pocketmine\item\Item;
use pocketmine\item\VanillaItems;
use pocketmine\player\Player;

class PickaxeManager {

	protected Pickaxe $pickaxe;

	public function __construct() {
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

	public function addPickaxe(Player $player): void {
		$item = VanillaItems::DIAMOND_PICKAXE();
		$item->setCustomName($this->getPickaxeName($player));
		$item->setLore(array($this->getPickaxeLore($player)));
		$item->getNamedTag()->setString("PickaxeLevel", $player->getName());
		$this->pickaxe->lockeditem->setLocked($item);
		$player->getInventory()->addItem($item);
	}

	public function isPickaxeLevel(Item $item): bool {
		return $item->getNamedTag()->getTag("PickaxeLevel") !== null;
	}
}
