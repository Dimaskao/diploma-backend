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
    const ANOTHER_ADMIN_PERMISSIONS = 'another_admin_permissions';
    const BAN_UNBAN = 'ban_unban';
}
