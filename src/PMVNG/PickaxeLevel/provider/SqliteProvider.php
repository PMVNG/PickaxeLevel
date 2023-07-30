<?php

declare(strict_types=1);

namespace PMVNG\PickaxeLevel\provider;

use pocketmine\player\Player;

class SqliteProvider extends Database
{
    protected \SQLite3 $database;

    public function initConfig(): void
    {
        $this->database = new \SQLite3($this->pickaxe->getDataFolder() . "PickaxeLevel.db");
        $exec = "CREATE TABLE IF NOT EXISTS PickaxeLevel (name TEXT PRIMARY KEY, level INTEGER, exp INTEGER, status_popup INTEGER);";
        $this->database->exec($exec);
    }

    public function isRegistered(Player $player): bool
    {
        $result = $this->database->query('SELECT * FROM PickaxeLevel WHERE name = "' . $player->getName() . '";');
        return ($result instanceof \SQLite3Result);
    }

    public function registerUser(Player $player): void
    {
        $result = $this->database->exec('INSERT INTO PickaxeLevel (name, level, exp, status_popup) VALUES ("' . $player->getName() . '", 1, 0, 1);');
        if (!$result) {
            $this->pickaxe->getLogger()->error("Error: " . $this->database->lastErrorMsg());
        }
    }

    public function getData(Player $player): ?array
    {
        $result = $this->database->query('SELECT * FROM PickaxeLevel WHERE name = "' . $player->getName() . '";');
        $data = $result->fetchArray(SQLITE3_ASSOC);
        return ($result instanceof \SQLite3Result) ? $data : null;
    }

    public function addExp(Player $player, int $exp): void
    {
        $data = $this->getData($player);
        $data["exp"] = intval($data["exp"]) + $exp;
        $this->database->exec('UPDATE PickaxeLevel SET exp = ' . $data["exp"] . ' WHERE name = "' . $player->getName() . '";');
    }

    public function setStatusPopup(Player $player, bool $status): void
    {
        $data = $this->getData($player);
        $data["status_popup"] = $status ? 1 : 0;
        $this->database->exec('UPDATE PickaxeLevel SET status_popup = ' . $data["status_popup"] . ' WHERE name = "' . $player->getName() . '";');
    }

    public function LevelUP(Player $player): void
    {
        $data = $this->getData($player);
        $data["level"] = intval($data["level"]) + 1;
        $data["exp"] = 0;
        $this->database->exec('UPDATE PickaxeLevel SET level = ' . $data["level"] . ', exp = ' . $data["exp"] . ' WHERE name = "' . $player->getName() . '";');
    }

    public function setData(Player $player, array $data): void
    {
        $this->database->exec('UPDATE PickaxeLevel SET level = ' . $data["level"] . ', exp = ' . $data["exp"] . ', status_popup = ' . $data["status_popup"] . ' WHERE name = "' . $player->getName() . '";');
    }

    public function getAllUsers(): array
    {
        $result = $this->database->query('SELECT * FROM PickaxeLevel;');
        $data = [];
        while ($row = $result->fetchArray(SQLITE3_ASSOC)) {
            $data[] = $row;
        }
        return $data;
    }
}
