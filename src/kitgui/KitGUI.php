<?php

namespace kitgui;

use pocketmine\command\Command;
use pocketmine\command\ConsoleCommandSender;
use pocketmine\command\CommandSender;
use pocketmine\Player;
use pocketmine\Server;
use pocketmine\block\Block;
use pocketmine\tile\Chest;
use pocketmine\tile\Tile;
use pocketmine\item\Item;
use pocketmine\item\ItemIds;
use pocketmine\inventory\ChestInventory;
use pocketmine\event\Listener;
use pocketmine\utils\Config;
use pocketmine\event\inventory\InventoryCloseEvent;
use pocketmine\event\inventory\InventoryTransactionEvent;
use pocketmine\inventory\transaction\action\InventoryAction;
use pocketmine\inventory\transaction\action\SlotChangeAction;
use onebone\economyapi\EconomyAPI;
use pocketmine\nbt\tag\CompoundTag;
use pocketmine\nbt\tag\IntTag;
use pocketmine\nbt\tag\StringTag;
use kitgui\Main;

class KitGUI extends Command implements Listener {

    public function __construct(Main $plugin) {
      parent::__construct("kitgui", "Opens the kit gui", "Usage: /kitgui", ["kitgui"]);
   $this->setPermission('open.kitgui'); 
    }

    public function execute(CommandSender $sender, string $label, array $args) : bool
    {
    if(!$this->testPermission($sender)){
			return true;
     }

     if ($sender instanceof Player) {
      $this->sendKitGUIMenu($sender); //send the kitgui menu to the command sender
      return true;
    }
     if ($sender instanceof ConsoleCommandSender) {
     $sender->sendMessage("Run this command in-game!");
     return true;
  }
 }

    public function closeInventory(Player $player) {
        $block1 = Block::get(Block::AIR);
        $block1->x = (int) $player->x - 1;
        $block1->y = (int) $player->y - 2;
        $block1->z = (int) $player->z - 2;
        $block1->level = $player->getLevel();
        $block1->level->sendBlocks([$player], [$block1]);
        $block2 = Block::get(Block::AIR);
        $block2->x = (int) $player->x - 2;
        $block2->y = (int) $player->y - 2;
        $block2->z = (int) $player->z - 1;
        $block2->level = $player->getLevel();
        $block2->level->sendBlocks([$player], [$block2]);
        $block3 = Block::get(Block::AIR);
        $block3->x = (int) $player->x - 2;
        $block3->y = (int) $player->y - 1;
        $block3->z = (int) $player->z - 2;
        $block3->level = $player->getLevel();
        $block3->level->sendBlocks([$player], [$block3]);
    }	
