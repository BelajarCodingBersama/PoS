<?php

namespace App\Api\Controllers\Admin;

use App\Api\Requests\UserStoreRequest;
use App\Api\Requests\UserUpdateRequest;
use App\Api\Resources\UserResourceCollection;
use App\Http\Controllers\Controller;
use App\Models\User;
use App\Repositories\UserRepository;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class AdminUserController extends Controller
{
    private $userRepository;

    public function __construct(UserRepository $userRepository)
    {
        $this->userRepository = $userRepository;
    }

    public function index()
    {
        $users = $this->userRepository->get();

        return new UserResourceCollection($users);
    }

    public function store(UserStoreRequest $request)
    {
        try {
            DB::beginTransaction();

            $request->merge([
                'password' => bcrypt($request->password)
            ]);

            $data = $request->only([
                'username', 'password', 'role_id', 'file_id'
            ]);

            $user = new User();
            $this->userRepository->save($user->fill($data));

            DB::commit();
        } catch (\Throwable $th) {
            DB::rollBack();

            return response()->json([
                'message' => 'Something went wrong, ' . $th->getMessage()
            ], 500);
        }

        return response()->json([
            'message' => 'User successfully created.'
        ], 201);
    }

    public function update(UserUpdateRequest $request, User $user)
    {
        try {
            DB::beginTransaction();

            $request->merge([
                'password' => bcrypt($request->password)
            ]);

            $data = $request->only([
                'password'
            ]);

            $this->userRepository->save($user->fill($data));

            DB::commit();
        } catch (\Throwable $th) {
            DB::rollBack();

            return response()->json([
                'message' => 'Something went wrong, ' . $th->getMessage()
            ], 500);
        }

        return response()->json([
            'message' => 'User successfully updated.'
        ], 201);
    }
}
