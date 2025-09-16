<?php

namespace App\Enums;

enum Sex: string
{
    case Male = 'masculino';
    case Female = 'feminino';

    public function label(): string
    {
        return match ($this) {
            self::Male => 'Masculino',
            self::Female => 'Feminino',
        };
    }
}
