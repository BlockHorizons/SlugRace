<?php

declare(strict_types = 1);

namespace BlockHorizons\SlugRace\Data;

use BlockHorizons\SlugRace\Game\Arena;

class ArenaConfiguration implements \JsonSerializable{

	/** @var Arena */
	private $arena = null;
	/** @var array */
	private $arenaSettings = [];

	public function __construct(Arena $arena){
		$this->arena = $arena;
		if(!file_exists($path = $arena->getLoader()->getDataFolder() . "arenas/" . $this->arena->getName())){
			copy(__DIR__ . "defaultArena.json", $path);
		}
		$this->arenaSettings = json_decode(file_get_contents($path));
	}

	/**
	 * @return Arena
	 */
	public function getArena() : Arena{
		return $this->arena;
	}

	public function jsonSerialize() : array{
		return [
			"name" => $this->arena->getName(),
			"config" => $this->arenaSettings
		];
	}
}