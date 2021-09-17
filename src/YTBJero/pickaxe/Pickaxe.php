<?php 

namespace YTBJero\pickaxe;

//Essentials Class
use pocketmine\plugin\PluginBase;
use pocketmine\command\{CommandSender, Command, ConsoleCommandSender};
use pocketmine\event\player\{PlayerJoinEvent, PlayerChatEvent, PlayerItemHeldEvent};
use pocketmine\{Player, Server};
use pocketmine\network\mcpe\protocol\{LevelSoundEventPacket, LevelEventPacket};
use pocketmine\item\enchantment\{Enchantment, EnchantmentInstance};
use pocketmine\event\block\BlockBreakEvent;
use pocketmine\utils\Config;
use pocketmine\item\Item;
use pocketmine\block\Block;
use pocketmine\nbt\tag\StringTag;
use pocketmine\event\Listener;
use onebone\economyapi\EconomyAPI;
use DaPigGuy\PiggyCustomEnchants\Main as CE;
class Pickaxe extends PluginBase implements Listener{

	const KEY_VALUE = "Level";

	private static $instance;

//Enable
	public function onEnable(){
		self::$instance = $this;
		$this->getServer()->getPluginManager()->registerEvents($this, $this);

		$this->getServer()->loadLevel("sb");
		$this->saveDefaultConfig();

		$this->pic = new Config($this->getDataFolder()."pickaxe.yml", Config::YAML);
		$this->li = $this->getServer()->getPluginManager()->getPlugin("LockedItem");
		$this->CE =  $this->getServer()->getPluginManager()->getPlugin("PiggyCustomEnchants");
		$this->score =  $this->getServer()->getPluginManager()->getPlugin("ScoreMC");
		$this->eco =  $this->getServer()->getPluginManager()->getPlugin("EconomyAPI");
		$this->form =  $this->getServer()->getPluginManager()->getPlugin("FormAPI");

		//ScoreBoard Task
		$task = new Score($this);
		$this->getScheduler()->scheduleRepeatingTask($task, 20);

		//Check Plugin
		if($this->li == null){ //LockedItem 
			$this->getLogger()->notice("PMVNG Pickaxe > You have not installed LockedItem, please download it at https://poggit.pmmp.io/p/LockedItem/3.0.0 and then try again.");
			$this->getServer()->getPluginManager()->disablePlugin($this);
		}
		if($this->CE == null){ //PiggyCustomEnchant
			$this->getLogger()->notice("PMVNG Pickaxe > You have not installed PiggyCustomEnchants, please download it and then try again.");
			$this->getServer()->getPluginManager()->disablePlugin($this);
		}
		if($this->score == null){ //ScoreMC
			$this->getLogger()->notice("PMVNG Pickaxe > You have not installed ScoreMC, please download it and then try again.");
			$this->getServer()->getPluginManager()->disablePlugin($this);
		}
		if($this->eco == null){ //EconomyAPI
			$this->getLogger()->notice("PMVNG Pickaxe > You have not installed EconomyAPI, please download it and then try again.");
			$this->getServer()->getPluginManager()->disablePlugin($this);
		}
		if($this->form == null){ //FormAPI
			$this->getLogger()->notice("PMVNG Pickaxe > You have not installed FormAPI, please download it and then try again.");
			$this->getServer()->getPluginManager()->disablePlugin($this);
		}
	}

	//get Main
	public static function getInstance() : self
    {
        return self::$instance;
    }

    //Event Join
	public function onJoin(PlayerJoinEvent $ev){
		$user = $ev->getPlayer()->getName();
		if(!$this->pic->exists($user)){
			$this->pic->set(($user), ["Exp" => 0, "Level" => 1, "NextExp" => 200, "Popup" => false]);
			$this->pic->save();
		}
	}

	//Chat give pic
	public function onChat(PlayerChatEvent $ev){
		$p = $ev->getPlayer();
		$msg = $ev->getMessage();
		$nameitem = $this->getPickaxeName($p);
		$loreitem = $this->getPickaxeLore($p);
		if($this->getConfig()->get("GivePickaxe-MSG") == true){
		if($msg == $this->getConfig()->get("Msg-Give")){
			$ev->setCancelled(true);
			$p->sendMessage("§a§lNhận Cúp Thành Công!");
							$volume = mt_rand();
			    					$p->getlevel()->broadcastLevelSoundEvent($p, LevelSoundEventPacket::SOUND_LEVELUP, (int) $volume);
			$item = Item::get(745, 0, 1);
			$item->setCustomName($nameitem);
			$item->setLore(array($loreitem));
			$this->setPickaxe($item);
			$this->li->setLocked($item);
			$p->getInventory()->addItem($item);
		}
	}
}

