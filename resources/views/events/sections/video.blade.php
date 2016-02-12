<div class = "event-detail-col">
    <div class = "events-detail-title">{{ isset($events['meta']['videos']['label']) ? $events['meta']['videos']['label'] : '360 video shoot' }}</div>
    {{-- 360 video section --}}
    <div class = "video-holder-360">
      <img src="{{ ViewHelper::asset('assets/img/video-360-example.jpg') }}" alt="...">
    </div>
</div>
