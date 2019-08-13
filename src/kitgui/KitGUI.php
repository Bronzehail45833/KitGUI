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

    public function sendKitGUIMenu(Player $player) {
        $nbt = new CompoundTag("", [new StringTag("id", Tile::CHEST), new StringTag("CustomName", "§l§eKit §cG§6U§bI §aMenu"), new IntTag("x", floor($player->x) - 1), new IntTag("y", floor($player->y) - 2), new IntTag("z", floor($player->z) - 2) ]);
        /** @var Chest $tile */
        $tile = Tile::createTile("Chest", $player->getLevel(), $nbt);
        $block = Block::get(Block::CHEST);
        $block->x = (int)$tile->x;
        $block->y = (int)$tile->y;
        $block->z = (int)$tile->z;
        $block->level = $tile->getLevel();
        $block->level->sendBlocks([$player], [$block]);
        if ($tile instanceof Chest) {
            // Items
            $inv = $tile->getInventory();
            $inv->setItem(0, Item::get(Item::BOW)->setCustomName("§6VIP §eKits")->setLore(["§eClick to select"]));
            $inv->setItem(1,Item::get(288)->setCustomName("§bMVP §eKits")->setLore(["§eClick to select"]));
        }
        $player->addWindow($inv);
            }

    public function sendVipKitsMenu(Player $player) {
        $nbt = new CompoundTag("", [new StringTag("id", Tile::CHEST), new StringTag("CustomName", "§l§6VIP §aKits"), new IntTag("x", floor($player->x) - 2), new IntTag("y", floor($player->y) - 2), new IntTag("z", floor($player->z) - 1) ]);
        /** @var Chest $tile */
        $tile = Tile::createTile("Chest", $player->getLevel(), $nbt);
        $block = Block::get(Block::CHEST);
        $block->x = (int)$tile->x;
        $block->y = (int)$tile->y;
        $block->z = (int)$tile->z;
        $block->level = $tile->getLevel();
        $block->level->sendBlocks([$player], [$block]);
        if ($tile instanceof Chest) {
            // Items
            $inv = $tile->getInventory();
            $inv->setItem(0, Item::get(Item::BOW)->setCustomName("§eTest1")->setLore(["§bClick"]));
            $inv->setItem(1,Item::get(288)->setCustomName("§eTest2")->setLore(["§bClick"]));
        }
        $player->addWindow($inv);
            }
    public function sendMvpKitsMenu(Player $player) {
        $nbt = new CompoundTag("", [new StringTag("id", Tile::CHEST), new StringTag("CustomName", "§l§bMVP §aKits"), new IntTag("x", floor($player->x) - 2), new IntTag("y", floor($player->y) - 2), new IntTag("z", floor($player->z) - 1) ]);
        /** @var Chest $tile */
        $tile = Tile::createTile("Chest", $player->getLevel(), $nbt);
        $block = Block::get(Block::CHEST);
        $block->x = (int)$tile->x;
        $block->y = (int)$tile->y;
        $block->z = (int)$tile->z;
        $block->level = $tile->getLevel();
        $block->level->sendBlocks([$player], [$block]);
        if ($tile instanceof Chest) {
            // Items
            $inv = $tile->getInventory();
            $inv->setItem(0, Item::get(Item::BOW)->setCustomName("§aTest1")->setLore(["§bclick"]));
            $inv->setItem(1,Item::get(288)->setCustomName("§aTest2")->setLore(["§bclick"]));
        }
        $player->addWindow($inv);
            }

    public function onInventoryTransaction(InventoryTransactionEvent $ev) {
        $action = $ev->getTransaction()->getActions();
       $player = $ev->getTransaction()->getSource();
       $item = null;
        foreach ($action as $inventoryAction) {
            $item = $inventoryAction->getTargetItem();
            //vip kits
            if ($item->getName() == "§6VIP §eKits") {
           $player->getInventory()->clearAll();
           $this->sendVipKitsMenu($player);
           }
            //mvp kits
            if ($item->getName() == "§bMVP §eKits") {
           $player->getInventory()->clearAll();
           $this->sendMvpKitsMenu($player);
           } 
            // vip test1 kit
            if ($item->getName() == "§eTest1") {
           $player->getInventory()->clearAll(); 
           $player->getInventory()->addItem(Item::get(Item::IRON_SWORD)->setCustomName("§eTest1 Sword"));
           $player->getInventory()->addItem(Item::get(Item::IRON_HELMET)->setCustomName("§eTest1 Helmet ")); 
           $player->getInventory()->addItem(Item::get(Item::IRON_CHESTPLATE)->setCustomName("§eTest1 Chestplate"));
           $player->getInventory()->addItem(Item::get(Item::IRON_LEGGINGS)->setCustomName("§eTest1 Leggings"));
           $player->getInventory()->addItem(Item::get(Item::IRON_BOOTS)->setCustomName("§eTest1 Boots"));
           $this->closeInventory($player); 
           }
        }
     }
   }
