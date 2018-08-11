<form action="{{ $url }}" method="POST">
    {{ csrf_field() }}
    {{ method_field( $method ) }}
    <div class="row col-md-10 form-group">
        <div class="col-md-5"> <input class="form-control" type="text" placeholder="Fully Qualified Domain Name" name="fqdn" required value="{{ $f_fqdn }}"> </div>
            <div class="col-md-3 selectContainer">
                <select title="Choose a pool" class="selectpicker" name="pool_id" data-show-subtext="true" data-live-search="true" >
                    @foreach($pools as $po)
                        <option @if( $pool == $po->id ) selected @endif value="{{ $po->id }}">{{ $po->name }}</option>
                    @endforeach
                </select>
            </div>
        <div class="col-md-2"> <button type="submit" class="btn btn-primary">{{ $buttext }}</button></div>
    </div>
</form>
@include('utils.valerr', array( 'errs' => $errs ))

