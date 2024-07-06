<?php

namespace App\Enums;

use App\Enums\Traits\EnumConcern;

enum EditInfoType: string
{
    use EnumConcern;

    case Add = 'add';
    case Remove = 'remove';
}
