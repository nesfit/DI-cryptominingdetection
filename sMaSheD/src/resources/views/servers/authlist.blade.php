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

                <th data-field="fqdn" data-filter-control="input" data-sortable="true">FQDN</th>
                <th data-field="pool" data-filter-control="input" data-sortable="true">Pool</th>
                <th data-field="port" data-filter-control="input" data-sortable="true">Ports</th>
                <th data-field="address" data-filter-control="input" data-sortable="true">Addresses</th>
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
