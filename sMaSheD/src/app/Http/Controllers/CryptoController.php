<?php

namespace App\Http\Controllers;

use App\Crypto;
use Illuminate\Http\Request;

class CryptoController extends Controller
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
        $cryptos = Crypto::all();
        return view('cryptos.index', compact('cryptos'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('cryptos.create');
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
            'abbreviation' => 'required|unique:cryptos',
            'name' => 'required|unique:cryptos',
            'url' => 'required|unique:cryptos'
            ]);

        $crypto = new Crypto;
        $this->assignFromRequest($request, $crypto);

        $crypto->save();

        $cryptos = Crypto::all();
        $isChanged = true;

        return view('cryptos.index', compact('cryptos','isChanged'));
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $crypto = Crypto::find($id);
        return view('cryptos.show', compact('crypto'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $crypto = Crypto::find($id);
        return view('cryptos.edit', compact('crypto'));
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
            'abbreviation' => 'required',
            'name' => 'required',
            'url' => 'required'
        ]);

        $crypto = Crypto::find($id);

        if (isset($crypto)) {
            $this->assignFromRequest($request, $crypto);

            $crypto->save();
            $isChanged = true;
        }

        return view('cryptos.edit', compact('crypto', 'isChanged'));
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        Crypto::destroy($id);

        $isDeleted = true;

        $cryptos = Crypto::all();
        return view('cryptos.index', compact('cryptos', 'isDeleted'));
    }

    /**
     * @param Request $request
     * @param $crypto
     */
    protected function assignFromRequest(Request $request, $crypto)
    {
        $crypto->abbreviation = $request->abbreviation;
        $crypto->name = $request->name;
        $crypto->url = $request->url;
    }


    /**
     * Display database entries in json format
     *
     * @return \Illuminate\Http\Response
     */
    public function json()
    {
        $cryptos = Crypto::all();
        $cryptosJson = $cryptos->toJson();
        // $cryptosJson = [];
        // foreach ($cryptos as $crypto)
        // {
        //     $cryptosJson[] = $crypto->toJson();
        // }

        // return view('cryptos.json', compact('cryptosJson'));
        return response()->json($cryptos);
    }
}
