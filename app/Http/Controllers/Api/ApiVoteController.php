<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Services\ResponseService;
use App\Models\Candidate;

class ApiVoteController extends Controller
{
  public function index()
  {
    $candidates = Candidate::select('id', 'chairman_name as name', 'chairman_photo as img')->withCount('votes as value')->get();

    return ResponseService::success($candidates, 'Success', 200);
  }
}
