<?php

namespace App\Enums;

use App\Enums\Traits\EnumConcern;

enum PostStatus: string
{
    use EnumConcern;

    case Draft = 'draft';
    case Published = 'published';
    case Blocked = 'blocked';
}
