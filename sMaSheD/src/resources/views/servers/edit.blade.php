@extends('layout')

@section('content')

    <div class="page-header">
        <h1>Server: Edit</h1>
    </div>

    @if( !isset($server))
        <div class="alert alert-danger" role="alert">Requested server does not exist!</div>
    @else
        @if ( isset($isChanged) )
            <div class="alert alert-success">Server successfully updated!</div>
        @endif

        <h2>{{ $server->fqdn }}</h2>
        <p>Update server entry:</p>

        @include('servers.update', array( 'url' => url('server/'.$server->id),
                                  'method' => 'PATCH',
                                  'f_fqdn' => $server->fqdn,
                                  'pools' => $pools,
                                  'pool' => $server->pool_id,
                                  'buttext' => 'Update',
                                  'errs' => $errors
                                  ))
        <div class="clearfix"></div>
        <hr/>
        <p>Add port entry:</p>
        @include('ports.update', array( 'url' => url('port'),
                                  'method' => 'POST',
                                  'number' => old('number'),
                                  'crypto_id' => null,//$server->ports->first()->crypto_id,
                                  'cryptos'=> $cryptos,
                                  'server_id' => $server->id,
                                  'server_fqdn' => $server->fqdn,
                                  'servers'=> null,
                                  'buttext' => 'Create',
                                  'errs' => $errors
                                  ))
        <div class="clearfix"></div>
        <p>Port entries:</p>

        @include('ports.authlist', array( 'ports' => $server->ports) )

        <div class="clearfix"></div>
        <hr/>
        <p>Add address entry:</p>

        @include('addresses.update', array( 'url' => url('address'),
                          'method' => 'POST',
                          'address' => old('address'),
                          'server_id' => $server->id,
                          'server_fqdn' => $server->fqdn,
                          'servers'=> null,
                          'buttext' => 'Create',
                          'errs' => $errors
                          ))

        <div class="clearfix"></div>
        <p>Address entries:</p>

        @include('addresses.authlist', array( 'addresses' => $server->addresses) )

    @endif

@endsection
