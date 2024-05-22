<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Pesanan;
class PesananController extends Controller
{
    public function showPesananJarakNull(){
        $pesanan = Pesanan::where();
    }
}
