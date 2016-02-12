{{-- Shows youtube and vimeo videos when available --}}
@if (!empty($events['youtube']))

  @foreach ($events['youtube'] as $video)

    <div class="section-hero-card">

      <div class="" align="middle" style="background-color: black;">

        <div class="video-wrapper">

        {!! html_entity_decode($video['embed']) !!}

        </div>

      </div>

    </div>

  @endforeach

@endif

@if (!empty($events['vimeo']))

  @foreach ($events['vimeo'] as $video)

    <div class="section-hero-card">

      <div class="" align="middle" style="background-color: black;">

        <div class="video-wrapper">

        {!! html_entity_decode($video['embed']) !!}

        </div>

      </div>

    </div>

  @endforeach

@endif
