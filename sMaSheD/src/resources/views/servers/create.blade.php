@extends('layout')

@section('content')

    <div class="page-header">
        <h1>Servers: Create</h1>
    </div>

    @if ( isset($isChanged) )
        <div class="alert alert-success">Server successfully created!</div>
    @endif
    <h2>A new entry</h2>
    <p>Add server entry:</p>

    @include('servers.update', array( 'url' => url('server'),
                                      'method' => 'POST',
                                      'f_fqdn' => old('fqdn'),
                                      'pools' => $pools,
                                      'pool' => old('pool'),
                                      'buttext' => 'Create',
                                      'errs' => $errors
                                      ))
@endsection