<div class = "event-detail-col-r-widget">
	<div class = "events-detail-title-r-col">{{ isset($events['meta']['qr']['label']) ? $events['meta']['qr']['label'] : 'QR Code' }}</div>
	<div class = "events-detail-info">
		Use your phone's camera to scan the QR Code.
	</div>

	{{-- QR code --}}
	<div class = "qr-holder">
		<img src="{{ url('tickets/' . $hash) }}" width="100%">
	</div>

</div>
