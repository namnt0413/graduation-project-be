<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Http\Requests\Api\JobRequest;
use App\Models\Job;
use App\Services\JobService;
use App\Services\CompanyService;
use Illuminate\Support\Facades\Validator;

class JobController extends Controller
{

    private $jobService;

    public function __construct(JobService $jobService, CompanyService $companyService)
    {
        $this->jobService = $jobService;
        $this->companyService = $companyService;
    }

    public function create(JobRequest $request)
    {
        $this->jobService->create($request->validated());
        return response([
            'status' => 200,
            'message' => 'OK'
        ]);
    }

    public function edit(Request $request, $id)
    {
        $job = $this->jobService->findOrFailById($id);
        if ($request->company_id != '') {
            $company = $this->companyService->findOrFailById($request->company_id);
            if ($company->can('edit', $job)) {
                $this->jobService->edit($request, $job);
                return response([
                    'status' => 200,
                    'message' => 'OK'
                ]);
            } else {
                return response([
                    'status' => 404,
                    'message' => 'Do not have permission'
                ]);
            }
        } else {
            return response([
                'status' => 404,
                'message' => 'Please sign in/up company account to do this action'
            ]);
        }

    }

    public function delete(Request $request, $id)
    {
        $job = $this->jobService->findOrFailById($id);
        if ($request->company_id != '') {
            $company = $this->companyService->findOrFailById($request->company_id);
            if ($company->can('delete', $job)) {
                $job->delete();
                return response([
                    'message' => 'OK'
                ], 200);
            } else {
                return response([
                    'message' => 'Do not have permission'
                ], 404);
            }

        } else {
            return response([
                'message' => 'Please sign in/up company account to do this action'
            ], 400);
        }

    }

    public function detail($id)
    {
        $job = $this->jobService->detailJob($id);
        return response([
            'data' => $job,
            'message' => 'OK'
        ], 200);
    }

    public function getAllJobs()
    {
        $jobs = $this->jobService->getAllJobs();
        return response([
            'data' => $jobs,
            'message' => 'OK'
        ], 200);
    }

    public function getCompanyJobs(Request $request, $company_id)
    {
        $jobs = $this->jobService->getCompanyJobs($company_id, $request);
        return response([
            'data' => $jobs,
            'message' => 'OK'
        ], 200);
    }

    public function filterJobs(Request $request)
    {
        $jobs = $this->jobService->filterJobs($request);

        return response([
            'data' => $jobs,
            'message' => 'OK'
        ], 200);
    }

    public function toggleStatusJob(Request $request)
    {
        if (isset($request->job_id)) {
            $job = Job::findOrFail($request->job_id);
            $this->jobService->toggleStatusJob($job);
            return response([
                'status' => 200,
                'message' => 'OK'
            ]);
        } else {
            return response([
                'status' => 404,
                'message' => 'Error'
            ]);
        }
    }

    public function deleteJobs(Request $request)
    {
        // Validate the request
        $validator = Validator::make($request->all(), [
            'jobIds' => 'required|array',
            'jobIds.*' => 'integer|exists:jobs,id',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Invalid input',
                'errors' => $validator->errors(),
            ], 400);
        }

        // Get the job IDs from the request
        $jobIds = $request->input('jobIds');

        // Delete the jobs
        Job::whereIn('id', $jobIds)->delete();

        return response()->json([
            'success' => true,
            'message' => 'Tasks deleted successfully',
        ]);
    }

    public function recommendJobs(Request $request)
    {
        $jobs = $this->jobService->recommendJobs($request);

        return response([
            'recommendJobs' => $jobs,
            'message' => 'OK'
        ], 200);
    }

}
