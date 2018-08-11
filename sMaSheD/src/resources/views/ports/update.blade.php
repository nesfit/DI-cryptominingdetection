<form action="{{ $url }}" method="POST">
    {{ csrf_field() }}
    {{ method_field( $method ) }}
    <div class="row col-md-12 form-group">
        <div class="col-md-1"> <input class="form-control" type="text" placeholder="Port" name="number" required value="{{ $number }}"> </div>
        <div class="col-md-3 selectContainer">
            <select title="Choose a crypto" class="selectpicker" name="crypto_id" data-show-subtext="true" data-live-search="true" >
                @foreach($cryptos as $cry)
                    <option @if( $crypto_id == $cry->id ) selected @endif value="{{ $cry->id }}">{{ $cry->name }} ({{ $cry->abbreviation }})</option>
                @endforeach
            </select>
        </div>
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