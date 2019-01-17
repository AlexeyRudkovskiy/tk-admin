@if($lastPage > 1)
<ul class="pagination">
    @foreach($links as $page => $link)
        <li class="page-item  @if($page === $currentPage) active @endif"><a href="{{ $link ?? 'javascript:' }}" class="page-link @if($link === null) disabled @endif">{{ $link !== null ? $page : '...' }}</a></li>
    @endforeach
</ul>
@endif