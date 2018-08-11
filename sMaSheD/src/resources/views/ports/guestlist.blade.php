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
        </tr>
        </thead>
        <tbody>
        @foreach($ports as $port)
            <tr>
                <td class="col-md-1">{{ $port->id }}</td>
                <td class="col-md-2">{{ $port->number }}</td>
                <td class="col-md-3">{{ $port->crypto->name }}</td>
                <td class="col-md-5">{{ $port->server->fqdn }}</td>
            </tr>
        @endforeach
        </tbody>
    </table>
</div>
