@extends('layout')

@section('content')

    <div class="page-header">
        <h1>Ports: Show</h1>
    </div>
    @if( !isset($port) )
        <div class="alert alert-danger" role="alert">Requested port does not exist!</div>
    @else
        <h2>{{ $port->name }}</h2>

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
                                <td>{{ $port->id }}</td>
                            </tr>
                            <tr>
                                <th>Number</th>
                                <td>{{ $port->number }}</td>
                            </tr>
                            <tr>
                                <th>Server</th>
                                <td><a href="{{ url('port', $port->server_id) }}" >{{ $port->server->fqdn }}</a></td>
                            </tr>
                            <tr>
                                <th>Timestamps</th>
                                <td>{{ $port->created_at }} <br> {{ $port->updated_at }}</td>
                            </tr>
                            <tr>
                                <th>Actions</th>
                                <td>
                                    <a class="btn btn-info" href="{{ url('port/' . $port->id . '/edit') }}"><span class="glyphicon glyphicon-edit" aria-hidden="true"></span> Edit</a>
                                    @include('utils.delete', array( 'url' => url('port/' . $port->id . '/destroy'),
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