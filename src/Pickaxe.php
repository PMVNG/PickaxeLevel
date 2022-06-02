<?php

declare(strict_types=1);


namespace DavidGlitch04\PMVNGPickaxe;

use DaPigGuy\PiggyCustomEnchants\PiggyCustomEnchants;
use DavidGlitch04\PMVNGPickaxe\commands\OPPickaxe;
use DavidGlitch04\PMVNGPickaxe\commands\PickaxeCommand;
use DavidGlitch04\PMVNGPickaxe\commands\TopPickaxe;
use DavidGlitch04\PMVNGPickaxe\listener\EventListener;
use DavidGlitch04\PMVNGPickaxe\provider\YamlProvider;
use DavidGlitch04\PMVNGPickaxe\utils\SingletonTrait;
use pocketmine\command\Command;
use pocketmine\command\CommandSender;
use pocketmine\console\ConsoleCommandSender;
use pocketmine\event\Listener;
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

	protected YamlProvider $provider;

	protected function onEnable() : void {
		self::setInstance($this);
		$this->getServer()->getPluginManager()->registerEvents(new EventListener($this), $this);
		$task = new Score($this);
		$this->getScheduler()->scheduleRepeatingTask($task, 20);
		$this->initDepend();
		$this->provider = new YamlProvider();
		$this->provider->initConfig();
		$this->getServer()->getCommandMap()->register('pickaxe', new PickaxeCommand($this));
	}

	public function getProvider(): YamlProvider{
		return $this->provider;
	}

	protected function initDepend(): void{
		$this->li = $this->getServer()->getPluginManager()->getPlugin("LockedItem");
		$this->CE = $this->getServer()->getPluginManager()->getPlugin("PiggyCustomEnchants");
		if ($this->li == null) {
			$this->getLogger()->notice("PMVNG Pickaxe > You have not installed LockedItem, please download it at https://poggit.pmmp.io/p/LockedItem/3.0.0 and then try again.");
			$this->getServer()->getPluginManager()->disablePlugin($this);
		}
		if ($this->CE == null) {
			$this->getLogger()->notice("PMVNG Pickaxe > You have not installed PiggyCustomEnchants, please download it and then try again.");
			$this->getServer()->getPluginManager()->disablePlugin($this);
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
