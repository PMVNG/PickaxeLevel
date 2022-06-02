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
use pocketmine\item\enchantment\VanillaEnchantments;
use pocketmine\item\ItemIds;
use pocketmine\Server;
use function in_array;

class EventListener implements Listener {
	protected Pickaxe $plugin;

	private array $list = [
		ItemIds::DIAMOND_ORE => 4,
		ItemIds::IRON_ORE => 3,
		ItemIds::GOLD_ORE => 4,
		ItemIds::COAL_ORE => 2,
		ItemIds::GOLD_BLOCK => 6,
		ItemIds::IRON_BLOCK => 6,
		ItemIds::DIAMOND_BLOCK => 7,
		ItemIds::EMERALD_ORE => 6,
		ItemIds::EMERALD_BLOCK => 8
	];

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

	public function onBreak(BlockBreakEvent $event) {
		$player = $event->getPlayer();
		$item = $player->getInventory()->getItemInHand();
		$id = $event->getBlock()->getId();
		$data = $this->plugin->getProvider()->getData($player);
		if ($event->isCancelled()) {
			return;
		}
		if ($this->plugin->getPickaxeMgr()->isPMVNGPickaxe($item)) {
			if (isset($this->list[$id])) {
				$this->plugin->getProvider()->addExp($player, $this->list[$id]);
			} else{
				$this->plugin->getProvider()->addExp($player, 2);
			}
			if ($data["Popup"]) {
				$player->sendPopup("§e§l⎳ §dCÚP: §b§l❖ §bPMVNG §e✪§9PICKAXE§e✪ §e⚒\n§c§l ⊱ §bKinh Nghiệm:§a " . $data["Exp"] . "§3/§a" . $data["NextExp"] . " §c| §bCấp Cúp: §a" . $data["Level"]);
			}
			if (intval($data["Exp"]) >= intval($data["NextExp"])) {
				$this->plugin->getProvider()->LevelUP($player);
				$player->sendMessage("§e§l❖§6Level Cúp§e: " . $data["Level"] + 1 . "!");
				$player->sendTitle("§a❖§l§9 Lên cấp§e " . $data["Level"] + 1);
				if(($data["Level"] +1) % 2 == 0){
					$efficiency = VanillaEnchantments::EFFICIENCY();
					$level = (int) $data["Level"] / 2.5;
					$item->addEnchantment(new EnchantmentInstance($efficiency, intval($level)));
					$player->getInventory()->setItemInHand($item);
				}
				// TODO: Rewards
			}
		}
	}
}