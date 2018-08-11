@extends('layout')

@section('content')
    <div class="page-header">
        <h1>Servers: Index</h1>
    </div>

    @if ( isset($isChanged) )
        <div class="alert alert-success">Server saved successfully!</div>
    @elseif ( isset($isDeleted) )
        <div class="alert alert-success">Server deleted successfully!</div>
    @endif

    @if ( Auth::check() )
        <h2>Create</h2>
        <p>Add server entry:</p>

        @include('servers.update', array( 'url' => url('server'),
                                           'method' => 'POST',
                                           'f_fqdn' => old('fqdn'),
                                           'pools' => $pools,
                                           'pool' => old('pool_id'),
                                           'buttext' => 'Create',
                                           'errs' => $errors
                                           ))

        <div class="clearfix"></div>
        <hr/>
    @endif

    <h2>List</h2>
    <p>All currently recognized pools in system.</p>

    @if ( Auth::check() )
        @include('servers.authlist', array( 'servers' => $servers ) )
    @else
        @include('servers.guestlist', array( 'servers' => $servers ) )
    @endif

@endsection
