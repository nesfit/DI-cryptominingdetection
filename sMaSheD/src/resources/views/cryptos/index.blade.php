@extends('layout')

@section('content')
    <div class="page-header">
        <h1>Cryptos: Index</h1>
    </div>

    @if ( isset($isChanged) )
        <div class="alert alert-success">Crypto saved successfully!</div>
    @elseif ( isset($isDeleted) )
        <div class="alert alert-success">Crypto deleted successfully!</div>
    @endif

    @if ( Auth::check() )
        <h2>Create</h2>
        <p>Add cryptocurrency entry:</p>

        @include('cryptos.update', array( 'url' => url('crypto'),
                                          'method' => 'POST',
                                          'cr_abbr' => old('abbreviation'),
                                          'cr_name' => old('name'),
                                          'cr_url' => old('url'),
                                          'buttext' => 'Create',
                                          'errs' => $errors
                                          ))

        <div class="clearfix"></div>
        <hr/>
    @endif

    <h2>List</h2>
    <p>All currently recognized cryptocurrencies in system.</p>

    @if ( Auth::check() )
        @include('cryptos.authlist', array( 'cryptos' => $cryptos ) )
    @else
        @include('cryptos.guestlist', array( 'cryptos' => $cryptos ) )
    @endif

@endsection
