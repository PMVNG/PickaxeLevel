<?php

declare(strict_types=1);

namespace PMVNG\PickaxeLevel;

use PMVNG\PickaxeLevel\commands\PickaxeCommand;
use PMVNG\PickaxeLevel\item\PickaxeManager;
use PMVNG\PickaxeLevel\listener\EventListener;
use PMVNG\PickaxeLevel\provider\YamlProvider;
use PMVNG\PickaxeLevel\utils\SingletonTrait;
use pocketmine\event\Listener;
use pocketmine\plugin\Plugin;
use pocketmine\plugin\PluginBase;
use pocketmine\utils\Config;
use ytbjero\LockedItem\LockedItem;

class Pickaxe extends PluginBase implements Listener {

	use SingletonTrait;

	/**
	 * @var ?Plugin $CE
	 */
	public $CE;

	/**
	 * @var LockedItem $lockeditem
	 */
	public $lockeditem;

	protected Config $pic;

	protected YamlProvider $provider;

	protected function onEnable(): void {
		self::setInstance($this);
		$this->getServer()->getPluginManager()->registerEvents(new EventListener($this), $this);
		/// $task = new Score($this);
		/// $this->getScheduler()->scheduleRepeatingTask($task, 20);
		$this->initDepend();
		$this->provider = new YamlProvider();
		$this->provider->initConfig();
		$this->getServer()->getCommandMap()->register('pickaxe', new PickaxeCommand($this));
	}

	public function getProvider(): YamlProvider {
		return $this->provider;
	}

	public function getPickaxeMgr(): PickaxeManager {
		return new PickaxeManager();
	}

	protected function initDepend(): void {
		$lockeditem =  $this->getServer()->getPluginManager()->getPlugin("LockedItem");
		if ($lockeditem instanceof LockedItem) {
			$this->lockeditem = $lockeditem;
		} else {
			$this->getLogger()->notice("PMVNG Pickaxe > You have not installed LockedItem, please download it at https://poggit.pmmp.io/p/LockedItem/5.0.0 and then try again.");
			$this->getServer()->getPluginManager()->disablePlugin($this);
		}
		$this->CE = $this->getServer()->getPluginManager()->getPlugin("PiggyCustomEnchants");
	}
}
