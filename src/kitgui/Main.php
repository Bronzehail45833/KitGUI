<?php

namespace kitgui;

use pocketmine\plugin\Plugin;
use pocketmine\plugin\PluginBase;
use pocketmine\Server;
use kitgui\KitGUI;

class Main extends PluginBase {


    public function onEnable() : void
   {
    //kitgui
    $this->getServer()->getCommandMap()->register("kitgui", new KitGUI($this));
    $this->getServer()->getPluginManager()->registerEvents(new KitGUI($this), $this);
   }
 }
