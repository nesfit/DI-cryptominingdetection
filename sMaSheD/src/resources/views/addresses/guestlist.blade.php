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
                <th ata-field="server" data-filter-control="input" data-sortable="true">Server</th>
            </tr>
        </thead>
        <tbody>
            @foreach($addresses as $addr)
                <tr>
                    <td class="col-md-2">{{ $addr->id }}</td>
                    <td class="col-md-3">{{ $addr->address }}</td>
                    <td class="col-md-3">{{ $addr->server->fqdn }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>
</div>
