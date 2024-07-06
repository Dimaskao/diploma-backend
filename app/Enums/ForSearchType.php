<?php

namespace App\Enums;

use App\Enums\Traits\EnumConcern;

enum ForSearchType: string
{
    use EnumConcern;

    case Users = 'users';
    case Companies = 'companies';
    case All = 'all';
}
