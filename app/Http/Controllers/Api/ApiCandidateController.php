<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Services\ResponseService;
use App\Models\Candidate;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;

class ApiCandidateController extends Controller
{
    public function index()
    {
        $candidates = Candidate::all();
        return ResponseService::success(['total' => $candidates->count(), 'candidates' => $candidates]);
    }

    public function show(Candidate $candidate)
    {
        return ResponseService::success($candidate);
    }

    public function create(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'chairman_name' => 'required|string',
            'vice_chairman_name' => 'nullable|string',
            'chairman_photo' => 'nullable|image',
            'vice_chairman_photo' => 'nullable|image',
            'vision' => 'nullable|string',
            'mission' => 'nullable|string',
        ]);

        if ($validator->fails()) {
            return ResponseService::error($validator->errors(), 'Validation error', 400);
        }

        $chairman_photo_url = null;
        $vice_chairman_photo_url = null;

        if ($request->hasFile('chairman_photo')) {
            $filename = time() . '.' . $request->file('chairman_photo')->getClientOriginalName();
            $path = "candidates/$filename";
            Storage::disk('public')->put($path, file_get_contents($request->file('chairman_photo')));
            $chairman_photo_url = config('app.url') . "/storage/$path";
        }

        if ($request->hasFile('vice_chairman_photo')) {
            $filename = time() . '.' . $request->file('vice_chairman_photo')->getClientOriginalName();
            $path = "candidates/$filename";
            Storage::disk('public')->put($path, file_get_contents($request->file('vice_chairman_photo')));
            $vice_chairman_photo_url = config('app.url') . "/storage/$path";
        }

        $candidate = Candidate::create([
            'chairman_name' => $request->chairman_name,
            'vice_chairman_name' => $request->vice_chairman_name,
            'chairman_photo' => $chairman_photo_url,
            'vice_chairman_photo' => $vice_chairman_photo_url,
            'vision' => $request->vision,
            'mission' => $request->mission,
        ]);

        return ResponseService::success($candidate, 'Candidate created successfully');
    }

    public function update(Request $request, Candidate $candidate)
    {
        $validator = Validator::make($request->all(), [
            'chairman_name' => 'nullable|string',
            'vice_chairman_name' => 'nullable|string',
            'chairman_photo' => 'nullable|image',
            'vice_chairman_photo' => 'nullable|image',
            'vision' => 'nullable|string',
            'mission' => 'nullable|string',
        ]);

        if ($validator->fails()) {
            return ResponseService::error($validator->errors()->first(), 'Validation error', 400);
        }

        if ($request->hasFile('chairman_photo')) {
            if ($candidate->chairman_photo)
                Storage::disk('public')->delete(get_filename_from_storage_url($candidate->chairman_photo));

            $filename = time() . '.' . $request->file('chairman_photo')->getClientOriginalName();
            $path = "candidates/$filename";
            Storage::disk('public')->put($path, file_get_contents($request->file('chairman_photo')));
            $candidate->chairman_photo = config('app.url') . "/storage/$path";
        }

        if ($request->hasFile('vice_chairman_photo')) {
            if ($candidate->vice_chairman_photo)
                Storage::disk('public')->delete(get_filename_from_storage_url($candidate->vice_chairman_photo));

            $filename = time() . '.' . $request->file('vice_chairman_photo')->getClientOriginalName();
            $path = "candidates/$filename";
            Storage::disk('public')->put($path, file_get_contents($request->file('vice_chairman_photo')));
            $candidate->vice_chairman_photo = config('app.url') . "/storage/$path";
        }

        $candidate->chairman_name = $request->chairman_name ?? $candidate->chairman_name;
        $candidate->vice_chairman_name = $request->vice_chairman_name ?? $candidate->vice_chairman_name;
        $candidate->vision = $request->vision ?? $candidate->vision;
        $candidate->mission = $request->mission ?? $candidate->mission;
        $candidate->save();

        return ResponseService::success($candidate, 'Candidate updated successfully');
    }

    public function destroy(Candidate $candidate)
    {
        if ($candidate->chairman_photo)
            Storage::disk('public')->delete(get_filename_from_storage_url($candidate->chairman_photo));

        if ($candidate->vice_chairman_photo)
            Storage::disk('public')->delete(get_filename_from_storage_url($candidate->vice_chairman_photo));

        $candidate->delete();
        return ResponseService::success(null, 'Candidate deleted successfully');
    }

    public function vote(Request $request, Candidate $candidate)
    {
        $candidate->votes()->create([
            'voter_id' => $request->voter->id,
        ]);

        return ResponseService::success($request->all());
    }
}
