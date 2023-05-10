<?php

declare(strict_types=1);

namespace PMVNG\PickaxeLevel\provider;

use PMVNG\PickaxeLevel\Pickaxe;
use pocketmine\player\Player;

abstract class Database
{
    protected Pickaxe $pickaxe;

    public function __construct()
    {
        $this->pickaxe = Pickaxe::getInstance();
    }

    abstract public function initConfig(): void;

    abstract public function isRegistered(Player $player): bool;

    abstract public function registerUser(Player $player): void;

    /**
     * @param Player $player
     * @return array|null
     */
    abstract public function getData(Player $player): ?array;

    abstract public function addExp(Player $player, int $exp): void;

    abstract public function setStatusPopup(Player $player, bool $status): void;

    abstract public function LevelUP(Player $player): void;

    abstract public function setData(Player $player, array $data): void;

    abstract public function getAllUsers(): array;
}
