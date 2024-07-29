<?php

namespace App\Enums;

class UpdateType extends BaseEnum
{
    const UPDATE_TYPE = 'update_type';
    const PERSONAL_INFORMATION = 'personal_information';
    const EDUCATION = 'education';
    const WORK_EXPERIENCE = 'work_experience';
    const SKILLS = 'skills';
    const SELF = 'self';
    const COMPANY = 'company';
    const REGULAR_USER = 'regular_user';
    const ANOTHER_ADMIN_PERMISSIONS = 'another_admin_permissions';
    const BAN_USER = 'ban_user';
    const BAN_POST = 'ban_post';
    const ADD_NEW_SKILLS = 'add_new_skills';
    const UNBAN_USER = 'unban_user';
    const UNBAN_POST = 'unban_post';
    const REMOVE_SKILLS = 'remove_skills';
}
