<?php

namespace hearlov\custompanels\event;

use pocketmine\event\plugin\PluginEvent;
use pocketmine\event\Cancellable;
use pocketmine\event\CancellableTrait;
use pocketmine\player\Player;
use hearlov\custompanels\CustomPanels;
use hearlov\custompanels\panel\CustomPanel;

class PanelOpenEvent extends PluginEvent implements  Cancellable{
    use CancellableTrait;

    private Player $player;
    private CustomPanel $panel;

    public function __construct(CustomPanels $plugin, Player $player, CustomPanel $panel){
        parent::__construct($plugin);
        $this->player = $player;
        $this->panel = $panel;
    }

    /**
     * @return Player
     */
    public function getPlayer(): Player{
        return $this->player;
    }

    /**
     * @return string|null
     */
    public function getPanelName(): ?string{
        return $this->panel->name;
    }

    /**
     * @return CustomPanel
     */
    public function getPanel(): CustomPanel{
        return $this->panel;
    }

}