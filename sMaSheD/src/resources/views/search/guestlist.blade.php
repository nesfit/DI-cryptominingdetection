<div class="col-md-12">
    <table class="table table-striped">
        <thead>
            <tr>
                <th>Server ID</th>
                <th>FQDN</th>
                <th>IP</th>
                <th>Pool</th>
                <th>Port</th>
                <th>Crypto</th>
            </tr>
        </thead>
        <tbody>
            @foreach($results as $result)
                <tr>
                    <td class="col-md-1">{{ $result->server_id }}</td>
                    <td class="col-md-3">{{ $result->fqdn }}</td>
                    <td class="col-md-2">{{ $result->address }}</td>
                    <td class="col-md-3">{{ $result->pool_name }}
                    <td class="col-md-2">{{ $result->number }}</td>
                    <td class="col-md-1">{{$result->abbreviation}}</td>
                </tr>
            @endforeach
        </tbody>
    </table>
</div>
