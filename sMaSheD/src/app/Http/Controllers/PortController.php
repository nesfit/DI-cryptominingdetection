<?php

namespace App\Http\Controllers;

use App\Port;
use App\Crypto;
use App\Server;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;

class PortController extends Controller
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
        $ports = Port::all();
        $servers = Server::all();
        $cryptos = Crypto::hydrate( DB::table('cryptos')->orderBy('name')->get()->toArray() );
        return view('ports.index', compact('ports','servers', 'cryptos'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $servers = Server::all();
        $cryptos = Crypto::hydrate( DB::table('cryptos')->orderBy('name')->get()->toArray() );

        return view('ports.create', compact('cryptos', 'servers'));
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
            'number' => 'required',
            'crypto_id' => 'required',
            'server_id' => 'required'
        ]);

        $port = new Port;

        $isChanged = false;

        if ( isset($port)) {
            $this->assignFromRequest($request, $port);
            $port->save();
            $isChanged = true;
        }

        $ports = Port::all();
        $servers = Server::all();
        $cryptos = Crypto::hydrate( DB::table('cryptos')->orderBy('name')->get()->toArray() );
        return view('ports.index', compact('ports','servers', 'cryptos', 'isChanged'));
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $port = Port::find($id);
        return view('ports.show', compact('port'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $port = Port::find($id);
        $servers = Server::all();
        $cryptos = Crypto::hydrate( DB::table('cryptos')->orderBy('name')->get()->toArray() );
        return view('ports.edit', compact('port', 'servers', 'cryptos'));
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
            'number' => 'required',
            'crypto_id' => 'required',
            'server_id' => 'required'
        ]);

        $port = Port::find($id);

        if ( isset($port)) {
            $this->assignFromRequest($request, $port);

            $port->save();
            $isChanged = true;
        }

        $servers = Server::all();
        $cryptos = Crypto::hydrate( DB::table('cryptos')->orderBy('name')->get()->toArray() );
        return view('ports.edit', compact('port','isChanged','servers','cryptos'));
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        Port::destroy($id);

        $isDeleted = true;

        $ports = Port::all();
        $servers = Server::all();
        $cryptos = Crypto::hydrate( DB::table('cryptos')->orderBy('name')->get()->toArray() );
        return view('ports.index', compact('ports','servers', 'cryptos', 'isDeleted'));
    }

    /**
     * @param Request $request
     * @param $port
     */
    protected function assignFromRequest(Request $request, $port)
    {
        $port->number = $request->number;
        $port->crypto_id = $request->crypto_id;
        $port->server_id = $request->server_id;
    }


    public function json()
    {
        $ports = port::all();

        // $portsJson = $ports->toJson();
        // $portsJson = [];
        // foreach ($ports as $port)
        // {
        //     $portsJson[] = $port->toJson();
        // }
        // return view('ports.json', compact('portsJson'));

        return response()->json($ports);
    }
}
