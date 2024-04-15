<?php

namespace hearlov\custompanels\panel\type;

class PanelType{

    CONST CHEST = 27;
    CONST DOUBLE_CHEST = 54;
    CONST HOPPER = 5;

    private int $type;

    public function __construct(string $typestr){
        if($typestr == "HOPPER") {
            $this->type = self::HOPPER;
        }else $this->type = $typestr == "DOUBLE_CHEST" ? self::DOUBLE_CHEST : self::CHEST;
    }

    public function getType(): string{
        if($this->type == self::HOPPER){
            return "invmenu:hopper";
        }else return $this->type == self::DOUBLE_CHEST ? "invmenu:double_chest" : "invmenu:chest";
    }

    public function getTypeName(): string{
        if($this->type == self::HOPPER){
            return "HOPPER";
        }else return $this->type == self::DOUBLE_CHEST ? "DOUBLE_CHEST" : "CHEST";
    }

    public function getTypeSize(): int{
        return $this->type;
    }

}