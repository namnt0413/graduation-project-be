<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Exp;

class ExpController extends Controller
{
    public function all()
    {
        $exps = Exp::all();
        return response([
            'data' => $exps,
            'status' => 200,
            'message' => 'OK'
        ]);
    }
}
