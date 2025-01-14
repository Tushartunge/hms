<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class ApiController extends Controller
{
    public function exampleMethod(Request $request)
    {
        return response()->json([
            'status' => 'success',
            'message' => 'API is working!',
        ], 200);
    }

    public function submitMethod(Request $request)
{
    $data = $request->validate([
        'name' => 'required|string|max:255',
    ]);

    return response()->json([
        'status' => 'success',
        'message' => 'Data received successfully!',
        'data' => $data,
    ]);
}

}

