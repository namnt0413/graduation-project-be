<!DOCTYPE html>
<html>
<head>
    <title>THƯ MỜI PHỎNG VẤN</title>
</head>
<body>
    <p>Thân gửi bạn <strong>{{ $candidate_name }}</strong>,</p>
    <p>{{ $company_name }} trân trọng mời bạn tham dự buổi phỏng vấn của Công ty : </p>
    <p><strong>Vị trí:</strong> {{ $job_title }}</p>
    <p><strong>Thời gian:</strong> {{ $format_time }}</p>
    <p><strong>Hình thức:</strong> {{ $type }}</p>
    @if($type === 'Trực tiếp')
        <p><strong>Địa điểm:</strong> {{ $location }}</p>
    @else
        <p><strong>Link meeting:</strong> <a href="{{ $link }}">{{ $link }}</a></p>
    @endif
    <p>{{ $content }}</p>
</body>
</html>
