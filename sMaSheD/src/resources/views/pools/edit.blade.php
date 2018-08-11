@extends('layout')

@section('content')

    <div class="page-header">
        <h1>Pools: Edit</h1>
    </div>

    @if( !isset($pool))
        <div class="alert alert-danger" role="alert">Requested pool does not exist!</div>
    @else
        @if ( isset($isChanged) )
            <div class="alert alert-success">Pool successfully updated!</div>
        @endif

        <h2>{{ $pool->name }}</h2>
        <p>Update pool entry:</p>

        @include('pools.update', array( 'url' => url('pool/'.$pool->id),
                                  'method' => 'PATCH',
                                  'cr_abbr' => $pool->abbreviation,
                                  'cr_name' => $pool->name,
                                  'cr_url' => $pool->url,
                                  'buttext' => 'Update',
                                  'errs' => $errors
                                  ))

    @endif

@endsection