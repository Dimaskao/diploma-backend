<?php

namespace App\Enums;

use App\Enums\Traits\EnumConcern;

enum PostVisibility: string
{
    use EnumConcern;

    case Public = 'public';
    case Private = 'private';
}
