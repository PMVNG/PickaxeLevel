<?php

declare(strict_types=1);

namespace PMVNG\PickaxeLevel\commands;

use PMVNG\PickaxeLevel\Pickaxe;
use PMVNG\PickaxeLevel\ui\AdminForm;
use PMVNG\PickaxeLevel\ui\MainForm;
use PMVNG\PickaxeLevel\ui\TopForm;
use pocketmine\command\Command;
use pocketmine\command\CommandSender;
use pocketmine\player\Player;
use pocketmine\plugin\Plugin;
use pocketmine\plugin\PluginOwned;
use pocketmine\Server;

class PickaxeCommand extends Command implements PluginOwned {

	protected Pickaxe $plugin;

	public function __construct(Pickaxe $plugin) {
		$this->plugin = $plugin;
		parent::__construct("pickaxe");
		$this->setDescription("Pickaxe Control");
	}

	public function getOwningPlugin(): Plugin {
		return $this->plugin;
	}

	public function execute(CommandSender $sender, string $commandLabel, array $args): void {
		if (!($sender instanceof Player)) {
		    $sender->sendMessage($this->plugin->getConfig()->get("Console-CMD"));
		    return;
		}

		if (!isset($args[0])) {
		    new MainForm($sender);
		    return;
		}

		switch (strtolower($args[0])) {
		    case "toppickaxe":
			new TopForm($sender);
			break;
		    case "op":
			if (Server::getInstance()->isOp($sender->getName())) {
			    new AdminForm($sender);
			}
			break;
		    default:
			new MainForm($sender);
			break;
		}
	}
}
