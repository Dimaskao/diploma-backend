<?php

namespace App\Services\Profile\SpecificProfile\Admin\Handlers\Update\Helpers;

use App\Enums\Edit;
use App\Enums\Permission;
use App\Enums\ResponseKey;
use App\Events\UserBanned;
use App\Models\Admin;
use App\Models\BannedPost;
use App\Models\BannedUser;
use App\Models\User;
use Exception;

trait BanUnbanUpdateHelper
{
    /**
     * @throws Exception
     */
    protected function handleBanOrUnban($banUnbanRequest, $admin): array
    {
        $result = [];

        foreach ($banUnbanRequest as $operationData) {
            foreach ($operationData as $key => $data) {
                $result[$key][] = match ($key) {
                    Edit::BAN => $this->processBan($data, $admin),
                    Edit::UNBAN => $this->processUnban($data, $admin),
                    default => throw new Exception('Invalid operation type')
                };
            }
        }

        return $result;
    }

    /**
     * @throws Exception
     */
    private function processBan($data, $admin): array
    {
        foreach ($data as $key => $banDataList) {
            foreach ($banDataList as $banData) {
                return match ($key) {
                    Edit::BAN_USERS => $this->banUser($banData, $admin),
                    Edit::BAN_POSTS => $this->banPost($banData, $admin),
                    default => throw new Exception('Invalid ban type')
                };
            }
        }
        return [];
    }

    /**
     * @throws Exception
     */
    private function processUnban($data, $admin): array
    {
        foreach ($data as $key => $unbanDataList) {
            foreach ($unbanDataList as $unbanData) {
                return match ($key) {
                    Edit::UNBAN_USERS => $this->unbanUser($unbanData, $admin),
                    Edit::UNBAN_POSTS => $this->unbanPost($unbanData, $admin),
                    default => throw new Exception('Invalid unban type')
                };
            }
        }
        return [];
    }

    /**
     * @throws Exception
     */
    private function banUser($banRequest, Admin $admin): array
    {
        return $this->handleBan($banRequest, $admin, 'user_id_to_ban', BannedUser::class);
    }

    /**
     * @throws Exception
     */
    private function banPost($banRequest, Admin $admin): array
    {
        return $this->handleBan($banRequest, $admin, 'post_id_to_ban', BannedPost::class);
    }

    /**
     * @throws Exception
     */
    private function unbanUser($unbanRequest, Admin $admin): array
    {
        return $this->handleUnban($unbanRequest, $admin, 'user_id_to_unban', BannedUser::class);
    }

    /**
     * @throws Exception
     */
    private function unbanPost($unbanRequest, Admin $admin): array
    {
        return $this->handleUnban($unbanRequest, $admin, 'post_id_to_unban', BannedPost::class);
    }

    /**
     * @throws Exception
     */
    private function handleBan($banRequest, Admin $admin, string $idKey, string $modelClass): array
    {
        if (!isset($banRequest[$idKey]) || !$this->hasFullPermission($admin) || !isset($banRequest['reason'])) {
            throw new Exception("Admin does not have full permissions or the ID of the entity to ban/unban was not sent");
        }

        $entity = User::findOrFail($banRequest[$idKey]);

        $data = [
            'user_id' => $entity->id,
            'banned_by_admin_id' => $admin->id,
            'reason' => $banRequest['reason'],
            'date_banned' => time(),
            'valid_until' => $banRequest['valid_until'] ?? null,
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
    private function handleUnban($unbanRequest, Admin $admin, string $idKey, string $modelClass): array
    {
        if (!isset($unbanRequest[$idKey]) || !$this->hasFullPermission($admin)) {
            throw new Exception("Admin does not have full permissions or the ID of the entity to unban was not sent");
        }

        match ($modelClass) {
            BannedPost::class => BannedPost::where('post_id', $unbanRequest[$idKey])->firstOrFail()->delete(),
            BannedUser::class => BannedUser::where('user_id', $unbanRequest[$idKey])->firstOrFail()->delete(),
            default => throw new Exception("Unknown model class")
        };

        return [ResponseKey::MESSAGE => 'success'];
    }

    private function hasFullPermission(Admin $admin): bool
    {
        return isset(json_decode($admin->permissions, true)[Permission::FULL]);
    }
}
