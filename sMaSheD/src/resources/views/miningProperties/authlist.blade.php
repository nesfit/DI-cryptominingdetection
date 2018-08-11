<div class="col-md-12">
    <table id="table"
           class="table table-striped"
           data-toggle="table"
           data-filter-control="true"
           data-show-export="true"
           data-click-to-select="true"
           data-pagination="true">
        <thead>
            <tr>
                <th data-field="id" data-filter-control="input" data-sortable="true">#</th>
                <th data-field="serverId" data-filter-control="input" data-sortable="true">Server</th>
                <th data-field="address" data-filter-control="input" data-sortable="true">Address</th>
                <th data-field="port" data-filter-control="input" data-sortable="true">Port</th>
                <th data-field="protocol" data-filter-control="select" data-sortable="true">Protocol</th>
                <th data-field="status" data-filter-control="select" data-sortable="true">Status</th>
            </tr>
        </thead>
        <tbody>
            @foreach($miningProps as $miningProp)
                <tr>
                    <td class="col-md-1"><a href="{{ url('miningProp', $miningProp->id) }}">{{ $miningProp->id }}</a></td>
                    <td class="col-md-2">
                        <a href="{{ url('server', $miningProp->server_id) }}"> 
                        {{ $miningProp->server->fqdn }}
                    </td>
                    <td class="col-md-3">{{ $miningProp->address }}</td>
                    <td class="col-md-4">{{ $miningProp->port }}</td>
                    <td class="col-md-5">{{ $miningProp->protocol }}</td>
                    <td class="col-md-6">
                        <p hidden>{{ convertMiningPropStatus($miningProp->status) }}</p>
                        <p class="text-center">
                            @if($miningProp->status === 1)
                                <i class="fa fa-check-circle" style="font-size:18px;color:green;" ></i>
                            @elseif($miningProp->status === 0)
                                <i class="fa fa-times-circle" style="font-size:18px;color:red;" ></i>
                            @else
                                <i class="fa fa-question-circle" style="font-size:18px;color:orange;"></i>
                            @endif
                        </p>
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>
</div>


