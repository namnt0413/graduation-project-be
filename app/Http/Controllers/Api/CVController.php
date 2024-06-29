<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\CV;
use App\Http\Requests\Api\CVRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class CVController extends Controller
{
    public function create(CVRequest $request) {
        $newCv = CV::create($request->validated());
        return response([
            'cv' => $newCv,
            'message' => 'create new cv success'
        ], 200);
    }

    public function updateName(Request $request, $id) {
        $cv = CV::findOrFail($id);
        $cv->update([
            'name' => $request->name,
        ]);
        return response([
            'message' => 'OK'
        ], 200);
    }

    public function updateTitle(Request $request, $id) {
        $cv = CV::findOrFail($id);
        $cv->update([
            'title' => $request->field,
        ]);
        return response([
            'message' => 'OK'
        ], 200);
    }

    public function updatePosition(Request $request, $id) {
        $cv = CV::findOrFail($id);
        $cv->update([
            'position' => $request->field,
        ]);
        return response([
            'message' => 'OK'
        ], 200);
    }

    public function updateEmail(Request $request, $id) {
        $cv = CV::findOrFail($id);
        $cv->update([
            'email' => $request->field,
        ]);
        return response([
            'message' => 'OK'
        ], 200);
    }

    public function updatePhone(Request $request, $id) {
        $cv = CV::findOrFail($id);
        $cv->update([
            'phone' => $request->field,
        ]);
        return response([
            'message' => 'OK'
        ], 200);
    }

    public function updateBirthday(Request $request, $id) {
        $cv = CV::findOrFail($id);
        $cv->update([
            'birthday' => $request->field,
        ]);
        return response([
            'message' => 'OK'
        ], 200);
    }

    public function updateAddress(Request $request, $id) {
        $cv = CV::findOrFail($id);
        $cv->update([
            'address' => $request->field,
        ]);
        return response([
            'message' => 'OK'
        ], 200);
    }

    public function updateThemeColor(Request $request, $id) {
        $cv = CV::findOrFail($id);
        $cv->update([
            'theme_color' => $request->theme_color,
        ]);
        return response([
            'message' => 'OK'
        ], 200);
    }
    public function updateTemplate(Request $request, $id) {
        $cv = CV::findOrFail($id);
        $cv->update([
            'template_id' => $request->template_id,
        ]);
        return response([
            'message' => 'OK'
        ], 200);
    }

    public function detail($id)
    {
        $cv = CV::where(["id" => $id])->with('subject')->first();

        return response([
            'data' => $cv,
            'message' => 'OK'
        ], 200);
    }

    public function updateOffset(Request $request, $id) {
        $cv = CV::findOrFail($id);
        $cv->update([
            'offset' => $request->offset,
        ]);
        return response([
            'message' => 'OK'
        ], 200);
    }

    public function getListCvs($id) {
        $listCvs = CV::where('user_id', $id)->get();
        return response([
            'data' => $listCvs,
            'message' => 'OK'
        ], 200);
    }

    // deleteCvs
    public function deleteCvs(Request $request)
    {
        // Validate the request
        $validator = Validator::make($request->all(), [
            'cvIds' => 'required|array',
            'cvIds.*' => 'integer|exists:cvs,id',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Invalid input',
                'errors' => $validator->errors(),
            ], 400);
        }

        // Get the job IDs from the request
        $cvIds = $request->input('cvIds');

        // Delete the jobs
        $cv = CV::whereIn('id', $cvIds)->delete();

        return response()->json([
            'success' => true,
            'message' => 'Cv deleted successfully',
        ]);
    }

    public function updateAvatar(Request $request, $id) {
        $cv = CV::findOrFail($id);
        $cv->update([
            'avatar' => $request->avatar,
        ]);
        return response([
            'message' => 'OK'
        ], 200);
    }

    public function updateShowingAvatar(Request $request, $id) {
        $cv = CV::findOrFail($id);
        $cv->update([
            'is_showing_avatar' => $request->is_showing_avatar,
        ]);
        return response([
            'message' => 'OK'
        ], 200);
    }

}
