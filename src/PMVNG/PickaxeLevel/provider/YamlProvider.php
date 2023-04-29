<?php

declare(strict_types=1);

namespace PMVNG\PickaxeLevel\provider;

use PMVNG\PickaxeLevel\Pickaxe;
use pocketmine\player\Player;
use pocketmine\utils\Config;

class YamlProvider {

	protected Pickaxe $pickaxe;

	protected Config $config, $users;

	public function __construct() {
		$this->pickaxe = Pickaxe::getInstance();
	}

	public function initConfig(): void {
		$this->pickaxe->saveDefaultConfig();
		$this->config = $this->pickaxe->getConfig();
		$this->users = new Config($this->pickaxe->getDataFolder() . "users.yml", Config::YAML);
	}

	public function isRegistered(Player $player): bool {
		$username = $player->getName();
		$data = $this->users->getAll();
		return isset($data[$username]);
	}

	public function registerUser(Player $player): void {
		$username = $player->getName();
		$this->users->set($username, [
			"Level" => 0,
			"Exp" => 0,
			"NextExp" => 1000,
			"Popup" => false
		]);
		$this->users->save();
	}

	/**
	 * @return array{Level: float|int|numeric-string, Exp: float|int|numeric-string, NextExp: float|int|numeric-string, Popup: bool} */
	public function getData(Player $player): array|null {
		$username = $player->getName();
		$data = $this->users->getAll();
		var_dump($data[$username]);
		return isset($data[$username]) ? $data[$username] : null;
	}

	public function addExp(Player $player, int $exp): void {
		$username = $player->getName();
		$data = $this->users->get($username, []);
		$data["Exp"] = intval($this->getData($player)["Exp"]) + $exp;
		$this->users->set($username, $data);
		$this->users->save();
	}

	public function setStatusPopup(Player $player, bool $status): void {
		$username = $player->getName();
		$data = $this->users->get($username, []);
		$data["Popup"] = $status;
		$this->users->set($username, $data);
		$this->users->save();
	}

	public function LevelUP(Player $player): void {
		$username = $player->getName();
		$data = $this->users->get($username, []);
		$data["Level"] = intval($this->getData($player)["Level"]) + 1;
		$data["Exp"] = 0;
		$data["NextExp"] = intval($this->getData($player)["NextExp"]) * 120;
		$this->users->set($username, $data);
		$this->users->save();
	}

	/**
	 * @param array{Level: float|int|numeric-string, Exp: float|int|numeric-string, NextExp: float|int|numeric-string, Popup: false} $data
	 */
	public function setData(Player $player, array $data): void {
		$this->users->set($player->getName(), $data);
		$this->users->save();
	}

	/**
	 * @return array<int|string, mixed>
	 */
	public function getAllUsers(): array {
		return $this->users->getAll();
	}
}
