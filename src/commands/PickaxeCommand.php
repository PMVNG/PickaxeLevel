<?php

namespace DavidGlitch04\PMVNGPickaxe\commands;

use DavidGlitch04\PMVNGPickaxe\Pickaxe;
use pocketmine\command\Command;
use pocketmine\command\CommandSender;
use pocketmine\plugin\Plugin;
use pocketmine\plugin\PluginOwned;

class PickaxeCommand extends Command implements PluginOwned{

    protected Pickaxe $plugin;
    
    public function __construct(Pickaxe $plugin)
    {
        $this->plugin = $plugin;
        parent::__construct("pickaxe");
        $this->setDescription("Pickaxe Control");
    }

    public function getOwningPlugin(): Plugin
    {
        return $this->plugin;
    }

    public function execute(CommandSender $sender, string $commandLabel, array $args)
    {
        
    }
}