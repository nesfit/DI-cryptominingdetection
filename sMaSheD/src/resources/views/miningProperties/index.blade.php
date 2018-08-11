@extends('layout')

@section('content')
    <div class="page-header">
        <h1>Mining Properties: Index</h1>
    </div>

     @if ( Auth::check() )
        <h2>Actions:</h2>
        <a class="btn btn-primary" href="{{ 'miningProp/refresh' }}">Refresh data</a>
        <!-- <a class="btn btn-danger" href="{{ 'miningProp/clear' }}">Clear table</a> -->
        <a class="hidden" href="{{ 'miningProp/clear' }}">Clear table</a>
        <a class="btn btn-info" href="{{ 'miningProp/json' }}">JSON</a>
        <div class="clearfix"></div>
        <hr/>
    @endif

    <h2>List</h2>

    <p>All currently recognized mining properties in system.</p>
    @if ( Auth::check() )
        @include('miningProperties.authlist', array( 'miningProps' => $miningProps ))
    @else
        @include('miningProperties.guestlist', array( 'miningProps' => $miningProps ))
    @endif

@endsection
