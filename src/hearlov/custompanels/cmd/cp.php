<?php

namespace hearlov\custompanels\cmd;

use pocketmine\player\Player;
use pocketmine\command\CommandSender;
use hearlov\custompanels\CustomPanels;
use pocketmine\plugin\PluginOwned;
use pocketmine\command\Command;
use hearlov\custompanels\form\MainForm;

class cp extends Command implements PluginOwned
{

    private CustomPanels $plugin;

    public function __construct(CustomPanels $plugin)
    {
        parent::__construct("cp");
        $this->plugin = $plugin;
        $this->setDescription("CustomPanels Plugin Menu");
        $this->setPermissionMessage("CustomPanels Uses custompanels.admin perm. you must have this");
        $this->setPermission("custompanels.admin");
    }

    public function execute(CommandSender $sender, string $commandLabel, array $args){
        if(!$sender instanceof Player) return;
        $sender->sendForm(new MainForm());
    }

    public function getOwningPlugin(): CustomPanels{
        return $this->plugin;
    }

}