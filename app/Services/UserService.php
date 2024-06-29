<?php

namespace App\Services;

use App\Models\User;
use Carbon\Carbon;

class UserService
{
    public function edit($request, $user)
    {
        if(!isset($request->avatar) ) {
            $request->avatar = $user->avatar;
        }
        return $user->update([
            'name'      => $request->name,
            'birthday'  => $request->birthday,
            'skill'     => $request->skill,
            'school'    => $request->school,
            'work_exp'  => $request->work_exp,
            'favourite' => $request->favourite,
            'activity'  => $request->activity,
            'prize'     => $request->prize,
            'avatar'     => $request->avatar,
        ]);
    }

    public function filterCandidates ($request)
    {
        return User::query()
        ->skill($request)
        ->get();
    }

    public function getFindingJobUser ()
    {
      return User::select('*')->where(["is_finding_job" => 1 ])->get();
    }

    public function toggleIsFindingJob ($user)
    {
        if ($user->is_finding_job == 1 )
         {
            return $user->update([
                'is_finding_job' => 0
            ]);
        } else {
            return $user->update([
                'is_finding_job' => 1
            ]);
        }
    }
}
