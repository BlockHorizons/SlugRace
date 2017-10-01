<?php

declare(strict_types = 1);

namespace BlockHorizons\SlugRace\Game;

use BlockHorizons\SlugRace\Data\ArenaConfiguration;
use BlockHorizons\SlugRace\SluggishLoader;

class Arena{

	const STATE_IDLE = 0;
	const STATE_AWAITING_PLAYERS = 1;
	const STATE_RUNNING = 2;

	/** @var SluggishLoader */
	private $loader = null;
	/** @var ArenaConfiguration */
	private $arenaConfiguration = null;
	/** @var string */
	private $arenaName = "";

	/** @var int */
	private $state = self::STATE_IDLE;

	public function __construct(SluggishLoader $loader){
		$this->loader = $loader;
		$this->arenaConfiguration = new ArenaConfiguration($this);
	}

	/**
	 * @return SluggishLoader
	 */
	public function getLoader() : SluggishLoader{
		return $this->loader;
	}

	/**
	 * @return ArenaConfiguration
	 */
	public function getArenaConfiguration() : ArenaConfiguration{
		return $this->arenaConfiguration;
	}

	/**
	 * @return int
	 */
	public function getState() : int{
		return $this->state;
	}

	/**
	 * @param bool $literal
	 *
	 * @return bool
	 */
	public function isRunning(bool $literal = false) : bool{
		return ($literal ? $this->state === self::STATE_RUNNING : $this->state !== self::STATE_IDLE);
	}

	/**
	 * @return string
	 */
	public function getName() : string{
		return $this->arenaName;
	}

	/**
	 * @param string $arenaName
	 */
	public function setName(string $arenaName) : void{
		$this->arenaName = $arenaName;
	}
}