@extends('layout')

@section('content')

    <div class="page-header">
        <h1>Mining properties: Show</h1>
    </div>
    @if( !isset($miningProp) )
        <div class="alert alert-danger" role="alert">Requested mining property does not exist!</div>
    @else

        <div class="row">
            <div class="col-sm-6">
                <div class="panel panel-primary">
                    <div class="panel-heading">
                        <h3 class="panel-title">Database detail</h3>
                    </div>
                    <div class="panel-body">
                        <table class="table">
                            <tbody>
                            <tr>
                                <th>Identifier</th>
                                <td>{{ $miningProp->id }}</td>
                            </tr>
                            <tr>
                                <th>IP</th>
                                <td>{{ $miningProp->address }}</td>
                            </tr>
                            <tr>
                                <th>Port</th>
                                <td>{{ $miningProp->port}}</td>
                            </tr>
                            <tr>
                                <th>Server</th>
                                <td>
                                    <a href="{{ url('miningProp', $miningProp->server_id) }}" >
                                        {{ $miningProp->server->fqdn }}
                                    </a>
                                </td>
                            </tr>
                            <tr>
                                <th>Protocol</th>
                                <td>{{ $miningProp->protocol }}</td>
                            </tr>
                            <tr>
                                <th>Status</th>
                                <td>
                                    @if($miningProp->status === 1)
                                        <i class="fa fa-check-circle" style="font-size:18px;color:green;" ></i>
                                    @elseif($miningProp->status === 0)
                                        <i class="fa fa-times-circle" style="font-size:18px;color:red;" ></i>
                                    @else
                                        <i class="fa fa-question-circle" style="font-size:18px;color:orange;"></i>
                                    @endif
                                    <!-- {{ convertMiningPropStatus($miningProp->status) }} -->
                                </td>
                            </tr>
                            <tr>
                                <th>Reason</th>
                                <td>{{ $miningProp->reason }}</td>
                            </tr>
                            <tr>
                                <th>Timestamps</th>
                                <td>{{ $miningProp->created_at }} <br> {{ $miningProp->updated_at }}</td>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

            <div>

                <div class="col-sm-6">
                    <div class="panel panel-info">
                        <div class="panel-heading">
                            <h3 class="panel-title">History (log)</h3>
                        </div>
                        <div class="panel-body">
                            <div class="text-center">
                                <!-- <a class="btn btn-danger" href="{{url('miningProp/' . $miningProp->id . '/clear') }}">Clear table</a> -->
                                <a class="hidden" href="{{url('miningProp/' . $miningProp->id . '/clear') }}">Clear table</a>
                            </div>
                            <table  class="table"
                                    data-click-to-select="true">
<!--                             <table  id="table"
                                    class="table table-striped"
                                    data-toggle="table"
                                    data-filter-control="true"
                                    data-show-export="true"
                                    data-pagination="true">
 -->                            
                                <thead>
<!--                                     <tr align="center">
                                        <th data-field="id" data-filter-control="input" data-sortable="true">#</th>
                                        <th data-field="status" data-filter-control="select" data-sortable="true">Status</th>
                                        <th data-field="reason" data-filter-control="input" data-sortable="true">Reason</th>
                                        <th data-field="created at" data-filter-control="input" data-sortable="true">Created-at</th>
                                    </tr> -->
                                    <tr>
                                        <th data-field="id">#</th>
                                        <th data-field="status">Status</th>
                                        <th data-field="reason">Reason</th>
                                        <th data-field="created at">Created-at</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($history as $record)
                                        <tr>
                                            <td class="col-md-1">{{ $record->id }}</td>
                                            <td class="col-md-2">
                                                <p hidden>{{ convertMiningPropStatus($record->status) }}</p>
                                                <p class="text-left">
                                                    @if($record->status === 1)
                                                        <i class="fa fa-check-circle" style="font-size:18px;color:green;" ></i>
                                                    @elseif($record->status === 0)
                                                        <i class="fa fa-times-circle" style="font-size:18px;color:red;" ></i>
                                                    @else
                                                        <i class="fa fa-question-circle" style="font-size:18px;color:orange;"></i>
                                                    @endif
                                                </p>
                                                <!-- {{ convertMiningPropStatus($miningProp->status) }} -->
                                            </td>
                                            <td class="col-md-3">{{ $record->reason }}</td>
                                            <td class="col-md-4">{{ $record->created_at }}</td>
                                        </tr>           
                                    @endforeach

                                </tbody>
                            </table>
                            <div align="center">
                                {{ $history->links() }}
                            </div>
                        </div>
                    </div>
                </div>
                
            </div>
        </div>
    @endif

@endsection