<?php

namespace denis909\yii;

abstract class FormatterBehavior extends \yii\base\Behavior
{

    public $attributes = [];

    public $prefix;

    public $suffix;

    abstract protected function encodeValue($value);

    protected function getAttributeName($name)
    {
        $attributes = $this->attributes;

        foreach($attributes as $key => $value)
        {
            if (!is_numeric($key))
            {
                if ($value == $name)
                {
                    return $key;
                }

                unset($attributes[$key]);
            }
        }

        $pattern = '/^' . preg_quote($this->prefix, '/') . '(.*)' .  preg_quote($this->suffix, '/') . '$/';

        if (preg_match($pattern, $name, $matches))
        {
            if (array_search($matches[1], $attributes) !== false)
            {
                return $matches[1];
            }
        }

        return null;
    }

    public function canGetProperty($name, $checkVars = true)
    {
        $attribute = $this->getAttributeName($name);

        if ($attribute)
        {
            return true;
        }
 
        return parent::canGetProperty($name, $checkVars);
    }    

    public function __get($name)
    {
        $attribute = $this->getAttributeName($name);

        if ($attribute)
        {
            return $this->encodeValue($this->owner->{$attribute});
        }
    
        return parent::__get($name);
    }

}