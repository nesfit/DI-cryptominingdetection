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
                <th data-field="address" data-filter-control="input" data-sortable="true">IP address</th>
                <th data-field="server" data-filter-control="input" data-sortable="true">Server</th>
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
