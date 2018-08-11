<?php

namespace App\Http\Controllers;

use App\Pool;
use Illuminate\Http\Request;

class PoolController extends Controller
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
        $pools = Pool::all();
        return view('pools.index', compact('pools'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('pools.create');
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
            'name' => 'required|unique:pools',
            'url' => 'required'
        ]);
        $pool = new Pool;
        $this->assignFromRequest($request, $pool);

        $pool->save();

        $pools = Pool::all();

        $isChanged = true;

        return view('pools.index', compact('pools','isChanged'));

    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $pool = Pool::find($id);
        return view('pools.show', compact('pool'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $pool = Pool::find($id);
        return view('pools.edit', compact('pool'));
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
            'name' => 'required',
            'url' => 'required'
        ]);

        $pool = Pool::find($id);

        if ( isset($pool)) {
            $this->assignFromRequest($request, $pool);

            $pool->save();
            $isChanged = true;
        }

        return view('pools.edit', compact('pool','isChanged'));
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        Pool::destroy($id);

        $isDeleted = true;

        $pools = Pool::all();
        return view('pools.index', compact('pools', 'isDeleted'));
    }

    /**
     * @param Request $request
     * @param $pool
     */
    protected function assignFromRequest(Request $request, $pool)
    {
        $pool->name = $request->name;
        $pool->url = $request->url;
    }


    /**
     * Display database entries in json format
     *
     * @return \Illuminate\Http\Response
     */
    public function json()
    {
        $pools = Pool::all();
        
        // $poolsJson = $pools->toJson();
        // $poolsJson = [];
        // foreach ($pools as $pool)
        // {
        //     $poolsJson[] = $pool->toJson();
        // }
        // return view('pools.json', compact('poolsJson'));

        return response()->json($pools);
    }
}
