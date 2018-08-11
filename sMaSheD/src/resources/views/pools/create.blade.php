@extends('layout')

@section('content')

    <div class="page-header">
        <h1>Pools: Create</h1>
    </div>

    @if ( isset($isChanged) )
        <div class="alert alert-success">Pool successfully created!</div>
    @endif
    <h2>A new entry</h2>
    <p>Add pool entry:</p>

    @include('pools.update', array( 'url' => url('pool'),
                                      'method' => 'POST',
                                      'cr_name' => old('name'),
                                      'cr_url' => old('url'),
                                      'buttext' => 'Create',
                                      'errs' => $errors
                                      ))

@endsection
