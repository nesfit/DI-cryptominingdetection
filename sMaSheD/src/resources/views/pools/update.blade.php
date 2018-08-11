<form action="{{ $url }}" method="POST">
    {{ csrf_field() }}
    {{ method_field( $method ) }}
    <div class="row col-md-10">
        <div class="col-md-2"> <input class="form-control" type="text" placeholder="Name" name="name" required value="{{ $cr_name }}"> </div>
        <div class="col-md-5"> <input class="form-control" type="text" placeholder="Official webpage" name="url" required value="{{ $cr_url }}"> </div>
        <div class="col-md-3"> <button type="submit" class="btn btn-primary">{{ $buttext }}</button></div>
    </div>
</form>
@include('utils.valerr', array( 'errs' => $errs ))