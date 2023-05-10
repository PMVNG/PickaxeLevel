<?php

declare(strict_types=1);

namespace PMVNG\PickaxeLevel;

use PMVNG\PickaxeLevel\commands\PickaxeCommand;
use PMVNG\PickaxeLevel\item\PickaxeManager;
use PMVNG\PickaxeLevel\listener\EventListener;
use PMVNG\PickaxeLevel\provider\Database;
use PMVNG\PickaxeLevel\provider\PostgreSQL;
use PMVNG\PickaxeLevel\provider\SqliteProvider;
use PMVNG\PickaxeLevel\provider\YamlProvider;
use PMVNG\PickaxeLevel\utils\SingletonTrait;
use pocketmine\event\Listener;
use pocketmine\plugin\Plugin;
use pocketmine\plugin\PluginBase;
use pocketmine\utils\Config;
use ytbjero\LockedItem\LockedItem;

class Pickaxe extends PluginBase implements Listener
{
    use SingletonTrait;

    /**
     * @var ?Plugin $CE
     */
    public $CE;

    /**
     * @var LockedItem $lockeditem
     */
    public ?LockedItem $lockeditem;

    protected Config $pic;

    protected Database $provider;

    protected function onEnable(): void
    {
        self::setInstance($this);
        $this->getServer()->getPluginManager()->registerEvents(new EventListener($this), $this);
        /// $task = new Score($this);
        /// $this->getScheduler()->scheduleRepeatingTask($task, 20);
        $this->initDepend();
        $this->selectConfig();
        $this->getServer()->getCommandMap()->register('pickaxe', new PickaxeCommand($this));
    }

    protected function selectConfig(): void
    {
        $config = $this->getConfig()->get('config');
        switch (strtolower($config)) {
            case 'postgre':
                $this->provider = new PostgreSQL($this->getConfig()->get('postgresql'));
                break;
            case 'sqlite':
                $this->provider = new SqliteProvider();
                break;
            default:
                $this->provider = new YamlProvider();
                break;
        }
        $this->provider->initConfig();
    }

    public function getProvider(): Database
    {
        return $this->provider;
    }

    public function getPickaxeMgr(): PickaxeManager
    {
        return new PickaxeManager();
    }

    public static function isLockedItem(): bool
    {
        return self::getInstance()->lockeditem instanceof LockedItem;
    }

    protected function initDepend(): void
    {
        $this->lockeditem = $this->getServer()->getPluginManager()->getPlugin("LockedItem");
        $this->CE = $this->getServer()->getPluginManager()->getPlugin("PiggyCustomEnchants");
    }
}
