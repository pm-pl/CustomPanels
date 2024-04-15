<?php

namespace hearlov\custompanels\utils;

use cooldogedev\BedrockEconomy\api\BedrockEconomyAPI;
use cooldogedev\BedrockEconomy\database\exception\RecordNotFoundException;
use cooldogedev\BedrockEconomy\libs\SOFe\AwaitGenerator\Await;
use onebone\economyapi\EconomyAPI;
use cooldogedev\BedrockEconomy\database\cache\GlobalCache;
use pocketmine\item\Item;
use pocketmine\player\Player;

class MoneyUtil{

public static $economyplugin;

    CONST EC = "EconomyAPI";
    CONST BE = "BedrockEconomy";

    public static function setup(String $eco){
        self::$economyplugin = $eco == "EconomyAPI" ? "EconomyAPI" : "BedrockEconomy";
    }

    /**
     * @param Player $player
     * @param int $price
     * @return bool
     */
    public static function buyItem(Player $player, int $price): bool{
        if(self::getMoney($player->getName()) < $price) return false;
        self::reduceMoney($player->getName(), $price);
        return true;
    }

    /**
     * @param Player $player
     * @param int $price
     * @param Item $item
     * @return bool
     */
    public static function sellItem(Player $player, int $price, Item $item): bool{
        if(!$player->getInventory()->contains($item)) return false;
        self::addMoney($player, $price);
        return true;
    }

    /**
     * @param String $player
     * @param int $count
     * @return void
     * Para ekler
     */
    public static function addMoney(String $player, int $count){
        if(self::$economyplugin == self::EC){
            EconomyAPI::getInstance()->addMoney($player, $count);
        }elseif(self::$economyplugin == self::BE){
            BedrockEconomyAPI::CLOSURE()->add("", $player, $count, 0, onSuccess: static function(): void{}, onError: static function(){});
        }
    }

    /**
     * @param String $player
     * @param int $count
     * @return void
     */
    public static function reduceMoney(String $player, int $count){
        if(self::$economyplugin == self::EC){
            EconomyAPI::getInstance()->reduceMoney($player, $count);
        }elseif(self::$economyplugin == self::BE){
            BedrockEconomyAPI::CLOSURE()->subtract("", $player, $count, 0, onSuccess: static function(): void{}, onError: static function(){});
        }
    }

    /**
     * @param String $player
     * @param int $count
     * @return void
     */
    public static function setMoney(String $player, int $count){
        if(self::$economyplugin == self::EC){
            EconomyAPI::getInstance()->setMoney($player, $count);
        }elseif(self::$economyplugin == self::BE){
            BedrockEconomyAPI::CLOSURE()->set("", $player, $count, 0, onSuccess: static function(): void{}, onError: static function(){});
        }
    }

    /**
     * @param String $player
     * @return int|null
     */
    public static function getMoney(String $player): ?int{
        if(self::$economyplugin == self::EC){
            return EconomyAPI::getInstance()->myMoney($player);
        }elseif(self::$economyplugin == self::BE){
            $cache = GlobalCache::ONLINE()->get($player);
            if($cache !== null){
                return $cache->amount;
            }
        }
        return 0;
    }

    public static function testFor(string $player, int $value): bool{
        $money = self::getMoney($player);
        if($value <= $money) return true;
        return false;
    }

}