{{-- Weather details for the event --}}
<div class = "event-details-bottom-sect">
  <div class = "weather-sect">
    <div class = "profile-weather-title">{{ isset($events['meta']['weather']['label']) ? $events['meta']['weather']['label'] : 'Weather' }}</div>
    <div class = "profile-weather-days-sect">

      {{-- Weather days --}}
      <div class = "profile-weather-days">
        <div class = "profile-weather-icons"><img src="{{ ViewHelper::asset('assets/img/w-icon-1.jpg') }}" /></div>

        {{-- Sunny... --}}
        <div class = "profile-weather-info">
          <div class = "profile-weather-info-title">Sunny</div>
          <div class = "profile-weather-info-desc">Lorem ipsum dolor sit amet, consectetur adipiscing elit. </div>
        </div>

      </div>

      {{-- Weather days --}}
      <div class = "profile-weather-days">
        <div class = "profile-weather-icons"><img src="{{ ViewHelper::asset('assets/img/w-icon-2.jpg') }}" /></div>

        {{-- Wind.. --}}
        <div class = "profile-weather-info">
          <div class = "profile-weather-info-title">Wind</div>
          <div class = "profile-weather-info-desc">Lorem ipsum dolor sit amet, consectetur adipiscing elit. </div>
        </div>

      </div>

      {{-- Weather days --}}
      <div class = "profile-weather-days">
        <div class = "profile-weather-icons"><img src="{{ ViewHelper::asset('assets/img/w-icon-3.jpg') }}" /></div>

        {{-- Snow --}}
        <div class = "profile-weather-info">
          <div class = "profile-weather-info-title">Snow</div>
          <div class = "profile-weather-info-desc">Lorem ipsum dolor sit amet, consectetur adipiscing elit. </div>
        </div>

      </div>

      {{-- Weather days --}}
      <div class = "profile-weather-days">
        <div class = "profile-weather-icons"><img src="{{ ViewHelper::asset('assets/img/w-icon-4.jpg') }}" /></div>

        {{-- Rainy --}}
        <div class = "profile-weather-info">
          <div class = "profile-weather-info-title">Rainy</div>
          <div class = "profile-weather-info-desc">Lorem ipsum dolor sit amet, consectetur adipiscing elit. </div>
        </div>

      </div>

      {{-- Weather days --}}
      <div class = "profile-weather-days">
        <div class = "profile-weather-icons"><img src="{{ ViewHelper::asset('assets/img/w-icon-5.jpg') }}" /></div>

        {{-- Fair --}}
        <div class = "profile-weather-info">
          <div class = "profile-weather-info-title">Fair</div>
          <div class = "profile-weather-info-desc">Lorem ipsum dolor sit amet, consectetur adipiscing elit. </div>
        </div>

      </div>
    </div>
  </div>
</div>
