<div class="col-md-12">
    <table class="table table-striped">
        <thead>
        <tr>
            <th>#</th>
            <th>FQDN</th>
            <th>Pool</th>
            <th>Ports</th>
            <th>Addresses</th>
            <th>Edit</th>
            <th>Delete</th>
        </tr>
        </thead>
        <tbody>
        @foreach($servers as $server)
            <tr>
                <td class="col-md-1"><a href="{{ url('server', $server->id ) }}">{{ $server->id }}</a></td>
                <td class="col-md-3">{{ $server->fqdn }}</td>
                <td class="col-md-3"><a href="{{ url('pool', $server->pool_id) }}" >{{ $server->pool->name }}</a></td>
                <td class="col-md-2">
                    @foreach ($server->ports as $port)
                        <a href="{{'port/'.$port->id}}">{{ $port->number }} {{$port->crypto->abbreviation}}</a><br/>
                    @endforeach
                </td>
                <td class="col-md-2">
                    @foreach ($server->addresses as $addr)
                        <a href="{{'address/'.$addr->id}}">{{ $addr->address}}</a><br/>
                    @endforeach
                </td>
                <td class="col-md-1">
                    <a class="btn btn-link" href="{{ url('server/' . $server->id . '/edit' ) }}"><span class="glyphicon glyphicon-edit" aria-hidden="true"></span></a>
                </td>
                <td class="col-md-1">
                    @include('utils.delete', array( 'url' => url('server/' . $server->id . '/destroy'),
                                                    'text' => '<span class="glyphicon glyphicon-trash" aria-hidden="true"></span>'))
                </td>
            </tr>
        @endforeach
        </tbody>
    </table>
</div>
