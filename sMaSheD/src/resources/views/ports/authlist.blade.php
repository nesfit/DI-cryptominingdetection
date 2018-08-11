<div class="col-md-12">
    <table  id="table"
            class="table table-striped"
            data-toggle="table"
            data-filter-control="true"
            data-show-export="true"
            data-click-to-select="true"
            data-pagination="true">
        <thead>
        <tr>
            <th data-field="id" data-filter-control="input" data-sortable="true">#</th>
            <th data-field="number" data-filter-control="input" data-sortable="true">Number</th>
            <th data-field="crypto" data-filter-control="input" data-sortable="true">Crypto</th>
            <th data-field="server" data-filter-control="input" data-sortable="true">Server</th>
            <th data-field="edit">Edit</th>
            <th data-field="del">Delete</th>
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
