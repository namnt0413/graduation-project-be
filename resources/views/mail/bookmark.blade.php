@component('mail::message')
# Xin chào <?= $candidate ?>

Nhà tuyển dụng  của Công ty <?= $company ?> vừa quan tâm đến CV của bạn và rất có thể sẽ liên hệ lại với bạn trong vài giờ tới.
Hãy cập nhật để CV của bạn để được nhiều nhà tuyển dụng chú ý hơn.
@component('mail::button', ['url' => 'http://localhost:3000'])
Cập nhật CV
@endcomponent

@endcomponent
