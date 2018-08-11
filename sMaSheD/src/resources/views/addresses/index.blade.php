@extends('layout')

@section('content')
    <div class="page-header">
        <h1>Address: Index</h1>
    </div>

    @if ( isset($isChanged) )
        <div class="alert alert-success">Address saved successfully!</div>
    @elseif ( isset($isDeleted) )
        <div class="alert alert-success">Address deleted successfully!</div>
    @endif

    @if ( Auth::check() )
        <h2>Create</h2>
        <p>Add address entry:</p>

        @include('addresses.update', array( 'url' => url('address'),
                                  'method' => 'POST',
                                  'address' => old('address'),
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
    <p>All currently recognized addresses in system.</p>

    @if ( Auth::check() )
        @include('addresses.authlist', array( 'addresses' => $addresses ) )
    @else
        @include('addresses.guestlist', array( 'addresses' => $addresses ) )
    @endif

@endsection