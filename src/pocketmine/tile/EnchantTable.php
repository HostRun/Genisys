<?php

/*
 *
 *  ____            _        _   __  __ _                  __  __ ____  
 * |  _ \ ___   ___| | _____| |_|  \/  (_)_ __   ___      |  \/  |  _ \ 
 * | |_) / _ \ / __| |/ / _ \ __| |\/| | | '_ \ / _ \_____| |\/| | |_) |
 * |  __/ (_) | (__|   <  __/ |_| |  | | | | | |  __/_____| |  | |  __/ 
 * |_|   \___/ \___|_|\_\___|\__|_|  |_|_|_| |_|\___|     |_|  |_|_| 
 *
 * This program is free software: you can redistribute it and/or modify
 * it under the terms of the GNU Lesser General Public License as published by
 * the Free Software Foundation, either version 3 of the License, or
 * (at your option) any later version.
 *
 * @author PocketMine Team
 * @link http://www.pocketmine.net/
 * 
 *
*/

namespace pocketmine\tile;

use pocketmine\inventory\EnchantInventory;
use pocketmine\item\Item;
use pocketmine\level\format\FullChunk;
use pocketmine\nbt\tag\CompoundTag;
use pocketmine\nbt\tag\IntTag;
use pocketmine\nbt\tag\StringTag;
use pocketmine\network\protocol\CraftingDataPacket;

class EnchantTable extends Spawnable implements InventoryHolder, Container, Nameable{
	/** @var EnchantInventory */
	protected $inventory;

	public function __construct(FullChunk $chunk, CompoundTag $nbt){
		parent::__construct($chunk, $nbt);
		$this->inventory = new EnchantInventory($this);
		$this->scheduleUpdate();
	}

	public function getName(){
		return isset($this->namedtag->CustomName) ? $this->namedtag->CustomName->getValue() : "Enchanting Table";
	}

	public function hasName(){
		return isset($this->namedtag->CustomName);
	}

	public function setName($str){
		if($str === ""){
			unset($this->namedtag->CustomName);
			return;
		}

		$this->namedtag->CustomName = new StringTag("CustomName", $str);
	}

	public function close(){
		if($this->closed === false){
			foreach($this->getInventory()->getViewers() as $player){
				$player->removeWindow($this->getInventory());
			}
			parent::close();
		}
	}

	/**
	 * @return int
	 */
	public function getSize(){
		return 2;
	}

	/**
	 * @return EnchantInventory
	 */
	public function getInventory(){
		return $this->inventory;
	}

	public function setItem($index, Item $item){
		// TODO: Implement setItem() method.
	}

	public function getItem($index){
		// TODO: Implement getItem() method.
	}


	public function onUpdate(){
		return false;
	}

	public function getSpawnCompound(){
		$c = new CompoundTag("", [
			new StringTag("id", Tile::ENCHANT_TABLE),
			new IntTag("x", (int) $this->x),
			new IntTag("y", (int) $this->y),
			new IntTag("z", (int) $this->z)
		]);

		if($this->hasName()){
			$c->CustomName = $this->namedtag->CustomName;
		}

		return $c;
	}
}