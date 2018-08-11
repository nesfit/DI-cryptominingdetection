@extends('layout')

@section('content')

    <div class="page-header">
        <h1>Port: Create</h1>
    </div>

    @if ( isset($isChanged) )
        <div class="alert alert-success">Port successfully created!</div>
    @endif
    <h2>A new entry</h2>
    <p>Add port entry:</p>


    @include('ports.update', array( 'url' => url('port'),
                      'method' => 'POST',
                      'number' => old('number'),
                      'crypto_id' => old('crypto_id'),
                      'cryptos'=> $cryptos,
                      'server_id' => old('server_id'),
                      'server_fqdn' => null,
                      'servers'=> $servers,
                      'buttext' => 'Update',
                      'errs' => $errors
                      ))
@endsection