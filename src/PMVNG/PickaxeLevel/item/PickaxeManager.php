<?php

declare(strict_types=1);

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

	public function getPickaxeName(Player $player): string {
		$username = $player->getName();
		$data = $this->pickaxe->getProvider()->getData($player);
		$level = $data["Level"] ?? "null";
		$name = "§l§a⚒§b PMVNG PICKAXE §6 §r§l[§cLevel: §b " . $level . " §r§l]§a§l " . $username;
		return $name;
	}

	public function getPickaxeLore(Player $player): string {
		$player = $player->getName();
		$lore = "§b§l⇲ Thông Tin:\n§e§lChiếc Cúp Được Rèn Từ\n§e§l§cMột Vị Thần tài Giỏi Đã Chiến Thắng §eCuộc Thời Chiến Tranh\n§e§l✦ §6Cậu Đã Triệu Hồi Ta?, Thế Cậu Đã sẵn Sàng Đối Đầu Chưa?\n\n§9§l↦ §bChủ Nhân: §a" . $player . "!";
		return $lore;
	}

	public function addPickaxe(Player $player): void {
		$item = VanillaItems::DIAMOND_PICKAXE();
		$item->setCustomName($this->getPickaxeName($player));
        $item->setLore(array($this->getPickaxeLore($player)));
		$item->getNamedTag()->setString("PickaxeLevel", $player->getName());
        if (Pickaxe::isLockedItem()) {
            $this->pickaxe->lockeditem->setLocked($item);
        }
		$player->getInventory()->addItem($item);
	}

	public function isPickaxeLevel(Item $item): bool {
		return $item->getNamedTag()->getTag("PickaxeLevel") !== null;
	}
}
