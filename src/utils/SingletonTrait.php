<?php

declare(strict_types=1);

namespace DavidGlitch04\PMVNGPickaxe\utils;

use DavidGlitch04\PMVNGPickaxe\Pickaxe;

trait SingletonTrait {
	public static $instance;

	public static function setInstance(Pickaxe $instance): void {
		self::$instance = $instance;
	}

	public static function getInstance(): Pickaxe {
		return self::$instance;
	}
}