	//Item Event
	public function onHeld(PlayerItemHeldEvent $ev){
		$p = $ev->getPlayer();
		$item = $p->getInventory()->getItemInHand();
		if($this->onCheck($item)){
		if(in_array($this->getLevel($p), array(10, 20))){
            }

            //add enchantment efficiency
            if(in_array($this->getLevel($p), array(2, 4, 6, 8, 10, 12, 14, 16, 18, 20, 22, 24, 26, 28, 30, 32, 34, 36, 38, 40, 42, 44, 46, 48, 50, 52, 54, 56, 58, 60, 62, 64, 66, 68, 70, 72, 74, 76, 78, 80, 82, 84, 86, 88, 90, 92, 94, 96, 98, 100, 102, 104, 106, 108, 108, 110, 112, 114, 116, 118, 120, 122, 124, 126, 128, 130, 132, 134, 136, 138, 140, 142, 144, 146, 148, 150, 152, 154, 156, 158, 160, 162, 164, 166, 168, 170, 172, 174, 176, 178, 180, 182, 184, 186, 188, 190, 192, 194, 196, 198, 200, 202, 204, 206, 208, 208, 210, 212, 214, 216, 218, 220, 222, 224, 226, 228, 230, 232, 234, 236, 238, 240, 242, 244, 246, 248, 250, 252, 254, 256, 258, 260, 262, 264, 266, 268, 270, 272, 274, 276, 278, 280, 282, 284, 286, 288, 290, 292, 294, 296, 298, 300, 302, 304, 306, 308, 308, 310, 312, 314, 316, 318, 320, 322, 324, 326, 328, 330, 332, 334, 336, 338, 340, 342, 344, 346, 348, 350, 352, 354, 356, 358, 360, 362, 364, 366, 368, 370, 372, 374, 376, 378, 380, 382, 384, 386, 388, 390, 392, 394, 396, 398, 400, 402, 404, 406, 408, 408, 410, 412, 414, 416, 418, 420, 422, 424, 426, 428, 430, 432, 434, 436, 438, 440, 442, 444, 446, 448, 450, 452, 454, 456, 458, 460, 462, 464, 466, 468, 470, 472, 474, 476, 478, 480, 482, 484, 486, 488, 490, 492, 494, 496, 498, 500))){
                $id = 15;
                $lv = $this->getLevel($p)/2.5;
                $item->addEnchantment(new EnchantmentInstance(Enchantment::getEnchantment($id), $lv));

                //add enchantment fortune
            }/**elseif(in_array($this->getLevel($p), array(51, 53, 55, 57, 59, 61, 63, 65, 67, 69, 71, 73, 75, 77, 79, 81, 83, 85, 87, 89, 91, 93, 95, 97, 99, 101, 103, 105, 107, 109, 111, 113, 115, 117, 119, 121, 123, 125, 127, 129, 131, 133, 135, 137, 139, 141, 143, 145, 147, 149, 151, 153, 155, 157, 159, 161, 163, 165, 167, 169, 171, 173, 175, 179, 181, 183, 185, 187, 189, 191, 193, 195, 197, 199, 201, 203, 205, 207, 209, 211, 213, 215, 217, 219, 221, 223, 225, 227, 229, 231, 233, 235, 237, 239, 241, 243, 245, 247, 249, 251, 253, 255, 257, 259, 261, 263, 265, 267, 269, 271, 273, 275, 279, 281, 283, 285, 287, 289, 291, 293, 295, 297, 299, 301, 303, 305, 307, 309, 311, 313, 315, 317, 319, 321, 323, 325, 327, 329, 331, 333, 335, 337, 339, 341, 343, 345, 347, 349, 351, 353, 355, 357, 359, 361, 363, 365, 367, 369, 371, 373, 375, 379, 381, 383, 385, 387, 389, 391, 393, 395, 397, 399, 401, 403, 405, 407, 409, 411, 413, 415, 417, 419, 421, 423, 425, 427, 429, 431, 433, 435, 437, 439, 441, 443, 445, 447, 449, 451, 453, 455, 457, 459, 461, 463, 465, 467, 469, 471, 473, 475, 479, 481, 483, 485, 487, 489, 491, 493, 495, 497, 499))){
                    $id = 18;
                    $lv = $this->getLevel($p)/3;
                    $item->addEnchantment(new EnchantmentInstance(Enchantment::getEnchantment($id), $lv));
                }**/

                //add customenchants
            $p->getInventory()->setItemInHand($item);
			switch($this->getLevel($p)){
				case 50:
					$this->addCE(new ConsoleCommandSender(), "Energizing", 1, $p->getName());
				break;
				case 100:
					$this->addCE(new ConsoleCommandSender(), "Jackpot", 1, $p->getName());
				break;
				case 150:
					$this->addCE(new ConsoleCommandSender(), "Energizing", 2, $p->getName());
				break;
                case 200:
					$this->addCE(new ConsoleCommandSender(), "Jackpot", 2, $p->getName());
				break;	
                case 250:
					$this->addCE(new ConsoleCommandSender(), "Haste", 1, $p->getName());
				break;	
                case 300:
					$this->addCE(new ConsoleCommandSender(), "Jackpot", 3, $p->getName());
				break;
        }
    }
}

