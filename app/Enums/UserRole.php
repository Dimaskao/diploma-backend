<?php

namespace App\Enums;

enum UserRole : string
{
    case RegularUser = 'user';
    case Company = 'company';
}
