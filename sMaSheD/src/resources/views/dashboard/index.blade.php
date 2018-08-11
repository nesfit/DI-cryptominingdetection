@extends('layout')

@section('head')
    {!! Charts::assets() !!}
@endsection

@section('content')
    <div class="page-header">
        <h1>Dashboard: Index</h1>
    </div>

    <h2>Summary</h2>
    <div class="col-md-12">
        <div class="col-md-6">
            <table class="table table-striped">
                <thead>
                    <tr>
                        <th>Name</th>
                        <th>Count</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($counts as $count)
                        <tr>
                            <td class="col-md-4">
                                <a href="{{ url(array_keys($counts, $count)[0]) }}">{{ array_keys($counts, $count)[0] }} count</a>
                            </td>
                            <td class="col-md-2">
                                {{ $count }}
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        <div class="col-md-6">
            {!! $charts['summary_counts_chart']->render() !!}
        </div>
        <div class="clearfix"></div>
        <hr>
    </div>

    <h2>Cryptocurrencies</h2>
    <div class="col-md-12">
        <div class="col-md-6">
            {!! $charts['summary_currencies_chart']->render() !!}
        </div>
        <div class="col-md-6">
            <table class="table table-striped">
                <thead>
                    <tr>
                        <th>Currencies registered in the system</th>
                        <th>Count</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($used_currencies as $currency)
                        <tr>
                            <td class="col-md-4">
                                <a href="{{ url('crypto', $currency->crypto_id) }}">{{ $currency->name }}</a>
                            </td>
                            <td class="col-md-2">
                                {{ $currency->count }}
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        <div class="clearfix"></div>
        <hr>
    </div>

    <h2>Pools</h2>
    <div class="col-md-12">
        <div class="col-md-6">
            <table class="table table-striped">
                <thead>
                <tr>
                    <th>Pool Name</th>
                    <th>FQDN Count</th>
                    <th>IP Count</th>
                </tr>
                </thead>
                <tbody>
                @foreach($pool_stats as $stat)
                    <tr>
                        <td class="col-md-4">
                            <a href="{{ url('pool', $stat[0]->id) }}">{{ $stat[0]->name }}</a>
                        </td>
                        <td class="col-md-1">
                            {{ $stat[0]->fqdn_count }}
                        </td>
                        <td class="col-md-1">
                            {{ $stat[1]->addr_count }}
                        </td>
                    </tr>
                @endforeach
                </tbody>
            </table>
        </div>
        <div class="col-md-6">
            {!! $charts['summary_pools_chart']->render() !!}
        </div>
    </div>
@endsection
