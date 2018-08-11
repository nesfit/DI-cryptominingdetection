@extends('layout')

@section('content')
    <div class="page-header">
        <h1>Mining Server Detector of Cryptocurrency Pools</h1>
    </div>
    <h2>Search</h2>
    <p>Query mining servers by IP or FQDN:</p>

    @if ( isset($success) && $success === true )
        @if ( Auth::guest() )
            <div class="alert alert-warning">
                <span class="glyphicon glyphicon-lock"></span>  Some features are unavailable for unauthenticated users
            </div>
        @endif
    @endif

    <form action="{{ url('/') }}" method="POST">
        {{ csrf_field() }}
        {{ method_field( "POST" ) }}
        <div class="row col-md-10">
            <div class="col-md-7">
                <input class="form-control" placeholder="IP[:port] | FQDN" name="query">
            </div>
            <div class="col-md-3">
                <button class="btn btn-primary">Search</button>
            </div>
        </div>
    </form>
    @include('utils.valerr', array( 'errs' => $errors ))
    <div class="clearfix"></div>

    @if ( isset($success) )
        @if ( $success === true )
            <hr>
            <p>Following entries from database match your query:</p>

            @if ( Auth::check() )
                @include('search.authlist', ['results' => $results]);
            @else
                @include('search.guestlist', ['results' => $results]);
            @endif
        @else
            <hr>
            <div class="alert alert-danger">No entries matching your query were found.</div>
        @endif
    @endif

@endsection
