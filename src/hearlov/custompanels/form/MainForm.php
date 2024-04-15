<?php

namespace hearlov\custompanels\form;

use pocketmine\form\Form;
use pocketmine\player\Player;

class MainForm implements Form{

    public function __construct(){}

    public function handleResponse(Player $player, $data): void
    {
        if (is_null($data)) {
            return;
        }
    }

    public function jsonSerialize(): array
    {
        return [
            "type" => "form",
            "title" => "CustomPanels Menu",
            "content" => "a",
            "buttons" => [
                ["text" => "New Panel"],
                ["text" => "Remove Panel"],
                ["text" => "Panels List"]
            ]
        ];
    }

}