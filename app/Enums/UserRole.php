<?php

namespace App\Enums;

use App\Enums\Traits\EnumConcern;

enum UserRole: string
{
    use EnumConcern;

    case RegularUser = 'user';
    case Company = 'company';
}
