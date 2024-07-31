<?php

namespace App\Services\Profile\SpecificProfile\Admin\Handlers\Delete;

use App\Models\User;

trait DeleteHandler
{
    protected function delete(User $base): void
    {
        $base->userProfile->admin->delete();
        $base->userProfile->delete();
        $base->delete();
    }
}
