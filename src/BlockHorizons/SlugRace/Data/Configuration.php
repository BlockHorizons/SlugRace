<?php

declare(strict_types = 1);

namespace BlockHorizons\SlugRace\Data;

use BlockHorizons\SlugRace\SluggishLoader;

class Configuration{

	/** @var SluggishLoader */
	private $loader = null;
	/** @var array */
	private $settings = [];

	public function __construct(SluggishLoader $loader){
		$this->loader = $loader;

		$loader->saveDefaultConfig();
		if(!is_dir($loader->getDataFolder() . "arenas")){
			mkdir($loader->getDataFolder() . "arenas");
		}
		$this->settings = yaml_parse_file($loader->getDataFolder() . "config.yml");
	}

	/**
	 * @return SluggishLoader
	 */
	public function getLoader() : SluggishLoader{
		return $this->loader;
	}
}