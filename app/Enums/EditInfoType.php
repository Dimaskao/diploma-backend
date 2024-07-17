<?php

namespace App\Enums;

class EditInfoType extends BaseEnum
{
    const ADD = 'add';
    const REMOVE = 'remove';
    const SELF = 'self';
    const COMPANY = 'company';
    const REGULAR_USER = 'regular_user';
    const ANOTHER_ADMIN = 'another_admin';
    const EDIT_INFO = 'edit_info';
}
