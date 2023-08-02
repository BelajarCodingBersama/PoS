<?php

namespace App\Api\Controllers\Admin;

use App\Api\Requests\SellerStoreRequest;
use App\Api\Requests\SellerUpdateRequest;
use App\Api\Resources\SellerResource;
use App\Api\Resources\SellerResourceCollection;
use App\Http\Controllers\Controller;
use App\Models\Seller;
use App\Repositories\SellerRepository;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class AdminSellerController extends Controller
{
    private $sellerRepository;

    public function __construct(SellerRepository $sellerRepository)
    {
        $this->sellerRepository = $sellerRepository;
    }

    public function index(Request $request)
    {
        $sellers = $this->sellerRepository->get([
            'search' => [
                'name' => $request->name
            ],
            'paginate' => $request->per_page
        ]);

        return new SellerResourceCollection($sellers);
    }

    public function store(SellerStoreRequest $request)
    {
        try {
            DB::beginTransaction();

            $request->merge(['slug' => Str::slug($request->name)]);

            $data = $request->only(['name', 'slug']);

            $seller = new Seller();
            $this->sellerRepository->save($seller->fill($data));

            DB::commit();
        } catch (\Throwable $th) {
            DB::rollBack();

            return response()->json([
                'message' => 'Something went wrong, ' . $th->getMessage()
            ], 500);
        }

        return response()->json([
            'message' => 'Seller successfully created.'
        ], 201);
    }

    public function show(Seller $seller)
    {
        return new SellerResource($seller);
    }

    public function update(SellerUpdateRequest $request, Seller $seller)
    {
        try {
            DB::beginTransaction();

            $request->merge(['slug' => Str::slug($request->name)]);

            $data = $request->only(['name', 'slug']);

            $this->sellerRepository->save($seller->fill($data));

            DB::commit();
        } catch (\Throwable $th) {
            DB::rollBack();

            return response()->json([
                'message' => 'Something went wrong, ' . $th->getMessage()
            ], 500);
        }

        return response()->json([
            'message' => 'Seller successfully updated.'
        ], 201);
    }

    public function destroy(Seller $seller)
    {
        try {
            DB::beginTransaction();

            $seller->delete();

            DB::commit();
        } catch (\Throwable $th) {
            DB::rollBack();

            return response()->json([
                'message' => 'Something went wrong, ' . $th->getMessage()
            ], 500);
        }

        return response()->json([
            'message' => 'Seller successfully deleted.'
        ], 201);
    }
}
