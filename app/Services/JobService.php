<?php

namespace App\Services;

use App\Models\Job;
use App\Models\Category;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class JobService
{
    public function create($request)
    {
        return Job::create($request);
    }

    public function edit($request, $job)
    {
        return $job->update([
            'title'         => $request->title,
            'description'   => $request->description,
            'salary'        => $request->salary,
            'requirement'   => $request->requirement,
            'category_id'   => $request->category_id,
            'position_id'   => $request->position_id,
            'city_id'       => $request->city_id,
            'deadline'      => $request->deadline,
        ]);

    }

    public function detailJob($id) {
        $job = Job::where(["id" => $id])
        ->with('company','city','position','exp')->get();
        $jobCategories = Category::whereIn('id', explode(",",Job::find($id)->category_id ))->get();
        $jobData =[
            'job' => $job,
            'categories' => $jobCategories
        ];
        return $jobData;
    }

    public function findOrFailById($id) {
        $job = Job::findOrFail($id);
        return $job;
    }


    public function getAllJobs()
    {
        return $jobs = Job::selectRaw( '* , DATEDIFF(`jobs`.`deadline`, NOW()) as `remaining_date`')
        ->where(["status" => 1])
        ->whereRaw( 'deadline >= NOW()')
        ->with('company','category','city','position')->withCount('apply')->get();
    }

    public function getCompanyJobs($company_id, $request)
    {
        if( isset($request->company_id) ) {
            $jobs = Job::selectRaw( '* , DATEDIFF(`jobs`.`deadline`, NOW()) as `remaining_date`')
            ->where('company_id', $company_id)->with('company','category','city','position')->withCount('apply')->get();
            return $jobs;
        } else {
            $jobs = Job::selectRaw( '* , DATEDIFF(`jobs`.`deadline`, NOW()) as `remaining_date`')
            ->where('company_id', $company_id)->where(["status" => 1])
            ->whereRaw( 'deadline >= NOW()')->with('company','category','city','position')->withCount('apply')->get();
            return $jobs;
        }
    }

    public function filterJobs($request)
    {
        return Job::query()
        ->selectRaw( '* , DATEDIFF(`jobs`.`deadline`, NOW()) as `remaining_date`' )
        ->title($request)
        ->category($request)->position($request)->city($request)
        ->where(["status" => 1])->whereRaw( 'deadline >= NOW()')
        ->with('company','category','city','position')->withCount('apply')->get();
    }

    public function toggleStatusJob(Job $job)
    {
        if ($job->status == 1 )
         {
            return $job->update([
                'status' => 0
            ]);
        } else {
            return $job->update([
                'status' => 1
            ]);
        }
    }


    ///////// Recommend jobs handle ////////////
    private function calculateSuitability($candidateData, $job)
    {
        $locationMatch = $this->calculateLocationMatch($candidateData['city'] ?? null, $job["city_id"]);
        $salaryMatch = $this->calculateSalaryMatch($candidateData['salary'] ?? null, $job["salary"], $job["max_salary"]);
        $experienceMatch = $this->calculateExperienceMatch($candidateData['exp'] ?? null, $job["exp_id"]);
        $fieldMatch = $this->calculateFieldMatch($candidateData['category'] ?? null, $job["category_id"]);

        $totalFactors = 4;
        if ($locationMatch === null) {
            $totalFactors--;
            $locationMatch = 0;
        }
        if ($salaryMatch === null) {
            $totalFactors--;
            $salaryMatch = 0;
        }
        if ($experienceMatch === null) {
            $totalFactors--;
            $experienceMatch = 0;
        }
        if ($fieldMatch === null) {
            $totalFactors--;
            $fieldMatch = 0;
        }
        if($totalFactors > 0 ) {
            $totalMatchScore = ($locationMatch + $salaryMatch + $experienceMatch + $fieldMatch) / $totalFactors;
        } else {
            $totalMatchScore = 1;
        }

        return $totalMatchScore;
    }

    private function calculateLocationMatch($candidateLocation, $jobLocation)
    {
        if ($candidateLocation === null) {
            return null;
        }

        $jobLocations = array_map('intval', explode(',', $jobLocation));
        return in_array($candidateLocation, $jobLocations) ? 1 : 0;
    }

    private function calculateSalaryMatch($candidateSalaryRange, $jobSalary, $jobMaxSalary)
    {
        if ($candidateSalaryRange === null) {
            return null;
        }

        $salaryRanges = [
            1 => [0, 5000000],
            2 => [5000000, 10000000],
            3 => [10000000, 15000000],
            4 => [15000000, 20000000],
            5 => [20000000, 25000000],
            6 => [25000000, 30000000],
            7 => [30000000, 50000000],
            8 => [50000000, 1000000000],
        ];

        $range1 = $salaryRanges[$candidateSalaryRange];
        $range2 = $jobMaxSalary!==null ? [$jobSalary, $jobMaxSalary] : [$jobSalary, $jobSalary];

        return $this->calculateOverlap($range1, $range2);
    }

    private function calculateExperienceMatch($candidateExperience, $jobExperienceRequired)
    {
        if ($candidateExperience === null) {
            return null;
        }

        $maxDifference = abs($candidateExperience - $jobExperienceRequired);
        $matchScore = 1 - $maxDifference / 6;

        return max($matchScore, 0);
    }

    private function calculateFieldMatch($candidateDesiredFields, $jobField)
    {
        if ($candidateDesiredFields === null) {
            return null;
        }

        $desiredFields = explode(',', $candidateDesiredFields);
        $jobFields = explode(',', $jobField);

        foreach ($desiredFields as $desiredField) {
            if (in_array($desiredField, $jobFields)) {
                return 1;
            }
        }
        return 0;
    }

    private function calculateOverlap($range1, $range2)
    {
        $start = max($range1[0], $range2[0]);
        $end = min($range1[1], $range2[1]);

        if ($start < $end) {
            // Tính toán sự trùng lặp
            $overlap = $end - $start;
            if ($range2[0] > $range1[0] && $range2[1] < $range1[1]) {
                return 1;
            }
            $maxRange = max($range1[1] - $range1[0], $range2[1] - $range2[0]);
            return $overlap / $maxRange;

        } else {
            //tính khoảng cách giữa các khoảng
            $distance = max($range1[0], $range2[0]) - min($range1[1], $range2[1]);
            // TH đặc biệt khi mức lương cụ thể ở trong khoảng lương mong muốn của ứng viên
            if(($range2[0] === $range2[1] && $range2[0] === $range1[0]) || ($range2[0] === $range2[1] && $range2[0] === $range1[1]
            || $range2[0] === $range2[1] && $range2[0] > $range1[0] && $range2[0] < $range1[1]
            )) {
                return 1;
            }

            $maxRangeLength = max($range1[1] - $range1[0], $range2[1] - $range2[0]);
            // Tính điểm tương đồng dựa trên hàm Gaussian
            $sigma = 5000000;
            $similarityScore = exp(-pow($distance, 2) / (2 * pow($sigma, 2))) * ($sigma/$maxRangeLength);

            return $similarityScore/2;  // không trùng lặp thì tối đa chỉ phù hợp 50% hoặc ít hơn
        }
    }

    public function recommendJobs($request) {

         // Nhận dữ liệu đầu vào từ request
         $candidateData = $request->all();

         // Truy vấn dữ liệu các công việc từ database
        //  $jobs = Job::all();
         $jobs = Job::selectRaw( '* , DATEDIFF(`jobs`.`deadline`, NOW()) as `remaining_date`')
            ->where('status', 1)->whereRaw( 'deadline >= NOW()')
            ->inRandomOrder()
            ->with("company")->with("city")->take(40)->get();

         // Tính toán mức độ phù hợp của các công việc với ứng viên bằng fuzzy logic
         $recommendedJobs = [];
         foreach ($jobs as $job) {
             $suitabilityScore = $this->calculateSuitability($candidateData, $job);
             $jobArray = $job->toArray();
             $jobArray['suitability_score'] = $suitabilityScore;
             $recommendedJobs[] = $jobArray;
            }
            // Sắp xếp danh sách công việc theo mức độ phù hợp giảm dần
            usort($recommendedJobs, function ($a, $b) {
                if ($a['suitability_score'] === $b['suitability_score']) {
                    return 0;
                }
                return ($a['suitability_score'] > $b['suitability_score']) ? -1 : 1;
            });
         return $recommendedJobs;
    }

}