	//BreakBlock Popup
	public function onBreak(BlockBreakEvent $ev){
		$player = $ev->getPlayer();
		$item = $player->getInventory()->getItemInHand();
		$id = $ev->getBlock()->getId();
		$n = $this->pic->get($player->getName());
		$damage = $item->getDamage();
		//Check Pickaxe
		if($this->onCheck($item)){
			if($damage > 30){
						 $item->setDamage(0);
             		           $player->getInventory()->setItemInHand($item);
		                     $player->sendMessage("§6⚒§e Cúp của bạn đã được sửa chữa!");
		                 }
			//If cancelled
			if($ev->isCancelled()){
				return false;
			} else{
				//Break Block add Exp
				switch ($id) {
					case 56:
                       $this->addExp($player, 4);
                       break;
                   case 14:
                       $this->addExp($player, 3);
                       break;
                   case 15:
                       $this->addExp($player, 4);
                       break;
                   case 16:
                       $this->addExp($player, 2);
                       break;
                  case 87:
                       $this->addExp($player, 0);
                       break;
                   case 129:
                       $this->addExp($player, 6);
                       break;
                   case 133:
                       $this->addExp($player, 8);
                       break;
                   case 57:
                       $this->addExp($player, 7);
                   case 42:
                       $this->addExp($player, 6);
                   case 41:
                       $this->addExp($player, 6);
                       break;
                   default:
                       $this->addExp($player, 2);
                       break;				
				}
				//If SendPopup is true, send Popup for player
				if($this->pic->get($player->getName())["Popup"] == true){
					$player->sendPopup("§e§l⎳ §dCÚP: §b§l❖ §bPMVNG §e✪§9PICKAXE§e✪ §e⚒\n§c§l ⊱ §bKinh Nghiệm:§a ".$n["Exp"]."§3/§a".$n["NextExp"]." §c| §bCấp Cúp: §a".$n["Level"]);
				}
				//if exp >= nextexp
				if($this->getExp($player) >= $this->getNextExp($player)){
					$this->setLevel($player, $this->getLevel($player) + 1);
					$player->sendMessage("§e§l❖§6Level Cúp§e: ".$this->getLevel($player)."!");
					$player->addTitle("§a❖§l§9 Lên cấp§e ".$this->getLevel($player));
					$volume = mt_rand();
			    	$player->getlevel()->broadcastLevelSoundEvent($player, LevelSoundEventPacket::SOUND_LEVELUP, (int) $volume);
			    	EconomyAPI::getInstance()->addMoney($player, $this->getLevel($player)*1000);
				}
			}
		}
	}

//Commands
	public function onCommand(CommandSender $s, Command $cmd, String $label, Array $args): bool 
	{
		///command /pickaxe
		if($cmd->getName() == "pickaxe"){
			if($s instanceof Player){
				$this->MainForm($s);
			} else{
				$this->getLogger()->error($this->getConfig()->get("Console-CMD"));
			}
		}
		//Admin
		if($cmd->getName() == "adminpickaxe"){
			if(!$s->isOp()){
				$s->sendMessage("§cYou can't use this command!");
			} else{
				$this->AdminForm($s);
			}
		}
		if($cmd->getName() == "toppickaxe"){
			$levelplot = $this->pic->getAll();
			$max = 0;
			$max = count($levelplot);
			$max = ceil(($max / 5));
			
			$message = "";
			$message1 = "";
			
			$page = array_shift($args);
			$page = max(1, $page);
			$page = min($max, $page);
			$page = (int)$page;
			
			$aa = $this->pic->getAll();
			arsort($aa);
			$i = 0;
			
			foreach ($aa as $b=>$a) {
				if (($page - 1) * 5 <= $i && $i <= ($page - 1) * 5 + 4) {
					$i1 = $i + 1;
					$c = $this->pic->get($b)["Level"];
					$trang = "§l§c⚒§6 Xếp Hạng Cấp Cúp §a ".$page."§f/§a".$max."§c ⚒\n";
					$message .= "§l§bHạng §e".$i1."§b thuộc về §c".$b."§f Với §e".$c." §cCấp\n";
					$message1 .= "§l§bHạng §e".$i1."§b thuộc về §c".$b."§f Với §e".$c." §cCấp\n";
				} $i++;
			}
			$form = $this->form->createCustomForm(function (Player $s, $data) use ($trang, $message) {
				if ($data === null) { 
					return $this->MainForm($s); 
				}
				$this->getServer()->dispatchCommand($s, "toppickaxe ".$data[1]);
			});
			$form->setTitle("§6§lTOP PICKAXE");
			$form->addLabel($trang. $message);
			$form->addInput("§1§l↣ §aNext Page", "0");
			$form->sendToPlayer($s);
		}
		return true;
	}


