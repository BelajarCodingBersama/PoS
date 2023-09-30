<?php

namespace App\Api\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Repositories\RoleRepository;
use App\Api\Requests\RoleStoreRequest;
use App\Api\Requests\RoleUpdateRequest;
use App\Api\Resources\RoleResource;
use App\Api\Resources\RoleResourceCollection;
use App\Models\Role;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class AdminRoleController extends Controller
{
    private $roleRepository;

    public function __construct(RoleRepository $roleRepository)
    {
        $this->roleRepository = $roleRepository;
    }

    public function index(Request $request)
    {
        $roles = $this->roleRepository->get([
            'search' => [
                'name' => $request->name
            ],
            'paginate' => $request->per_page
        ]);

        return new RoleResourceCollection($roles);
    }

    public function store(RoleStoreRequest $request, Role $role)
    {
        try {
            DB::beginTransaction();

            $request->merge([
                'slug' => Str::slug($request->name)
            ]);

            $data = $request->only(['name', 'slug']);

            $role = new Role();
            $this->roleRepository->save($role->fill($data));

            DB::commit();
        } catch (\Throwable $th) {
            DB::rollBack();

            return response()->json([
                'message' => 'Something went wrong, ' . $th->getMessage()
            ], 500);
        }

        return response()->json([
            'message' => 'Role successfully created.'
        ], 201);
    }

    public function show(Role $role)
    {
        return new RoleResource($role);
    }

    public function update(RoleUpdateRequest $request, Role $role)
    {
        try {
            DB::beginTransaction();

            $request->merge([
                'slug' => Str::slug($request->name)
            ]);

            $data = $request->only(['name', 'slug']);

            $this->roleRepository->save($role->fill($data));

            DB::commit();
        } catch (\Throwable $th) {
            DB::rollback();

            return response()->json([
                'message' => 'Something went wrong, ' . $th->getMessage()
            ], 500);
        }

        return response()->json([
            'message' => 'Role successfully updated.'
        ], 200);
    }

    public function destroy(Role $role)
    {
        $this->authorize('delete', $role);

        try {
            DB::beginTransaction();

            $softDeleted = $role->delete();

            if ($softDeleted) {
                $roleNameUpdated = $role->name = $role->name . '|' . $role->deleted_at;
                $role->save();
            }

            DB::commit();
        } catch (\Throwable $th) {
            DB::rollBack();

            return response()->json([
                'message' => 'Something went wrong, ' . $th->getMessage()
            ], 500);
        }

        return response()->json([
            'message' => 'Role successfully deleted.'
        ], 200);
    }
}
