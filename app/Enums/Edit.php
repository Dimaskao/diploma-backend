<?php

namespace App\Enums;

class Edit extends BaseEnum
{
    const ADD = 'add';
    const REMOVE = 'remove';
    const SELF = 'self';
    const COMPANY = 'company';
    const REGULAR_USER = 'regular_user';
    const ANOTHER_ADMIN_PERMISSIONS = 'another_admin_permissions';
    const EDIT_INFO = 'edit_info';
    const BAN_USER = 'ban_user';
    const BAN_POST = 'ban_post';
    const ADD_NEW_SKILL = 'add_new_skill';
    const UNBAN_USER = 'unban_user';
    const UNBAN_POST = 'unban_post';
}
