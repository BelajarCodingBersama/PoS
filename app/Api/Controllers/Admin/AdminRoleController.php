<?php

namespace App\Api\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Repositories\RoleRepository;
use App\Api\Requests\RoleStoreRequest;
use App\Api\Requests\RoleUpdateRequest;
use app\Api\Resources\RoleResource;
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

   public function index()
   {
        $roles = $this->roleRepository->get();

        return new RoleResourceCollection($roles);
   }

   public function store(RoleStoreRequest $request)
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

   public function update(Role $role, RoleUpdateRequest $request)
   {
     try {
          DB::beginTransaction();

          $request->merge([
               'slug' => Str::slug($role->name)
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
     ], 201);
   }

   public function destroy(Role $role)
   {
     try {
          DB::beginTransaction();

          if ($role->users->count() >= 1) {
               return response()->json([
                    'message' => "Can't delete this data."
               ], 400);
          }
          
          $role->delete();

          DB::commit();
     } catch (\Throwable $th) {
          DB::rollBack();

          return response()->json([
               'message' => 'Something went wrong, ' . $th->getMessage()
          ], 500);
     }

     return response()->json([
          'message' => 'Role successfully deleted.'
     ], 201);
   }
}