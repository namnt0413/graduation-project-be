<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\InterviewSchedule;
use Illuminate\Support\Facades\Mail;
use App\Mail\InterviewScheduled;
use Carbon\Carbon;

function formatDatetime($datetimeString) {
    // Chuyển chuỗi datetime thành đối tượng Carbon để xử lý dễ dàng hơn
    $datetime = Carbon::parse($datetimeString);

    // Lấy các thông tin cần thiết từ đối tượng Carbon
    $formattedTime = $datetime->format('H\hi');
    $formattedDayOfWeek = __('Thứ ') . $datetime->isoFormat('E'); // Sử dụng translation nếu cần
    $formattedDate = __('ngày ') . $datetime->isoFormat('DD/MM/YYYY'); // Sử dụng translation nếu cần

    // Tạo chuỗi kết quả
    $formattedString = "{$formattedTime} {$formattedDayOfWeek} {$formattedDate}";

    return $formattedString;
}

class InterviewScheduleController extends Controller
{
    public function create(Request $request)
    {
        $interview = new InterviewSchedule();
        $interview->candidate_id = $request->input('candidate_id');
        $interview->company_id = $request->input('company_id');
        $interview->time = $request->input('time');
        $interview->type = $request->input('type');
        $interview->location = $request->input('location');
        $interview->link = $request->input('link');
        $interview->content = $request->input('content');
        $interview->apply_id = $request->input('apply_id');
        $interview->save();
        $request->input('type') == 1 ? $interview->type="Trực tiếp" : $interview->type="Trực tuyến";
        $interview->candidate_name = $request->input('candidate_name');
        $interview->candidate_email = $request->input('candidate_email');
        $interview->job_title = $request->input('job_title');
        $interview->company_name = $request->input('company_name');
        $interview->format_time = formatDatetime($request->input('time'));
        Mail::to($request->input('candidate_email'))->send(new InterviewScheduled($interview));

        return response()->json([
            'status' => 'success',
        ]);
    }

}
