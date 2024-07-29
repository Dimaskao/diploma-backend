<?php

namespace App\Enums;

class Edit extends BaseEnum
{
    const ADD = 'add';
    const REMOVE = 'remove';
    const BAN = 'ban';
    const UNBAN = 'unban';
    const BAN_USERS = 'ban_users';
    const EDIT_INFO = 'edit_info';
    const BAN_POSTS = 'ban_posts';

    const UNBAN_USERS = 'unban_users';
    const UNBAN_POSTS = 'unban_posts';
}
