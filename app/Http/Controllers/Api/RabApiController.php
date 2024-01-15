<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Rab;
use Illuminate\Http\Request;

class RabApiController extends Controller
{
    public function index()
    {
        $rabs = Rab::orderBy('rab_no', 'asc')->get();

        return response()->json(
            [
                "rab_count" => $rabs->count(),
                "data" => $rabs,
                "message" => "success",
            ]
        );
    }
}
