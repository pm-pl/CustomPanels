<?php

namespace hearlov\custompanels\manager;



use hearlov\custompanels\CustomPanels;
use hearlov\custompanels\libs\muqsit\invmenu\InvMenu;
use hearlov\custompanels\libs\muqsit\invmenu\transaction\InvMenuTransaction;
use hearlov\custompanels\libs\muqsit\invmenu\transaction\InvMenuTransactionResult;
use hearlov\custompanels\event\PanelOpenEvent;
use hearlov\custompanels\manager\AgFactory as AF;
use pocketmine\inventory\SimpleInventory;
use pocketmine\player\Player;
use hearlov\custompanels\panel\{Panels as P, CustomPanel};

class OpenPanel{

    private static CustomPanels $plugin;
    private static bool $usedatas;

    public static function setup(CustomPanels $plugin, bool $usedatas){
        self::$plugin = $plugin;
        self::$usedatas = $usedatas;
    }

    private static function getEditedMenu(Player $player, SimpleInventory $inv, InvMenu $invm): SimpleInventory{
        $newenv = new SimpleInventory($inv->getSize());
        $newenv->setContents($inv->getContents());
        foreach($newenv->getContents() as $index => $item){
            if($item->hasCustomName()){
                $nwname = AF::TextGenerate(self::$plugin->getServer(), $player, $item, $item->getCustomname(), $invm);
                $newenv->setItem($index, $item->setCustomName(self::$usedatas ? AF::getDataStatic($nwname) : $nwname));
            }
        }
        return $newenv;
    }

    public static function command(Player $player, string $command){
        $panel = P::getPanel($command);
        if(!$panel instanceof CustomPanel) return;
        $inventory = $panel->getInventory()->getInventory() ?? null;
        if(!$inventory instanceof SimpleInventory) return;
        $event = new PanelOpenEvent(self::$plugin, $player, $panel);
        $event->call();
        if($event->isCancelled()) return;

        $inv = InvMenu::create($panel->getType()->getType());
        $inv->setName($panel->name);
        $envanter = new SimpleInventory($panel->getType()->getTypeSize());
        $envanter->setContents(self::getEditedMenu($player, $inventory, $inv)->getContents());
        $inv->setInventory($envanter);
        if(count($panel->getInventory()->getCommands()) >= 1) {
            $inv->setListener(function (InvMenuTransaction $transaction) use ($inv, $panel, $inventory): InvMenuTransactionResult {
                $instance = false;
                $index = $transaction->getAction()->getSlot();
                if(!$panel->readonly || !$panel->getInventory()->getReadonly($index)) $instance = true;

                $commands = $panel->getInventory()->getCommand($index);
                if($commands !== null){

                    foreach($commands as $command){
                        if($command == "close"){
                            $inv->onClose($transaction->getPlayer());
                        }elseif($command == "reload"){
                            $inv->getInventory()->setContents(self::getEditedMenu($transaction->getPlayer(), $inventory, $inv)->getContents());
                            if($panel->getOpenCommands() !== null) self::openCMD($panel->getOpenCommands(), $transaction->getPlayer(), $inv);
                        }else{
                            self::$plugin->sendCommandInItem($transaction->getPlayer(), $transaction->getOut(), $command, $inv);
                        }
                    }

                }

                if(!$instance) return $transaction->discard(); else return $transaction->continue();
            });
        }
        if($panel->getCloseCommands() !== null){
            $inv->setInventoryCloseListener(function (Player $player, SimpleInventory $inventory) use ($panel, $inv): void{
                foreach($panel->getCloseCommands() as $cmd){
                    self::$plugin->sendCommandInItem($player, $player->getInventory()->getItemInHand(), $cmd, $inv);
                }
            });
        }
        if($panel->getOpenCommands() !== null) self::openCMD($panel->getOpenCommands(), $player, $inv);
        $inv->send($player);

    }

    private static function openCMD(array $args, Player $player, InvMenu &$inv){
        foreach($args as $cmd){
            self::$plugin->sendCommandInItem($player, $player->getInventory()->getItemInHand(), $cmd, $inv);
        }
    }

}