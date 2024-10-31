<?php

namespace App\Http\Controllers;

use App\Services\JobService;
use Illuminate\Http\Request;

class JobController extends Controller
{
    protected $jobService;

    public function __construct(jobService $jobService)
    {
        $this->jobService = $jobService;
    }

    /**
     * Create job and store data.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'urls' => 'required|array',
            'selectors' => 'required|array'
        ]);

        $jobId = $this->jobService->createJob($validated);

        return response()->json(['job_id' => $jobId], 201);
    }

    /**
     * Display the specified job details.
     */
    public function show($id)
    {
        $job = $this->jobService->getJobData($id);

        if (!$job) {
            return response()->json(['error' => 'Job not found'], 404);
        }

        return response()->json($job, 200);
    }

    /**
     * Remove the specified job.
     */
    public function destroy($id)
    {
        $this->jobService->deleteJob($id);

        return response()->json(['message' => 'Job deleted successfully'], 200);
    }
}
