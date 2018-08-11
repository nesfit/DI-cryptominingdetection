<div class="col-md-12">
    <table class="table table-striped">
        <thead>
        <tr>
            <th>#</th>
            <th>IP address</th>
            <th>Server</th>
            <th>Edit</th>
            <th>Delete</th>
        </tr>
        </thead>
        <tbody>
        @foreach($addresses as $addr)
            <tr>
                <td class="col-md-1"><a href="{{ url('port', $addr->id ) }}">{{ $addr->id }}</a></td>
                <td class="col-md-2">{{ $addr->address }}</td>
                <td class="col-md-3"><a href="{{ url('server', $addr->server->id) }}" >{{ $addr->server->fqdn }}</a></td>
                <td class="col-md-1">
                    <a class="btn btn-link" href="{{ url('address/' . $addr->id . '/edit' ) }}"><span class="glyphicon glyphicon-edit" aria-hidden="true"></span></a>
                </td>
                <td class="col-md-1">
                    @include('utils.delete', array( 'url' => url('address/' . $addr->id . '/destroy'),
                                                    'text' => '<span class="glyphicon glyphicon-trash" aria-hidden="true"></span>'))
                </td>
            </tr>
        @endforeach
        </tbody>
    </table>
</div>