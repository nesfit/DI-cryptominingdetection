@if(count($errs))
    <div class="clearfix"></div>
    <div class="alert" role="alert">
        <ul class="list-group">
            @foreach($errs->all() as $err)
                <li class="list-group-item list-group-item-warning"> {{ $err }} </li>
            @endforeach
        </ul>
    </div>
@endif