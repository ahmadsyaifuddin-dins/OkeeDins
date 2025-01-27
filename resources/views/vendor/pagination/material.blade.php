@if ($paginator->hasPages())
    <nav aria-label="Page navigation example">
        <ul class="pagination mb-0">
            {{-- Previous Page Link --}}
            @if ($paginator->onFirstPage())
                <li class="page-item disabled">
                    <a class="page-link" href="javascript:;" aria-label="Previous">
                        <span class="material-icons d-flex align-items-center" style="font-size: 1.2rem;">keyboard_arrow_left</span>
                    </a>
                </li>
            @else
                <li class="page-item">
                    <a class="page-link" href="{{ $paginator->previousPageUrl() }}" aria-label="Previous">
                        <span class="material-icons d-flex align-items-center" style="font-size: 1.2rem;">keyboard_arrow_left</span>
                    </a>
                </li>
            @endif

            {{-- Pagination Elements --}}
            @foreach ($elements as $element)
                {{-- "Three Dots" Separator --}}
                @if (is_string($element))
                    <li class="page-item disabled">
                        <a class="page-link" href="javascript:;">{{ $element }}</a>
                    </li>
                @endif

                {{-- Array Of Links --}}
                @if (is_array($element))
                    @foreach ($element as $page => $url)
                        @if ($page == $paginator->currentPage())
                            <li class="page-item active">
                                <a class="page-link text-white" href="javascript:;">{{ $page }}</a>
                            </li>
                        @else
                            <li class="page-item">
                                <a class="page-link" href="{{ $url }}">{{ $page }}</a>
                            </li>
                        @endif
                    @endforeach
                @endif
            @endforeach

            {{-- Next Page Link --}}
            @if ($paginator->hasMorePages())
                <li class="page-item">
                    <a class="page-link" href="{{ $paginator->nextPageUrl() }}" aria-label="Next">
                        <span class="material-icons d-flex align-items-center" style="font-size: 1.2rem;">keyboard_arrow_right</span>
                    </a>
                </li>
            @else
                <li class="page-item disabled">
                    <a class="page-link" href="javascript:;" aria-label="Next">
                        <span class="material-icons d-flex align-items-center" style="font-size: 1.2rem;">keyboard_arrow_right</span>
                    </a>
                </li>
            @endif
        </ul>
    </nav>
@endif
