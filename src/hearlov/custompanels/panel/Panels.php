<?php

namespace hearlov\custompanels\panel;

use hearlov\custompanels\CustomPanels;

final class Panels{

    private static CustomPanels $plugin;

    private static array $panels;

    public static function setup(CustomPanels $plugin){
        self::$plugin = $plugin;
    }

    public static function reload(){
        self::$panels = [];
    }

    public static function register(CustomPanel $panel){
        self::$panels[$panel->command] = $panel;
    }

    /**
     * @return CustomPanel[]
     */
    public static function getPanels(): array{
        return isset(self::$panels) ? self::$panels : [];
    }

    public static function getPanel($command): ?CustomPanel{
        return isset(self::$panels[$command]) ? self::$panels[$command] : null;
    }

}