<form action="{{ $url }}" method="POST">
    {{ csrf_field() }}
    {{ method_field( $method ) }}
    <div class="row col-md-12 form-group">
        <div class="col-md-4"> <input class="form-control" type="text" placeholder="Address" name="address" required value="{{ $address }}"> </div>
        <div class="col-md-5 selectContainer">
            @if(isset($servers))
                <select title="Choose a server" class="selectpicker" name="server_id" data-show-subtext="true" data-live-search="true" >
                    @foreach($servers as $serv)
                        <option @if( $server_id == $serv->id ) selected @endif value="{{ $serv->id }}">{{ $serv->fqdn }}</option>
                    @endforeach
                </select>
            @else
                <select class="selectpicker" name="server_id" >
                    <option selected value="{{ $server_id }}">{{ $server_fqdn }}</option>
                </select>
            @endif
        </div>
        <div class="col-md-2"> <button type="submit" class="btn btn-primary">{{ $buttext }}</button></div>
    </div>
</form>
@include('utils.valerr', array( 'errs' => $errs ))