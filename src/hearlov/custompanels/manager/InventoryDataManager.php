<?php

namespace hearlov\custompanels\manager;

use pocketmine\utils\Config;
use pocketmine\plugin\PluginBase;
use hearlov\custompanels\event\WriteDataEvent;

class InventoryDataManager{

    private static Config $config;
    private static array $datas;
    private static PluginBase $plugin;

    public static function setup(PluginBase $plugin){
        self::$plugin = $plugin;
        self::$config = new Config($plugin->getDataFolder() . "datas.yml", Config::YAML);
        //self::$datas = array_flip(array_map(fn($i): string => "[".$i."]",array_flip(self::$config->getAll()))); OLD ESSENCE
        foreach(self::$config->getAll() as $conx => $data) self::$datas["[".$conx."]"] = $data;
    }

    public static function setData($conx, $context){
        $event = new WriteDataEvent(self::$plugin, [$conx, $context]);
        $event->call();
        if($event->isCancelled()) return;

        self::$config->set($conx, $context);
        self::$datas["[".$conx."]"] = $context;
        self::$config->save();
    }

    public static function allDatas(): ?array{
        return self::$datas;
    }

    public static function getData($conx): ?String{
        $data = self::$datas["[".$conx."]"] ?? null;
        return $data;
    }

}