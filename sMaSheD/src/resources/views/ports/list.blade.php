<div class="col-md-12">
    <table class="table table-striped">
        <thead>
        <tr>
            <th>#</th>
            <th>Number</th>
            <th>Crypto</th>
            <th>Server</th>
            <th>Edit</th>
            <th>Delete</th>
        </tr>
        </thead>
        <tbody>
        @foreach($ports as $port)
            <tr>
                <td class="col-md-1"><a href="{{ url('port', $port->id ) }}">{{ $port->id }}</a></td>
                <td class="col-md-2">{{ $port->number }}</td>
                <td class="col-md-3"><a href="{{ url('crypto', $port->crypto->id) }}" >{{ $port->crypto->name }}</a></td>
                <td class="col-md-3"><a href="{{ url('server', $port->server->id) }}" >{{ $port->server->fqdn }}</a></td>
                <td class="col-md-1">
                    <a class="btn btn-link" href="{{ url('port/' . $port->id . '/edit' ) }}"><span class="glyphicon glyphicon-edit" aria-hidden="true"></span></a>
                </td>
                <td class="col-md-1">
                    @include('utils.delete', array( 'url' => url('port/' . $port->id . '/destroy'),
                                                    'text' => '<span class="glyphicon glyphicon-trash" aria-hidden="true"></span>'))
                </td>
            </tr>
        @endforeach
        </tbody>
    </table>
</div>