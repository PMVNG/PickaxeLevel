<?php

declare(strict_types=1);

namespace DavidGlitch04\PickaxeLevel\utils;

use DavidGlitch04\PickaxeLevel\Pickaxe;

trait SingletonTrait {
	public static $instance;

	public static function setInstance(Pickaxe $instance): void {
		self::$instance = $instance;
	}

	public static function getInstance(): Pickaxe {
		return self::$instance;
	}
}
