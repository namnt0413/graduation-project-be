@component('mail::message')
# Thông báo ứng tuyển

Ứng viên <?= $candidate ?> vừa ứng tuyển vào công việc <?= $job ?> của công ty bạn.

@component('mail::button', ['url' => 'http://localhost:3000'])
Bấm vào đây để xem chi tiết.
@endcomponent

@endcomponent
