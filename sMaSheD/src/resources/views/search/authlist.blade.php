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
                    <td class="col-md-1">
                        <a href="{{ url('server', $result->server_id ) }}">{{ $result->server_id }}</a>
                    </td>
                    <td class="col-md-3">
                        {{ $result->fqdn }}
                    </td>
                    <td class="col-md-2">
                        <a href="{{ url('address', $result->address_id) }}">{{ $result->address }}</a>
                    </td>
                    <td class="col-md-3">
                        <a href="{{ url('pool', $result->pool_id) }}" >{{ $result->pool_name }}</a>
                    </td>
                    <td class="col-md-2">
                        <a href="{{ url('port', $result->port_id) }}">{{ $result->number }}</a>
                    </td>
                    <td class="col-md-1">
                        <a href="{{ url('crypto', $result->crypto_id) }}">{{$result->abbreviation}}</a>
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>
</div>
