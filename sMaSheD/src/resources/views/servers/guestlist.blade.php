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
                <th data-field="ports" data-filter-control="input" data-sortable="true">Ports</th>
                <th data-field="addresses" data-filter-control="input" data-sortable="true">Addresses</th>
            </tr>
        </thead>
        <tbody>
            @foreach($servers as $server)
                <tr>
                    <td class="col-md-1">{{ $server->id }}</td>
                    <td class="col-md-3">{{ $server->fqdn }}</td>
                    <td class="col-md-3">{{ $server->pool->name }}</td>
                    <td class="col-md-2">
                        @foreach ($server->ports as $port)
                            {{ $port->number }} {{$port->crypto->abbreviation}}<br/>
                        @endforeach
                    </td>
                    <td class="col-md-2">
                        @foreach ($server->addresses as $addr)
                            {{ $addr->address}}<br/>
                        @endforeach
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>
</div>
