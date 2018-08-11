@extends('layout')

@section('content')

    <div class="page-header">
        <h1>Servers: Show</h1>
    </div>
    @if( !isset($server) )
        <div class="alert alert-danger" role="alert">Requested pool does not exist!</div>
    @else
        <h2>{{ $server->name }}</h2>

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
                                <td>{{ $server->id }}</td>
                            </tr>
                            <tr>
                                <th>Name</th>
                                <td>{{ $server->fqdn }}</td>
                            </tr>
                            <tr>
                                <th>Pool</th>
                                <td><a href="{{ url('pool', $server->pool_id) }}" >{{ $server->pool->name }}</a></td>
                            </tr>
                            <tr>
                                <th>Timestamps</th>
                                <td>{{ $server->created_at }} <br> {{ $server->updated_at }}</td>
                            </tr>
                            <tr>
                                <th>Actions</th>
                                <td>
                                    <a class="btn btn-info" href="{{ url('server/' . $server->id . '/edit') }}"><span class="glyphicon glyphicon-edit" aria-hidden="true"></span> Edit</a>
                                    @include('utils.delete', array( 'url' => url('server/' . $server->id . '/destroy'),
                                                                    'class' => 'btn btn-danger',
                                                                    'text' => '<span class="glyphicon glyphicon-trash" aria-hidden="true"></span> Delete'))
                                </td>
                            </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    @endif

@endsection