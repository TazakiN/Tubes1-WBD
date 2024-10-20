<?php

namespace app\models;

abstract class BaseModel
{
    public $_primary_key = '';

    public function set($attr, $value)
    {
        $this->$attr = $value;
        return $this;
    }

    public function get($attr)
    {
        return $this->$attr;
    }

    abstract public function constructFromArray($array);
    abstract public function toResponse();
}