	//MainForm
	public function MainForm(Player $player){
		$form = $this->form->createSimpleForm(function (Player $player, ?int $data = null){
			$result = $data;
			if($data === null){
				return false;
			}
			switch ($result) {
				case 0:
					$this->info($player);
					break;
				case 1:
				$this->getServer()->dispatchCommand($player, "toppickaxe");
				break;
				case 2:
				$this->popup($player);
				break;
			}
		});
		$type = $this->getConfig()->get("Type");
		$png1 = $this->getConfig()->get("PNGINFO");
		$png2 = $this->getConfig()->get("PNGTOP");
		$png3 = $this->getConfig()->get("PNGPOPUP");
		$form->setTitle($this->getConfig()->get("Title"));
		$form->setContent($this->getConfig()->get("Content"));
		$form->addButton($this->getConfig()->get("ButtonINFO"), $type, $png1);
		$form->addButton($this->getConfig()->get("ButtonTOP"), $type, $png2);
		$form->addButton($this->getConfig()->get("ButtonPOPUP"), $type, $png3);
		$form->sendToPlayer($player);
		return $form;
	}

	//Info Form
	public function info(Player $player){
		$form = $this->form->createSimpleForm(function (Player $player, ?int $data = null){
			$result = $data;
			if($data === null){
				return false;
			}
		});
		$type = $this->getConfig()->get("Type");
		$png = $this->getConfig()->get("PNGBACK");
		$form->setTitle($this->getConfig()->get("Title"));
		$form->setContent($this->getConfig()->get("Contentinfo"));
		$form->addButton($this->getConfig()->get("ButtonBACK"), $type, $png);
		$form->sendToPlayer($player);
	}
	//Admin Form

	public function AdminForm(Player $player){
		$form = $this->form->createCustomForm(function (Player $player, $data){
			if($data == null){
				return false;
			}
			if($data[0] == null || $data[1] == null || $data[2] == null){
				$player->sendMessage("§cVui lòng nhập đầy đủ thông tin!");
				return false;
			}
			if(!is_numeric($data[0]) || !is_numeric($data[1]) || !is_numeric($data[2])){
				$player->sendMessage("§cThông tin phải là số!");
				return false;
			}
			$this->pic->set(($player->getName()), [
				"Exp" => $data[1],
				"Level" => $data[0],
				"NextExp" => $data[2],
				"Popup" => true
			]);
			$this->pic->save();
		});
		$form->setTitle("§c§lAdmin Pickaxe");
		$form->addInput("§1§l↣ §aLevel:", "0");
		$form->addInput("§1§l↣ §aExp:", "0");
		$form->addInput("§1§l↣ §aNextExp:", "0");
		$form->sendToPlayer($player);
		return $form;
	}
//Popup Form
	public function popup(Player $player){
		$form = $this->form->createCustomForm(function (Player $player, $data){
			if($data === null){
				return $this->MainForm($player);
			}
			if($data[0] == true){
				$current = $this->pic->get($player->getName())["Exp"];
			$currentlv = $this->pic->get($player->getName())["Level"];
			$currentne = $this->pic->get($player->getName())["NextExp"];
			$this->pic->set(($player->getName()), [
				"Exp" => $current,
				"Level" => $currentlv,
				"NextExp" => $currentne,
				"Popup" => true
			]);
			$this->pic->save();
			}
			if($data[0] == false){
				$current = $this->pic->get($player->getName())["Exp"];
			$currentlv = $this->pic->get($player->getName())["Level"];
			$currentne = $this->pic->get($player->getName())["NextExp"];
			$this->pic->set(($player->getName()), [
				"Exp" => $current,
				"Level" => $currentlv,
				"NextExp" => $currentne,
				"Popup" => false
			]);
			$this->pic->save();
			}
		});
		$form->setTitle("§6§lPoppup Pickaxe");
		$form->addToggle("§1§l↣ §aKéo sang phải để bật", false);
		$form->sendToPlayer($player);
		return $form;
	}

