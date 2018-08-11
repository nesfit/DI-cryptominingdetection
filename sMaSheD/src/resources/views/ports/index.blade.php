@extends('layout')

@section('content')
    <div class="page-header">
        <h1>Port: Index</h1>
    </div>

    @if ( isset($isChanged) )
        <div class="alert alert-success">Port saved successfully!</div>
    @elseif ( isset($isDeleted) )
        <div class="alert alert-success">Port deleted successfully!</div>
    @endif

    @if ( Auth::check() )
        <h2>Create</h2>
        <p>Add port entry:</p>

        @include('ports.update', array( 'url' => url('port'),
                                  'method' => 'POST',
                                  'number' => old('number'),
                                  'crypto_id' => old('crypto_id'),
                                  'cryptos'=> $cryptos,
                                  'server_id' => old('server_id'),
                                  'server_fqdn' => null,
                                  'servers'=> $servers,
                                  'buttext' => 'Create',
                                  'errs' => $errors
                                  ))

        <div class="clearfix"></div>
        <hr/>
    @endif

    <h2>List</h2>
    <p>All currently recognized ports in system.</p>

    @if ( Auth::check() )
        @include('ports.authlist', array( 'ports' => $ports) )
    @else
        @include('ports.guestlist', array( 'ports' => $ports) )
    @endif

@endsection
