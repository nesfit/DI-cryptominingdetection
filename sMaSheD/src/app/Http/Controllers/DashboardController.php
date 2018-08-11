<?php

namespace App\Http\Controllers;

use DB;
use Illuminate\Http\Request;

use Charts;

class DashboardController extends Controller
{
    public function index()
    {
        $counts['pool'] = DB::table('pools')->count();
        $counts['crypto'] = DB::table('cryptos')->count();
        $counts['server'] = DB::table('servers')->count();
        $counts['port'] = DB::table('ports')->select('number')->groupBy('number')->count();
        $counts['address'] = DB::table('addresses')->count();

        $charts['summary_counts_chart'] = Charts::create('bar', 'highcharts')
            ->title('Count')
            ->dimensions(0, 0)
            ->colors(['#64B5F6', '#2196F3', '#1976D2'])
            ->elementLabel('Total')
            ->labels(['Pools', 'Cryptos', 'Servers', 'Ports', 'Addresses'])
            ->values([$counts['pool'], $counts['crypto'], $counts['server'], $counts['port'], $counts['address']]);

        $used_currencies = DB::table('ports')
            ->select('ports.crypto_id', 'cryptos.name', DB::raw('count(*) as count'))
            ->join('cryptos', 'ports.crypto_id', '=', 'cryptos.id')
            ->groupBy('crypto_id')
            ->get()->toArray();

        $currency_labels = [];
        $currency_counts = [];

        foreach ($used_currencies as $currency) {
            $currency_labels[] = $currency->name;
            $currency_counts[] = $currency->count;
        }

        $charts['summary_currencies_chart'] = Charts::create('pie', 'highcharts')
            ->title('Currencies')
            ->dimensions(0, 0)
            ->colors(['#64B5F6', '#2196F3', '#1976D2'])
            ->labels($currency_labels)
            ->values($currency_counts);

        $tmp1 = DB::table('pools')
            ->select('pools.id', 'pools.name', DB::raw('count(servers.fqdn) as fqdn_count'))
            ->join('servers', 'pools.id', '=', 'servers.pool_id')
            ->groupBy('pools.id')
            ->get();

        $tmp2 = DB::table('pools')
            ->select('pools.id', DB::raw('count(addresses.address) as addr_count'))
            ->join('servers', 'pools.id', '=', 'servers.pool_id')
            ->join('addresses', 'servers.id', '=', 'addresses.server_id')
            ->groupBy('pools.id')
            ->get();

        $pool_stats = $tmp1->zip($tmp2)->toArray();
        $pool_labels = [];
        $pool_fqdn_counts = [];
        $pool_addr_counts = [];

        foreach($pool_stats as $stat) {
            $pool_fqdn_counts[] = $stat[0]->fqdn_count;
            $pool_addr_counts[] = $stat[1]->addr_count;
            $pool_labels[] = $stat[0]->name;
        }

        $charts['summary_pools_chart'] = Charts::multi('bar', 'highcharts')
            ->title('Pools')
            ->dimensions(0, 0)
            ->colors(['#64B5F6', '#2196F3'])
            ->labels($pool_labels)
            ->dataset('FQDN', $pool_fqdn_counts)
            ->dataset('IP', $pool_addr_counts);

        return view('dashboard.index')
            ->with('charts', $charts)
            ->with('counts', $counts)
            ->with('used_currencies', $used_currencies)
            ->with('pool_stats', $pool_stats);
    }
}
