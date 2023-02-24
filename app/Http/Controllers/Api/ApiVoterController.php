<?php

namespace App\Http\Controllers\Api;

use App\Exports\VotersExport;
use App\Http\Controllers\Controller;
use App\Http\Services\ResponseService;
use App\Models\Voter;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;
use Maatwebsite\Excel\Facades\Excel;

class ApiVoterController extends Controller
{
    public function login(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'username' => 'required|string',
            'password' => 'required|string',
        ]);

        if ($validator->fails()) {
            return ResponseService::error($validator->errors(), 'Validation error', 400);
        }

        $voter = Voter::where('username', $request->username)->where('password', $request->password)->first();

        if (!$voter) {
            return ResponseService::error('Invalid username or password', 401);
        }

        return ResponseService::success($voter, 'Login successful', 201);
    }

    public function generate(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'amount' => 'required|integer|min:1|max:500',
        ]);

        if ($validator->fails()) {
            return ResponseService::error($validator->errors(), 'Validation error', 400);
        }

        $amount = $request->amount;

        $voters = [];

        for ($i = 0; $i < $amount; $i++) {
            $voters[] = [
                'username' => Str::random(5),
                'password' => Str::random(5),
                'created_at' => now(),
                'updated_at' => now(),
            ];
        }

        Voter::insert($voters);

        return ResponseService::success(null, 'Voters generated successfully');
    }

    public function list(Request $request)
    {
        $voters = Voter::all();
        return ResponseService::success(['total' => $voters->count(), 'voters' => $voters]);
    }

    public function destroy(Request $request)
    {
        DB::statement("SET foreign_key_checks=0");
        Voter::truncate();
        DB::statement("SET foreign_key_checks=1");
        return ResponseService::success(null, 'Voters deleted successfully');
    }

    public function export()
    {
        return Excel::download(new VotersExport, 'users.xlsx');
    }
}
