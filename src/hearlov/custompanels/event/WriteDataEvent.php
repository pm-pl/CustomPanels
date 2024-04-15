<?php

namespace hearlov\custompanels\event;

use pocketmine\event\plugin\PluginEvent;
use pocketmine\event\Cancellable;
use pocketmine\event\CancellableTrait;
use hearlov\custompanels\CustomPanels;

class WriteDataEvent extends PluginEvent implements Cancellable{
    use CancellableTrait;
    private string $conx;
    private string $context;

    public function __construct(CustomPanels $plugin, array $data){
        parent::__construct($plugin);
        $this->conx = $data[0];
        $this->context = $data[1];
    }

    /**
     * @return string
     */
    public function getConx(): string{
        return $this->conx;
    }

    /**
     * @return string
     */
    public function getContext(): string{
        return $this->context;
    }

}