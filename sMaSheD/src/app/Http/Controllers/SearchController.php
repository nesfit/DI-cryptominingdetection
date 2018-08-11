<?php

namespace App\Http\Controllers;

use App\Server;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use Mockery\Exception;


class SearchController extends Controller
{
    public function index()
    {
        return view('search.index');
    }

    public function query(Request $request)
    {
        $this->validate($request, [
            'query' => 'required'
        ]);

        $input = $request->input('query');

        if (preg_match("/^([0-9]{1,3}\.){3}[0-9]{1,3}(:[0-9]+)?$/", $input)) {

            $input_arr = explode(':', $input);

            if ( isset($input_arr[1]) ) {
                $results = DB::table('servers')
                    ->select('servers.id as server_id', 'servers.fqdn', 'addresses.id as address_id', 'addresses.address', 'pools.id as pool_id', 'pools.name as pool_name', 'ports.id as port_id', 'ports.number', 'cryptos.id as crypto_id', 'cryptos.abbreviation')
                    ->join('addresses', 'servers.id', '=', 'addresses.server_id')
                    ->join('ports', 'servers.id', '=', 'ports.server_id')
                    ->join('pools', 'servers.pool_id', '=', 'pools.id')
                    ->join('cryptos', 'ports.crypto_id', '=', 'cryptos.id')
                    ->where([
                        ['address', $input_arr[0]],
                        ['number', $input_arr[1]]
                    ])
                    ->get();
            } else {
                $results = DB::table('servers')
                    ->select('servers.id as server_id', 'servers.fqdn', 'addresses.id as address_id', 'addresses.address', 'pools.id as pool_id', 'pools.name as pool_name', 'ports.id as port_id', 'ports.number', 'cryptos.id as crypto_id', 'cryptos.abbreviation')
                    ->join('addresses', 'servers.id', '=', 'addresses.server_id')
                    ->join('ports', 'servers.id', '=', 'ports.server_id')
                    ->join('pools', 'servers.pool_id', '=', 'pools.id')
                    ->join('cryptos', 'ports.crypto_id', '=', 'cryptos.id')
                    ->where('address', $input)
                    ->get();
            }
        } else {
            $results = DB::table('servers')
                ->select('servers.id as server_id', 'servers.fqdn', 'addresses.id as address_id', 'addresses.address', 'pools.id as pool_id', 'pools.name as pool_name', 'ports.id as port_id', 'ports.number', 'cryptos.id as crypto_id', 'cryptos.abbreviation')
                ->join('addresses', 'servers.id', '=', 'addresses.server_id')
                ->join('ports', 'servers.id', '=', 'ports.server_id')
                ->join('pools', 'servers.pool_id', '=', 'pools.id')
                ->join('cryptos', 'ports.crypto_id', '=', 'cryptos.id')
                ->where('fqdn', $input)
                ->get();
        }

        if ($results->count() === 0) {
            $success = false;
        } else {
            $success = true;
        }

        return view('search.index')
            ->with('results', $results)
            ->with('success', $success);
    }
}
