<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Artisan;

class PresensiController extends Controller
{
    public function generatePresensi()
    {
        Artisan::call('tambah:presensi');
        $message = Artisan::output();
        return response()->json([
            'message' => $message
        ]);
    }
}
