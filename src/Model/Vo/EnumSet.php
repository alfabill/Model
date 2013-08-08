<?php

namespace Model\Vo;

class EnumSet
{
    private $values;

    public function __construct(array $values)
    {
        $this->values = $values;
    }

    public function __invoke($value)
    {
        if (!is_array($value)) {
            return;
        }

        $add = [];

        foreach ($value as $k => $v) {
            if (in_array($v, $this->values)) {
                $add[$k] = $v;
            }
        }

        return $add;
    }
}