<?php

namespace App\Http\Controllers;

use App\Address;
use App\Server;
use App\Pool;
use App\Crypto;
use App\Port;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;


class ServerController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth', ['except' => ['index', 'json']]);
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $servers = Server::all()->reverse();
        $pools = Pool::hydrate( DB::table('pools')->orderBy('name')->get()->toArray() );
        return view('servers.index', compact('servers','pools'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $pools = Pool::hydrate( DB::table('pools')->orderBy('name')->get()->toArray() );
        return view('servers.create', compact('pools'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $this->validate($request, [
            'fqdn' => 'required|unique:servers',
            'pool_id' => 'required'
        ]);

        $server = new Server;

        $pieces = explode(":", $request->fqdn);

        $result = isset($pieces[1]);
        if ( $result ) {
            $request->fqdn = $pieces[0];
        }

        $this->assignFromRequest($request, $server);
        $server->save();

        $this->updateAddresses($server);

        //Port exists
        if ( $result ) {
            $port = new Port;
            $port->server_id = $server->id;
            $port->number = $pieces[1];
            $port->crypto_id = 1;
            $port->save();
        }

        $servers = Server::all()->reverse();
        $isChanged = true;

        $pools = Pool::hydrate( DB::table('pools')->orderBy('name')->get()->toArray() );

        return view('servers.index', compact('servers','isChanged','pools'));
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $server = Server::find($id);
        $pools = Pool::hydrate( DB::table('pools')->orderBy('name')->get()->toArray() );
        return view('servers.show', compact('server', 'pools'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $server = Server::find($id);
        $pools = Pool::hydrate( DB::table('pools')->orderBy('name')->get()->toArray() );
        $cryptos = Crypto::hydrate( DB::table('cryptos')->orderBy('name')->get()->toArray() );
        return view('servers.edit', compact('server','pools','cryptos'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $this->validate($request, [
            'fqdn' => 'required',
            'pool_id' => 'required'
        ]);

        $server = Server::find($id);

        if (isset($server)) {
            $this->assignFromRequest($request, $server);

            $this->updateAddresses($server);

            $server->save();
            $isChanged = true;
        }

        $pools = Pool::hydrate( DB::table('pools')->orderBy('name')->get()->toArray() );
        $cryptos = Crypto::hydrate( DB::table('cryptos')->orderBy('name')->get()->toArray() );
        return view('servers.edit', compact('server','isChanged', 'pools', 'cryptos'));
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        Server::destroy($id);

        $isDeleted = ture;

        $servers = Server::all()->reverse();
        $pools = Pool::hydrate( DB::table('pools')->orderBy('name')->get()->toArray() );
        return view('servers.index', compact('servers','pools', 'isDeleted'));
    }

    /**
     * @param Request $request
     * @param $server
     */
    protected function assignFromRequest(Request $request, Server $server)
    {
        $server->fqdn = $request->fqdn;
        $server->pool_id = $request->pool_id;
    }

    /**
     * @param $server
     * @param $addrs
     */
    protected function updateAddresses($server)
    {
        $col = Address::all()->where('server_id', $server->id);
        $addrs = gethostbynamel($server->fqdn);
        foreach ($addrs as $addr) {
            //Continue if address already in table
            if ($col->contains('address', $addr)) continue;
            //otherwise add a new address
            $a = new Address;
            $a->server_id = $server->id;
            $a->address = $addr;
            $a->save();
        }
    }

    /**
     * Display database entries in json format
     *
     * @return \Illuminate\Http\Response
     */
    public function json()
    {
        $servers = Server::all();
        
        // $serversJson = $servers->toJson();
        // $serversJson = [];
        // foreach ($servers as $server)
        // {
        //     $serversJson[] = $server->toJson();
        // }

        return response()->json($servers);
    }
}
