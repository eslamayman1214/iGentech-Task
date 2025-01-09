<?php

namespace App\Services;

use App\Models\User;
use Exception;
use Illuminate\Http\UploadedFile;

class UserService
{
    /**
     * Create a new user and handle avatar upload.
     *
     * @param array $data
     * @param UploadedFile|null $avatar
     * @return User
     * @throws Exception
     */
    public function createUser(array $data, ?UploadedFile $avatar): User
    {
        try {
            $user = User::create($data);

            if ($avatar) {
                $user->addMedia($avatar)->toMediaCollection('avatars');
            }

            return $user;
        } catch (Exception $e) {
            throw new Exception('Failed to create user: ' . $e->getMessage());
        }
    }

    /**
     * Update an existing user and handle avatar upload.
     *
     * @param User $user
     * @param array $data
     * @param UploadedFile|null $avatar
     * @return User
     * @throws Exception
     */
    public function updateUser(User $user, array $data, ?UploadedFile $avatar): User
    {
        try {
            $user->update($data);

            if ($avatar) {
                $user->clearMediaCollection('avatars');
                $user->addMedia($avatar)->toMediaCollection('avatars');
            }

            return $user->fresh();
        } catch (Exception $e) {
            throw new Exception('Failed to update user: ' . $e->getMessage());
        }
    }

    /**
     * Delete a user.
     *
     * @param User $user
     * @return void
     * @throws Exception
     */
    public function deleteUser(User $user): void
    {
        try {
            $user->delete();
        } catch (Exception $e) {
            throw new Exception('Failed to delete user: ' . $e->getMessage());
        }
    }
}
