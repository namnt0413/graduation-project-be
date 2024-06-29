<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Bookmark;
use App\Models\Company;
use App\Models\User;
use App\Http\Requests\Api\BookmarkRequest;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;
use App\Mail\BookmarkMail;

class BookmarkController extends Controller
{
    public function create(BookmarkRequest $request) {
        $checkExist = DB::table('bookmarks')
        ->where('company_id','=',$request->company_id)
        ->where('user_id','=',$request->user_id)
        ->first();

        if(!isset($checkExist)) {
            $company = Company::where([ 'id' => $request->company_id])->first();
            $candidate = User::where([ 'id' => $request->user_id])->first();
            Mail::to($candidate->email)
                ->send(new BookmarkMail($candidate->name,$company->name));

            Bookmark::create($request->validated());
            return response([
                'message' => 'OK'
            ], 200);
        } else {
            return response([
                'message' => 'Bookmark user failed.'
            ], 400);
        }
    }

    public function delete(Request $request)
    {
        try {
            Bookmark::where('company_id','=',$request->company_id)
            ->where('user_id','=',$request->user_id)->delete();
            return response([
                'message' => 'OK'
            ], 200);
        } catch (\Exception $exception) {
            Log::error('Message: ' . $exception->getMessage() . ' --- Line : ' . $exception->getLine());
            return response()->json([
                'code' => 500,
                'message' => 'delete failed'
            ], 400);
        }

    }

    public function listCandidates($company_id)
    {
        $listCandidates = Bookmark::where('company_id', $company_id)
        ->with('user')
        ->get();
        return response([
            'data' => $listCandidates,
            'message' => 'OK'
        ], 200);
    }

}
