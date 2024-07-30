<?php

namespace App\Enums;

use App\Enums\Traits\EnumConcern;

enum Roles: string
{
    use EnumConcern;

    case RegularUser = 'user';
    case Company = 'company';
    case Admin = 'admin';
}
