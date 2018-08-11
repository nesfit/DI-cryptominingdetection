<?php

namespace App\Http\Controllers;

use App\MiningProp;
use App\History;
use App\Services\RefreshService;
use App\Jobs\RefreshMiningProperties;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;


class MiningPropController extends Controller
{
	private $service;

    public function __construct(RefreshService $service)
    {
        $this->middleware('auth', ['except' => ['index', 'json', 'jsonHistory', 'jsonHistoryAll']]);
        $this->service = $service;
    }


    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $miningProps = MiningProp::all();
        // default pagination
        // $miningProps = MiningProp::paginate(30);

        return view('miningProperties.index', compact('miningProps'));
    }


    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        // $miningProps = MiningProp::paginate(30);

        $history = History::where('miningProp_id', $id)->paginate(10);
        // $history = History::where('miningProp_id', $id)->get();
        $miningProp = MiningProp::find($id);
        
        return view('miningProperties.show', compact('miningProp', 'history'));
    }

    /**
     * Refresh mining properties status
     *
     * @return \Illuminate\Http\Response
     */
    public function refresh()
    {
        // dispatch job RefreshMiningProperties
        RefreshMiningProperties::dispatch();

        return redirect('miningProp');
    }

    /**
     * Remove the resources from storage.
     *
     * @return \Illuminate\Http\Response
     */
    public function clear()
    {
        $this->service->clearHistory();
        $this->service->clearMiningProp();
        // History::truncate();
        // MiningProp::truncate();
        return redirect('miningProp');
    }

    /**
     * Remove the specified resource from storage.
     * Keeps last record in resource.
     * @param  int  $miningPropId
     * @return \Illuminate\Http\Response
     */
    public function historyClear($miningPropId)
    {
        $lastRecord = History::where('miningProp_id', $miningPropId)->orderBy('id', 'desc')->first();

        History::where('miningProp_id', $miningPropId)->delete();
        $this->service->addHistoryRecord($lastRecord->miningProp_id, $lastRecord->status, $lastRecord->created_at);

        return redirect('miningProp/' . $miningPropId);
    }

    /**
     * Display database entries in json format
     *
     * @return \Illuminate\Http\Response
     */
    public function json()
    {
        $miningProps = miningProp::all();
        return response()->json($miningProps);
    }

    /**
     * Display specific database entries in json format
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function jsonHistory($id)
    {
        $history = History::where('miningProp_id', $id)->get();
        return response()->json($history);
    }

    /**
     * Display database entries in json format
     *
     * @return \Illuminate\Http\Response
     */
    public function jsonHistoryAll()
    {
        ini_set('memory_limit', '1G');
        $history = History::all();
        return response()->json($history);
    }
}