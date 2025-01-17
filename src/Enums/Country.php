<?php

namespace Spatie\Holidays\Enums;

use Spatie\Holidays\Actions\Belgium;
use Spatie\Holidays\Actions\Executable;

enum Country: string
{
    case Belgium = 'BE';

    public function action(): Executable
    {
        return match ($this) {
            self::Belgium => new Belgium(),
        };
    }
}
