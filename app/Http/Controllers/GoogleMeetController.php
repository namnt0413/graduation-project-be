<?php

namespace App\Http\Controllers;

use Google_Client;
use Google_Service_Calendar;
use Google_Service_Calendar_Event;
use Google_Service_Calendar_EventDateTime;
use Illuminate\Http\Request;
use App\Models\InterviewSchedule;
use Illuminate\Support\Facades\Mail;
use App\Mail\InterviewScheduled;

class GoogleMeetController extends Controller
{
    private $client;

    public function __construct()
    {
        $this->client = new Google_Client();
        $this->client->setClientId(env('GOOGLE_CLIENT_ID'));
        $this->client->setClientSecret(env('GOOGLE_CLIENT_SECRET'));
        $this->client->setRedirectUri(env('GOOGLE_REDIRECT_URI'));
        $this->client->addScope(Google_Service_Calendar::CALENDAR_EVENTS);
    }

    public function redirectToGoogle()
    {
        $auth_url = $this->client->createAuthUrl();
        return redirect()->away($auth_url);
    }

    public function handleGoogleCallback(Request $request)
    {
        $code = $request->input('code');
        $this->client->authenticate($code);
        $token = $this->client->getAccessToken();
        dd($token);

        session(['google_token' => $token]);

        return redirect('/schedule-interview');
    }

    public function scheduleInterview(Request $request)
    {
        $client = new Google_Client();
        $client->setAuthConfig(base_path('app/Credentials/meeting-system.json'));
        $client->setScopes(Google_Service_Calendar::CALENDAR_EVENTS);
        // Xác thực và lấy access token
        $client->fetchAccessTokenWithAssertion();

        // Kiểm tra xem access token đã được cập nhật chưa
        if ($client->isAccessTokenExpired()) {
            $client->refreshToken($client->getRefreshToken());
        }
    
        $service = new Google_Service_Calendar($client);

        $event = new Google_Service_Calendar_Event([
            'summary' => 'Interview with ' . $request->input('candidate_name'),
            'description' => $request->input('content'),
            'start' => [
                'dateTime' => $request->input('date') . 'T' . $request->input('time') . ':00',
                'timeZone' => 'UTC',
            ],
            'end' => [
                'dateTime' => $request->input('date') . 'T' . $request->input('time') . ':30:00',
                'timeZone' => 'UTC',
            ],
            'conferenceData' => [
                'createRequest' => [
                    'conferenceSolutionKey' => [
                        'type' => 'hangoutsMeet',
                    ],
                    'requestId' => 'some-random-string',
                ],
            ],
        ]);

        $calendarId = 'primary';
        $event = $service->events->insert($calendarId, $event, ['conferenceDataVersion' => 1]);

        $interview = new InterviewSchedule();
        $interview->candidate_id = $request->input('candidate_id');
        $interview->company_id = $request->input('company_id');
        $interview->time = $request->input('time');
        $interview->type = $request->input('type');
        $interview->location = $request->input('location');
        $interview->link = $event->hangoutLink;
        $interview->content = $request->input('content');
        $interview->save();
        $interview->candidate_name = $request->input('candidate_name');
        $interview->candidate_email = $request->input('candidate_email');
        $interview->job_title = $request->input('job_title');

        Mail::to($request->input('candidate_email'))->send(new InterviewScheduled($interview));

        return response()->json([
            'status' => 'success',
            'event_link' => $event->hangoutLink,
        ]);
    }
}
