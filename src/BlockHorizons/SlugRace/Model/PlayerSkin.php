<?php

declare(strict_types = 1);

namespace BlockHorizons\SlugRace\Model;

use pocketmine\utils\Binary;

class PlayerSkin {

	/** @var string */
	private $rawData = "";

	public function __construct(string $skinData) {
		$this->rawData = $skinData;
	}

	/**
	 * @return string
	 */
	public function getRawData(): string {
		return $this->rawData;
	}

	/**
	 * @param int $index
	 *
	 * @return int
	 */
	public function getR(int $index): int {
		return Binary::readByte($this->rawData[$index * 4]);
	}

	/**
	 * @param int $index
	 *
	 * @return int
	 */
	public function getG(int $index): int {
		return Binary::readByte($this->rawData[$index * 4 + 1]);
	}

	/**
	 * @param int $index
	 *
	 * @return int
	 */
	public function getB(int $index): int {
		return Binary::readByte($this->rawData[$index * 4 + 2]);
	}

	/**
	 * @param int $index
	 * @param int $r
	 * @param int $g
	 * @param int $b
	 */
	public function setRGB(int $index, int $r, int $g, int $b): void {
		$this->setR($index, $r);
		$this->setG($index, $g);
		$this->setB($index, $b);
	}

	/**
	 * @param int $index
	 * @param int $value
	 */
	public function setR(int $index, int $value): void {
		$this->rawData[$index * 4] = Binary::writeByte($value);
	}

	/**
	 * @param int $index
	 * @param int $value
	 */
	public function setG(int $index, int $value): void {
		$this->rawData[$index * 4 + 1] = Binary::writeByte($value);
	}

	/**
	 * @param int $index
	 * @param int $value
	 */
	public function setB(int $index, int $value): void {
		$this->rawData[$index * 4 + 2] = Binary::writeByte($value);
	}

	/**
	 * @param int $index
	 * @param int $value
	 */
	public function setAlpha(int $index, int $value): void {
		$this->rawData[$index * 4 + 3] = Binary::writeByte($value);
	}

	/**
	 * @param int $index
	 *
	 * @return int
	 */
	public function getAlpha(int $index): int {
		return Binary::readByte($this->rawData[$index * 4 + 3]);
	}

	/**
	 * @return int[]
	 */
	public function getHeadIndexes(): array {
		$indexes = [];
		for($x = 0; $x < 32; $x++) {
			for($y = 0; $y < 16; $y++) {
				$index = $y * 64 + $x;
				if($this->isEmpty($index)) {
					continue;
				}
				$indexes[] = $index;
			}
		}
		return $indexes;
	}

	/**
	 * @param int $index
	 *
	 * @return bool
	 */
	public function isEmpty(int $index): bool {
		if(!isset($this->rawData[$index * 4])) {
			return false;
		}
		if($this->getR($index) !== 255 || $this->getG($index) !== 255 || $this->getB($index) !== 255) {
			return false;
		}
		return true;
	}

	/**
	 * @return int[]
	 */
	public function getBodyIndexes(): array {
		$indexes = [];
		for($x = 16; $x < 56; $x++) {
			for($y = 16; $y < 32; $y++) {
				$index = $y * 64 + $x;
				if($this->isEmpty($index)) {
					continue;
				}
				$indexes[] = $index;
			}
		}
		return $indexes;
	}
}