<?php

declare(strict_types=1);


namespace DavidGlitch04\PMVNGPickaxe;

use DaPigGuy\PiggyCustomEnchants\PiggyCustomEnchants;
use DavidGlitch04\PMVNGPickaxe\listener\EventListener;
use DavidGlitch04\PMVNGPickaxe\utils\SingletonTrait;
use pocketmine\command\Command;
use pocketmine\command\CommandSender;
use pocketmine\console\ConsoleCommandSender;
use pocketmine\event\Listener;
use pocketmine\item\Item;
use pocketmine\player\Player;
use pocketmine\plugin\PluginBase;
use pocketmine\utils\Config;
use function array_shift;
use function arsort;
use function ceil;
use function count;
use function is_numeric;
use function max;
use function min;

class Pickaxe extends PluginBase implements Listener {

	const KEY_VALUE = "Level";

	use SingletonTrait;

	protected PluginBase $li, $CE, $score, $eco, $form;

	protected Config $pic;

	protected function onEnable() : void {
		self::setInstance($this);
		$this->getServer()->getPluginManager()->registerEvents(new EventListener($this), $this);
		$this->saveDefaultConfig();
		$this->pic = new Config($this->getDataFolder() . "pickaxe.yml", Config::YAML);
		$task = new Score($this);
		$this->getScheduler()->scheduleRepeatingTask($task, 20);
		$this->initDepend();
	}

	protected function initDepend(): void{
		$this->li = $this->getServer()->getPluginManager()->getPlugin("LockedItem");
		$this->CE = $this->getServer()->getPluginManager()->getPlugin("PiggyCustomEnchants");
		if ($this->li == null) { //LockedItem
			$this->getLogger()->notice("PMVNG Pickaxe > You have not installed LockedItem, please download it at https://poggit.pmmp.io/p/LockedItem/3.0.0 and then try again.");
			$this->getServer()->getPluginManager()->disablePlugin($this);
		}
		if ($this->CE == null) { //PiggyCustomEnchant
			$this->getLogger()->notice("PMVNG Pickaxe > You have not installed PiggyCustomEnchants, please download it and then try again.");
			$this->getServer()->getPluginManager()->disablePlugin($this);
		}
	}
	public function onCommand(CommandSender $sender, Command $cmd, String $label, Array $args) : bool {
		///command /pickaxe
		if ($cmd->getName() == "pickaxe") {
			if ($sender instanceof Player) {
				$this->MainForm($sender);
			} else {
				$this->getLogger()->error($this->getConfig()->get("Console-CMD"));
			}
		}
		//Admin
		if ($cmd->getName() == "adminpickaxe") {
			if ($this->getServer()->isOp($sender->getName())) {
				$sender->sendMessage("§cYou can't use this command!");
			} else {
				$this->AdminForm($sender);
			}
		}
		if ($cmd->getName() == "toppickaxe") {
			$levelplot = $this->pic->getAll();
			$max = 0;
			$max = count($levelplot);
			$max = ceil(($max / 5));

			$message = "";
			$message1 = "";

			$page = array_shift($args);
			$page = max(1, $page);
			$page = min($max, $page);
			$page = (int) $page;

			$aa = $this->pic->getAll();
			arsort($aa);
			$i = 0;

			foreach ($aa as $b => $a) {
				if (($page - 1) * 5 <= $i && $i <= ($page - 1) * 5 + 4) {
					$i1 = $i + 1;
					$c = $this->pic->get($b)["Level"];
					$trang = "§l§c⚒§6 Xếp Hạng Cấp Cúp §a " . $page . "§f/§a" . $max . "§c ⚒\n";
					$message .= "§l§bHạng §e" . $i1 . "§b thuộc về §c" . $b . "§f Với §e" . $c . " §cCấp\n";
					$message1 .= "§l§bHạng §e" . $i1 . "§b thuộc về §c" . $b . "§f Với §e" . $c . " §cCấp\n";
				}
				$i++;
			}
			$form = $this->form->createCustomForm(function (Player $s, $data) use ($trang, $message) {
				if ($data === null) {
					return $this->MainForm($s);
				}
				$this->getServer()->dispatchCommand($s, "toppickaxe " . $data[1]);
			});
			$form->setTitle("§6§lTOP PICKAXE");
			$form->addLabel($trang . $message);
			$form->addInput("§1§l↣ §aNext Page", "0");
			$form->sendToPlayer($sender);
		}
		return true;
	}
	public function getPickaxeName($player) {
		if ($player instanceof Player) {
			$player = $player->getName();
		}
		$name = "§l§a⚒§b PMVNG PICKAXE §6 §r§l[§cLevel: §b " . $this->pic->get($player)["Level"] . " §r§l]§a§l " . $player;
		return $name;
	}

