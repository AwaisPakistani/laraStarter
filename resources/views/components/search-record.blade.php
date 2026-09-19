@props(['searchRoute'])
 <form action="{{ $searchRoute }}" method="get">
<input class="form-control" type="text" name="search" placeholder="Search..">
<button class="search-submit btn btn-icon" style="padding-right: 5px;">
    <em class="icon ni ni-search"></em>
</button>
</form>