	//Name Pickaxe Level
	public function getPickaxeName($player){
		if($player instanceof Player){
				$player = $player->getName();
				}
				$name = "§l§a⚒§b PMVNG PICKAXE §6 §r§l[§cLevel: §b ".$this->pic->get($player)["Level"]." §r§l]§a§l ".$player;
			return $name;	
	}

	//Lore Pickaxe Level
	public function getPickaxeLore($player){
		if($player instanceof Player){
				$player = $player->getName();
				}
		$lore = "§b§l⇲ Thông Tin:\n§e§lChiếc Cúp Được Rèn Từ\n§e§l§cMột Vị Thần tài Giỏi Đã Chiến Thắng §eCuộc Thời Chiến Tranh\n§e§l✦ §6Cậu Đã Triệu Hồi Ta?, Thế Cậu Đã sẵn Sàng Đối Đầu Chưa?\n\n§9§l↦ §bChủ Nhân: §a".$player."!";
		return $lore;
	}

	//Set Pickaxe Level
	public function setPickaxe(Item $item) : Item {
		$item->setNamedTagEntry(new StringTag("Pickaxe", self::KEY_VALUE));
		return $item;
	}

	//Check Pickaxe Level
	public function onCheck(Item $item) : bool{
		return $item->getNamedTag()->hasTag("Pickaxe", StringTag::class);
	}

	//Reload Config
	public function reload()
    {
        $this->pic->reload();
        $this->reloadConfig();
        $this->saveDefaultConfig();
        $this->pic->save();
    }

    //getExp player
	public function getExp($player){
		if($player instanceof Player){
			$player = $player->getName();
			if(!$this->pic->exists($player)){
				$exp = 0;
				return $exp;
			} else{
				$exp = $this->pic->get($player)["Exp"];
				return $exp;
			}
		}
	}

	//getNextExp player
	public function getNextExp($player){
		if($player instanceof Player){
			$player = $player->getName();
			if(!$this->pic->exists($player)){
				$nexp = 0;
				return $nexp;
			} else{
				$nexp = $this->pic->get($player)["NextExp"];
				return $nexp;
			}
		}
	}

	//get Level player
	public function getLevel($player){
		if($player instanceof Player){
			$player = $player->getName();
			if(!$this->pic->exists($player)){
				$lv = 0;
				return $lv;
			} else{
				$lv = $this->pic->get($player)["Level"];
				return $lv;
			}		
		}
	}

	//addExp for player
	public function addExp($player, $xp){
		if($player instanceof Player){
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
	public function setLevel($player, $level){
		if($player instanceof Player){
			$name = $player->getName();
         $nextexp = ($this->getLevel($player)+1)*120;
         $currentpopup = $this->pic->get($player->getName())["Popup"];
          $this->pic->set(($name), ["Exp" => 0, "Level" => $level, "NextExp" => $nextexp, "Popup" => $currentpopup]);
          $this->pic->save();
      }
  }

  //add piggycustomenchants
	public function addCE(CommandSender $sender, $enchantment, $level, $target)
    {
        $plugin = $this->CE;
        if ($plugin instanceof CE) {
            if (!is_numeric($level)) {
                $level = 1;
                $sender->sendMessage("Level must be numerical. Setting level to 1.");
            }
            $target == null ? $target = $sender : $target = $this->getServer()->getPlayer($target);
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