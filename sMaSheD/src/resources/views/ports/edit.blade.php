@extends('layout')

@section('content')

    <div class="page-header">
        <h1>Ports: Edit</h1>
    </div>

    @if( !isset($port))
        <div class="alert alert-danger" role="alert">Requested port does not exist!</div>
    @else
        @if ( isset($isChanged) )
            <div class="alert alert-success">Port successfully updated!</div>
        @endif

        <h2>{{ $port->number }} at {{ $port->server->fqdn }}</h2>
        <p>Update port entry:</p>

        @include('ports.update', array( 'url' => url('port/'.$port->id),
                          'method' => 'PATCH',
                          'number' => $port->number,
                          'crypto_id' => $port->crypto->id,
                          'cryptos'=> $cryptos,
                          'server_id' => $port->server->id,
                          'server_fqdn' => null,
                          'servers'=> $servers,
                          'buttext' => 'Update',
                          'errs' => $errors
                          ))
    @endif

@endsection