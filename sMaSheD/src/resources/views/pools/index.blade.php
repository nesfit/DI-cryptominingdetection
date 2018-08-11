@extends('layout')

@section('content')
    <div class="page-header">
        <h1>Pools: Index</h1>
    </div>

    @if ( isset($isChanged) )
        <div class="alert alert-success">Pool saved successfully!</div>
    @elseif ( isset($isDeleted) )
        <div class="alert alert-success">Pool deleted successfully!</div>
    @endif

    @if ( Auth::check() )
        <h2>Create</h2>
        <p>Add pool entry:</p>

        @include('pools.update', array( 'url' => url('pool'),
                                          'method' => 'POST',
                                          'cr_name' => old('name'),
                                          'cr_url' => old('url'),
                                          'buttext' => 'Create',
                                          'errs' => $errors
                                          ))

        <div class="clearfix"></div>
        <hr/>
    @endif

    <h2>List</h2>
    <p>All currently recognized pools in system.</p>

    @if ( Auth::check() )
        @include('pools.authlist', array( 'pools' => $pools ) )
    @else
        @include('pools.guestlist', array( 'pools' => $pools ) )
    @endif

@endsection
