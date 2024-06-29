<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Http\Requests\Api\SubjectRequest;
use App\Models\Subject;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;

class SubjectController extends Controller
{
    public function create(SubjectRequest $request) {
        $newSubject = Subject::create($request->validated());
        return response([
            "subject" => $newSubject,
            'message' => 'create new subject success'
        ], 200);
    }

    public function detail($id)
    {
        $subject = Subject::where(["id" => $id])->with('item')->first();
        return response([
            'data' => $subject,
            'message' => 'OK'
        ], 200);
    }

    public function updateSubjectTitle(Request $request, $id) {
        $subject = Subject::findOrFail($id);
        $subject->update([
            'title' => $request->title,
        ]);
        return response([
            'message' => 'OK'
        ], 200);
    }

    public function updateOffset(Request $request, $id) {
        $subject = Subject::findOrFail($id);
        $subject->update([
            'offset' => $request->offset,
        ]);
        return response([
            'message' => 'OK'
        ], 200);
    }

    public function delete($id)
    {
        try {
            $subject = Subject::find($id);
            if(isset($subject->items)) {
                $subject->items()->delete();
            }
            $subject->delete();
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

}
