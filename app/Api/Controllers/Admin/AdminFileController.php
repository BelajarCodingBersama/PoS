<?php

namespace App\Api\Controllers\Admin;

use App\Api\Requests\FileStoreRequest;
use App\Api\Resources\FileResourceCollection;
use App\Helpers\FileHelper;
use App\Http\Controllers\Controller;
use App\Models\File;
use Illuminate\Support\Facades\DB;

class AdminFileController extends Controller
{
    public function store(FileStoreRequest $request)
    {
        try {
            DB::beginTransaction();

            $arrId = [];

            foreach ($request->file('files') as $file) {
                $result = FileHelper::store($file, $request->folder_name);
                $arrId[] = $result->id;
            }

            $files = File::whereIn('id', $arrId)->get();

            DB::commit();
        } catch (\Throwable $th) {
            DB::rollBack();

            return response()->json([
                'message' => 'Something went wrong, ' . $th->getMessage()
            ], 500);
        }

        return new FileResourceCollection($files);
    }
}
