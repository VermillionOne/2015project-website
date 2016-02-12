{{-- If number of items is more than one show paginator --}}
@if ($paginator->lastItem() > 0)

  @if ($paginator->currentPage() === 1 && $paginator->lastItem() < 20)

  @else

      <ul class="pager">
        {{-- If cannot go to previous, is set to disabled --}}
        <li class="previous {{ ($paginator->currentPage() == 1) ? ' disabled disable-click' : '' }}">
            <a class="pagi-previous" href="{{ $paginator->appends(\Input::all())->url($paginator->currentPage()-1) }}">&laquo;&nbsp;Previous</a>
        </li>

        {{-- If cannot go forward, is set to disabled --}}
        <li class="next {{ ($paginator->lastItem() < 20) ? ' disabled disable-click' : '' }}">
            <a class="pagi-next" href="{{ $paginator->appends(\Input::all())->url($paginator->currentPage()+1) }} " >Next&nbsp;&raquo;</a>
        </li>
      </ul>

  @endif

@endif
