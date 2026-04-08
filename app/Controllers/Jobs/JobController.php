<?php

namespace App\Controllers\Jobs;

use App\Models\Job;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class JobController
{
    // Create a new job posting
    public function store(Request $request)
    {
        $this->validate($request, [
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'user_id' => 'required|exists:users,id'
        ]);

        $job = Job::create($request->all());
        return response()->json($job, 201);
    }

    // Read job postings (index)
    public function index(Request $request)
    {
        $query = Job::query();

        if ($request->has('search')) {
            $query->where('title', 'LIKE', '%' . $request->input('search') . '%');
        }

        $jobs = $query->get();
        return response()->json($jobs);
    }

    // Show a specific job posting
    public function show($id)
    {
        $job = Job::findOrFail($id);
        return response()->json($job);
    }

    // Update a job posting
    public function update(Request $request, $id)
    {
        $this->validate($request, [
            'title' => 'string|max:255',
            'description' => 'string',
        ]);

        $job = Job::findOrFail($id);
        $job->update($request->all());
        return response()->json($job);
    }

    // Delete a job posting
    public function destroy($id)
    {
        $job = Job::findOrFail($id);
        $job->delete();
        return response()->json(null, 204);
    }

    // AI matching function
    public function aiMatch(Request $request)
    {
        $this->validate($request, [
            'skills' => 'required|array',
        ]);

        $jobs = Job::whereIn('required_skills', $request->input('skills'))->get();
        return response()->json($jobs);
    }
}