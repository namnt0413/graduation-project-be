<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Http\Requests\Api\UserRequest;
use App\Models\User;
use App\Services\UserService;

class UserController extends Controller
{
    private $UserService;

    public function __construct( UserService $UserService){
        $this->UserService = $UserService;
    }

    public function updateProfile(UserRequest $request, User $user) {
        $user = User::findOrFail($request->user_id);
        $this->UserService->edit($request, $user);
        return response([
            'message' => 'OK'
        ],200 );
    }

    public function filterCandidates(Request $request) {
        $candidates = $this->UserService->filterCandidates($request);

        return response([
            'data' => $candidates,
            'message' => 'OK'
        ], 200);
    }

    public function getFindingJobUser(Request $request) {
        $candidates = $this->UserService->getFindingJobUser($request);

        return response([
            'data' => $candidates,
            'message' => 'OK'
        ], 200);
    }

    public function toggleIsFindingJob(Request $request, User $user) {
        if ( isset($request->user_id) ) {
            $user = User::findOrFail($request->user_id);
            $this->UserService->toggleIsFindingJob($user);
            return response([
                'message' => 'OK'
            ],200);
        } else {
            return response([
                'message' => 'Error'
            ],400);
        }

    }

}
