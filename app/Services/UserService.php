<?php

namespace App\Services;

use App\Models\User;
use App\Exceptions\UserNotFountException;

use App\Utils\Pagination;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;

class UserService
{
    public function __construct() {
    }

    public function list(Pagination $pagination) : LengthAwarePaginator {
        Log::info("Retrieving list of Users in PostService");

        return User::query()
            ->paginate($pagination->getPerPage(), ['*'], 'page', $pagination->getCurrentPage());
    }

    public function create($data): User {
        Log::info('Creating user in UserService');

        return User::create([
            'name' => $data['name'],
            'email' => $data['email'],
            'password' => hash::make($data['password']),
        ])->assignRole("user");
    }

    public function findByid($id): User {
        Log::info("Retrieving user with id $id in UserService");

        try {
            return User::findOrFail($id);
        } catch (ModelNotFoundException $e) {
            Log::error("User with id $id Not Found.");

            throw new UserNotFountException($id);
        }
    }

    public function update($data, $id): User {
        Log::info("Updating user with id $id in UserService");

        try {
            $user = User::findOrFail($id);
            $user->update($data);

            return $user;
        } catch (ModelNotFoundException $e) {
            Log::error("User with id $id Not Found.");

            throw new UserNotFountException($id);
        }
    }

    public function delete($id): void {
        Log::info("Deleting user with id $id in UserService");

        try {
            $user = User::findOrFail($id);
        } catch (ModelNotFoundException $e) {
            Log::error("User with id $id Not Found.");

            throw new UserNotFountException($id);
        }

        $user->delete();
    }
}
