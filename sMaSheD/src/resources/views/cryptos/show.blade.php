@extends('layout')

@section('content')

    <div class="page-header">
        <h1>Cryptos: Show</h1>
    </div>
    @if( !isset($crypto) )
        <div class="alert alert-danger" role="alert">Requested cryptocurrency does not exist!</div>
    @else
        <h2>{{ $crypto->name }}</h2>

        <div class="row">
            <div class="col-sm-5">
                <div class="panel panel-primary">
                    <div class="panel-heading">
                        <h3 class="panel-title">Database detail</h3>
                    </div>
                    <div class="panel-body">
                        <table class="table">
                            <tbody>
                            <tr>
                                <th>Identifier</th>
                                <td>{{ $crypto->id }}</td>
                            </tr>
                            <tr>
                                <th>Abbreviation</th>
                                <td>{{ $crypto->abbreviation }}</td>
                            </tr>
                            <tr>
                                <th>Name</th>
                                <td>{{ $crypto->name }}</td>
                            </tr>
                            <tr>
                                <th>URL</th>
                                <td>{{ $crypto->url }}</td>
                            </tr>
                            <tr>
                                <th>Timestamps</th>
                                <td>{{ $crypto->created_at }} <br> {{ $crypto->updated_at }}</td>
                            </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
            <div class="col-sm-1"></div>
            <div class="col-sm-2">
                <div class="panel panel-default">
                    <div class="panel-heading">
                        <h3 class="panel-title">Actions</h3>
                    </div>
                    <div class="panel-body">
                        <a class="btn btn-info" href="{{ url('crypto/' . $crypto->id . '/edit') }}"><span class="glyphicon glyphicon-edit" aria-hidden="true"></span> Edit</a>
                        <br>
                        @include('utils.delete', array( 'url' => url('crypto/' . $crypto->id . '/destroy'),
                                                        'class' => 'btn btn-danger',
                                                        'text' => '<span class="glyphicon glyphicon-trash" aria-hidden="true"></span> Delete'))
                    </div>
                </div>
            </div>
        </div>
    @endif

@endsection