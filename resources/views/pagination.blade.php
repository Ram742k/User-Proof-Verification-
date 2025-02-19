@if ($users->lastPage() > 1)
<nav>
    <ul class="pagination justify-content-center">
        <!-- Previous Page -->
        <li class="page-item {{ ($users->currentPage() == 1) ? ' disabled' : '' }}">
            <a class="page-link" href="{{ $users->previousPageUrl() }}" data-page="{{ $users->currentPage() - 1 }}">«</a>
        </li>

        <!-- Pagination Links -->
        @for ($i = 1; $i <= $users->lastPage(); $i++)
            <li class="page-item {{ ($users->currentPage() == $i) ? ' active' : '' }}">
                <a class="page-link" href="{{ $users->url($i) }}" data-page="{{ $i }}">{{ $i }}</a>
            </li>
        @endfor

        <!-- Next Page -->
        <li class="page-item {{ ($users->currentPage() == $users->lastPage()) ? ' disabled' : '' }}">
            <a class="page-link" href="{{ $users->nextPageUrl() }}" data-page="{{ $users->currentPage() + 1 }}">»</a>
        </li>
    </ul>
</nav>
@endif
