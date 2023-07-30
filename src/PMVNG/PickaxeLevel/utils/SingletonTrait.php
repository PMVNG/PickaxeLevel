<?php

declare(strict_types=1);

namespace PMVNG\PickaxeLevel\utils;

use PMVNG\PickaxeLevel\Pickaxe;

trait SingletonTrait
{
    public static Pickaxe $instance;

    public static function setInstance(Pickaxe $instance): void
    {
        self::$instance = $instance;
    }

    public static function getInstance(): Pickaxe
    {
        return self::$instance;
    }
}
