<?php

declare(strict_types=1);

namespace DavidGlitch04\PMVNGPickaxe\listener;

use DavidGlitch04\PMVNGPickaxe\Pickaxe;
use pocketmine\console\ConsoleCommandSender;
use pocketmine\data\bedrock\EnchantmentIdMap;
use pocketmine\event\block\BlockBreakEvent;
use pocketmine\event\Listener;
use pocketmine\event\player\PlayerChatEvent;
use pocketmine\event\player\PlayerItemHeldEvent;
use pocketmine\event\player\PlayerJoinEvent;
use pocketmine\item\enchantment\EnchantmentInstance;
use pocketmine\item\VanillaItems;
use pocketmine\Server;
use function in_array;

class EventListener implements Listener {
	protected Pickaxe $plugin;

	private array $list = [
		56 => 4,
		14 => 3,
		15 => 4,
		16 => 2,
		41 => 6,
		42 => 6,
		57 => 7,
		129 => 6,
		133 => 8
	]; // TODO: Use ItemIds on array_keys

	public function __construct(Pickaxe $plugin) {
		$this->plugin = $plugin;
	}

	public function onJoin(PlayerJoinEvent $event) {
		$player = $event->getPlayer();
		if(!$this->plugin->getProvider()->isRegistered($player)){
			$this->plugin->getProvider()->registerUser($player);
		}
	}

	public function onChat(PlayerChatEvent $event) {
		$player = $event->getPlayer();
		$msg = $event->getMessage();
		$config = $this->plugin->getConfig();
		if ($config->get("givePickaxe_chat", true)) {
			if ($config->get('msg-Give', '!givepickaxe') == $msg) {
				$event->cancel();
				$this->plugin->getPickaxeMgr()->addPickaxe($player);
				$player->sendMessage("§a§lNhận Cúp Thành Công!");
			}
		}
	}

	public function onHeld(PlayerItemHeldEvent $event) {
		$p = $event->getPlayer();
		$item = $p->getInventory()->getItemInHand();
		if ($this->plugin->onCheck($item)) {
			if (in_array($this->plugin->getLevel($p), [2, 4, 6, 8, 10, 12, 14, 16, 18, 20, 22, 24, 26, 28, 30, 32, 34, 36, 38, 40, 42, 44, 46, 48, 50, 52, 54, 56, 58, 60, 62, 64, 66, 68, 70, 72, 74, 76, 78, 80, 82, 84, 86, 88, 90, 92, 94, 96, 98, 100, 102, 104, 106, 108, 108, 110, 112, 114, 116, 118, 120, 122, 124, 126, 128, 130, 132, 134, 136, 138, 140, 142, 144, 146, 148, 150, 152, 154, 156, 158, 160, 162, 164, 166, 168, 170, 172, 174, 176, 178, 180, 182, 184, 186, 188, 190, 192, 194, 196, 198, 200, 202, 204, 206, 208, 208, 210, 212, 214, 216, 218, 220, 222, 224, 226, 228, 230, 232, 234, 236, 238, 240, 242, 244, 246, 248, 250, 252, 254, 256, 258, 260, 262, 264, 266, 268, 270, 272, 274, 276, 278, 280, 282, 284, 286, 288, 290, 292, 294, 296, 298, 300, 302, 304, 306, 308, 308, 310, 312, 314, 316, 318, 320, 322, 324, 326, 328, 330, 332, 334, 336, 338, 340, 342, 344, 346, 348, 350, 352, 354, 356, 358, 360, 362, 364, 366, 368, 370, 372, 374, 376, 378, 380, 382, 384, 386, 388, 390, 392, 394, 396, 398, 400, 402, 404, 406, 408, 408, 410, 412, 414, 416, 418, 420, 422, 424, 426, 428, 430, 432, 434, 436, 438, 440, 442, 444, 446, 448, 450, 452, 454, 456, 458, 460, 462, 464, 466, 468, 470, 472, 474, 476, 478, 480, 482, 484, 486, 488, 490, 492, 494, 496, 498, 500], true)) {
				$id = 15;
				$lv = $this->plugin->getLevel($p) / 2.5;
				$item->addEnchantment(new EnchantmentInstance(EnchantmentIdMap::getInstance()->fromId($id), $lv));
			}
			$p->getInventory()->setItemInHand($item);
			$sender = new ConsoleCommandSender(Server::getInstance(), Server::getInstance()->getLanguage());
			switch ($this->plugin->getLevel($p)) {
				case 50:
					$this->plugin->addCE($sender, "Energizing", 1, $p->getName());
				break;
				case 100:
					$this->plugin->addCE($sender, "Jackpot", 1, $p->getName());
				break;
				case 150:
					$this->plugin->addCE($sender, "Energizing", 2, $p->getName());
				break;
				case 200:
					$this->plugin->addCE($sender, "Jackpot", 2, $p->getName());
				break;
				case 250:
					$this->plugin->addCE($sender, "Haste", 1, $p->getName());
				break;
				case 300:
					$this->plugin->addCE($sender, "Jackpot", 3, $p->getName());
				break;
			}
		}
	}

	public function onBreak(BlockBreakEvent $event) {
		$player = $event->getPlayer();
		$item = $player->getInventory()->getItemInHand();
		$id = $event->getBlock()->getId();
		$data = $this->plugin->getProvider()->getData($player);
		if ($event->isCancelled()) {
			return;
		}
		if ($this->plugin->onCheck($item)) {
			if (in_array($id, $this->list, true)) {
				$this->plugin->getProvider()->addExp($player, $this->list[$id]);
			}
			if ($data["Popup"]) {
				$player->sendPopup("§e§l⎳ §dCÚP: §b§l❖ §bPMVNG §e✪§9PICKAXE§e✪ §e⚒\n§c§l ⊱ §bKinh Nghiệm:§a " . $data["Exp"] . "§3/§a" . $data["NextExp"] . " §c| §bCấp Cúp: §a" . $data["Level"]);
			}
			if ($data["Exp"] >= $data["NextExp"]) {
				$this->plugin->setLevel($player, $data["Level"] + 1);
				$player->sendMessage("§e§l❖§6Level Cúp§e: " . $this->plugin->getLevel($player) . "!");
				$player->sendTitle("§a❖§l§9 Lên cấp§e " . $this->plugin->getLevel($player));
				// TODO: Rewards
			}
		}
	}
}