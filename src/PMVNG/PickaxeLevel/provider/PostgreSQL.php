<?php

declare(strict_types=1);

namespace PMVNG\PickaxeLevel\provider;

use pocketmine\player\Player;

class PostgreSQL extends Database
{
    protected \PDO $pdo;

    public function __construct(array $config)
    {
        $dbconnect = pg_connect("host={$config['host']} port={$config['port']} dbname={$config['database']} user={$config['user']} password={$config['password']}");
        if (!$dbconnect) {
            throw new \RuntimeException("Failed to connect to PostgreSQL database");
        }
        parent::__construct();
    }

    public function initConfig(): void
    {

    }

    public function isRegistered(Player $player): bool
    {

    }

    public function registerUser(Player $player): void
    {

    }

    public function getData(Player $player): array|null
    {

    }

    public function addExp(Player $player, int $exp): void
    {

    }

    public function setStatusPopup(Player $player, bool $status): void
    {

    }

    public function LevelUP(Player $player): void
    {

    }

    public function setData(Player $player): void
    {

    }

    public function getAllUsers(): array
    {

    }
}