	//Lore Pickaxe Level
	public function getPickaxeLore($player) {
		if ($player instanceof Player) {
			$player = $player->getName();
		}
		$lore = "§b§l⇲ Thông Tin:\n§e§lChiếc Cúp Được Rèn Từ\n§e§l§cMột Vị Thần tài Giỏi Đã Chiến Thắng §eCuộc Thời Chiến Tranh\n§e§l✦ §6Cậu Đã Triệu Hồi Ta?, Thế Cậu Đã sẵn Sàng Đối Đầu Chưa?\n\n§9§l↦ §bChủ Nhân: §a" . $player . "!";
		return $lore;
	}

	//Set Pickaxe Level
	public function setPickaxe(Item $item) : Item {
		$item->getNamedTag()->setString("Pickaxe", self::KEY_VALUE);
		return $item;
	}

	//Check Pickaxe Level
	public function onCheck(Item $item) : bool {
		return $item->getNamedTag()->getTag("Pickaxe") !== null;
	}

	//getExp player
	public function getExp($player) {
		if ($player instanceof Player) {
			$player = $player->getName();
			if (!$this->pic->exists($player)) {
				$exp = 0;
				return $exp;
			} else {
				$exp = $this->pic->get($player)["Exp"];
				return $exp;
			}
		}
	}

	//getNextExp player
	public function getNextExp($player) {
		if ($player instanceof Player) {
			$player = $player->getName();
			if (!$this->pic->exists($player)) {
				$nexp = 0;
				return $nexp;
			} else {
				$nexp = $this->pic->get($player)["NextExp"];
				return $nexp;
			}
		}
	}

	//get Level player
	public function getLevel($player) {
		if ($player instanceof Player) {
			$player = $player->getName();
			if (!$this->pic->exists($player)) {
				$lv = 0;
				return $lv;
			} else {
				$lv = $this->pic->get($player)["Level"];
				return $lv;
			}
		}
	}

	//addExp for player
	public function addExp($player, $xp) {
		if ($player instanceof Player) {
			$player = $player->getName();
			$current = $this->pic->get($player)["Exp"];
			$currentlv = $this->pic->get($player)["Level"];
			$currentne = $this->pic->get($player)["NextExp"];
			$currentpopup = $this->pic->get($player)["Popup"];
			$this->pic->set(($player), [
				"Exp" => $current + $xp,
				"Level" => $currentlv,
				"NextExp" => $currentne,
				"Popup" => $currentpopup
			]);
			$this->pic->save();
		}
	}

	//set level next
	public function setLevel($player, $level) {
		if ($player instanceof Player) {
			$name = $player->getName();
			$nextexp = ($this->getLevel($player) + 1) * 120;
			$currentpopup = $this->pic->get($player->getName())["Popup"];
			$this->pic->set(($name), ["Exp" => 0, "Level" => $level, "NextExp" => $nextexp, "Popup" => $currentpopup]);
			$this->pic->save();
		}
	}

	//add piggycustomenchants
	public function addCE(CommandSender $sender, $enchantment, $level, $target) {
		$plugin = $this->CE;
		if ($plugin instanceof PiggyCustomEnchants) {
			if (!is_numeric($level)) {
				$level = 1;
				$sender->sendMessage("Level must be numerical. Setting level to 1.");
			}
			$target == null ? $target = $sender : $target = $this->getServer()->getPlayerByPrefix($target);
			if (!$target instanceof Player) {
				if ($target instanceof ConsoleCommandSender) {
					$sender->sendMessage("Please provide a player.");
					return;
				}
				$sender->sendMessage("Invalid player.");
				return;
			}
			$target->getInventory()->setItemInHand($plugin->addEnchantment($target->getInventory()->getItemInHand(), $enchantment, $level, $sender->hasPermission("piggycustomenchants.overridecheck") ? false : true, $sender));
		}
	}
}
##------------------------------------[END]--------------------------------------------------
