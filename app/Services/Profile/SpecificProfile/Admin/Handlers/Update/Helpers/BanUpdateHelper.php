<?php

namespace App\Services\Profile\SpecificProfile\Admin\Handlers\Update\Helpers;

use App\Enums\Permission;
use App\Enums\ResponseKey;
use App\Events\UserBanned;
use App\Models\Admin;
use App\Models\BannedPost;
use App\Models\BannedUser;
use App\Models\User;
use Exception;

trait BanUpdateHelper
{
    /**
     * @throws Exception
     */
    protected function handleBanOrUnban($editRequest, Admin $admin, string $idKey, string $modelClass): array
    {
        if (!isset($editRequest[$idKey]) || !$this->hasFullPermission($admin)) {
            throw new Exception("Admin does not have full permissions or the ID of the entity to ban/unban was not sent");
        }

        $entity = User::findOrFail($editRequest[$idKey]);

        $data = [
            'user_id' => $entity->id,
            'banned_by_admin_id' => $admin->id,
            'reason' => $editRequest['reason'],
            'date_banned' => time(),
            'valid_until' => $editRequest['valid_until'] ?? null,
        ];

        match ($modelClass) {
            BannedPost::class => BannedPost::create($data),
            BannedUser::class => BannedUser::create($data),
            default => throw new Exception("Unknown model class")
        };

        if ($modelClass === BannedUser::class) {
            event(new UserBanned($entity));
        }

        return [ResponseKey::MESSAGE => 'success'];
    }

    /**
     * @throws Exception
     */
    private function handleUnban($editRequest, Admin $admin, string $idKey, string $modelClass): array
    {
        if (!isset($editRequest[$idKey]) || !$this->hasFullPermission($admin)) {
            throw new Exception("Admin does not have full permissions or the ID of the entity to unban was not sent");
        }

        match ($modelClass) {
            BannedPost::class => BannedPost::where('post_id', $editRequest[$idKey])->firstOrFail()->delete(),
            BannedUser::class => BannedUser::where('user_id', $editRequest[$idKey])->firstOrFail()->delete(),
            default => throw new Exception("Unknown model class")
        };

        return [ResponseKey::MESSAGE => 'success'];
    }

    /**
     * @throws Exception
     */
    protected function banUser($editRequest, Admin $admin): array
    {
        return $this->handleBanOrUnban($editRequest, $admin, 'user_id_to_ban', BannedUser::class);
    }

    /**
     * @throws Exception
     */
    protected function banPost($editRequest, Admin $admin): array
    {
        return $this->handleBanOrUnban($editRequest, $admin, 'post_id_to_ban', BannedPost::class);
    }

    /**
     * @throws Exception
     */
    protected function unbanUser($editRequest, Admin $admin): array
    {
        return $this->handleUnban($editRequest, $admin, 'user_id_to_unban', BannedUser::class);
    }

    /**
     * @throws Exception
     */
    protected function unbanPost($editRequest, Admin $admin): array
    {
        return $this->handleUnban($editRequest, $admin, 'post_id_to_unban', BannedPost::class);
    }

    private function hasFullPermission(Admin $admin): bool
    {
        return isset($admin->permissions[Permission::FULL]);
    }
}
