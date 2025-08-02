<?php

namespace App\FileUpload\Http\Controllers;

use App\FileUpload\Services\FileUploadService;
use App\Http\Controllers\BaseController;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class UploadFileController extends BaseController
{
    public function __construct(
        private FileUploadService $service
    )
    {}

    public function uploadPublic(Request $request) : JsonResponse
    {
        $request->validate([
            'file' => 'required|file|max:10240'
        ]);

        $path = $this->service->storePublicFile(file: $request->file('file'));

        return response()->json([
            'data' => [
                'path' => $path
            ]
        ]);
    }
}
