@extends('layout')

@section('content')

    <div class="page-header">
        <h1>Cryptos: Edit</h1>
    </div>

    @if( !isset($crypto))
        <div class="alert alert-danger" role="alert">Requested cryptocurrency does not exist!</div>
    @else
        @if ( isset($isChanged) )
            <div class="alert alert-success">Cryptocurrency successfully updated!</div>
        @endif

        <h2>{{ $crypto->name }}</h2>
        <p>Update cryptocurrency entry:</p>

        @include('cryptos.update', array( 'url' => url('crypto/'.$crypto->id),
                                  'method' => 'PATCH',
                                  'cr_abbr' => $crypto->abbreviation,
                                  'cr_name' => $crypto->name,
                                  'cr_url' => $crypto->url,
                                  'buttext' => 'Update',
                                  'errs' => $errors
                                  ))


    @endif

@endsection