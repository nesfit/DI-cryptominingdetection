<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;


use App\Services\RefreshService;

use Carbon\Carbon; 
use DB;
use App\MiningProp;

class RefreshMiningProperties implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;
  
    private $data;
    private $service;
    // private $miningProps;
    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct()
    {
        // select matching data based on server ID from tables servers, miningProperties, addresses
        $this->data = DB::table('servers')
                        ->join('ports', 'servers.id', '=', 'ports.server_id')
                        ->join('addresses', 'servers.id', '=', 'addresses.server_id')
                        ->select('servers.id', 'ports.number', 'addresses.address')
                        ->distinct()
                        ->get();
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle(RefreshService $service)
    {
        // init service
        $this->service = $service;

        // define protocols
        $protocols = ['stratum', 'getblocktemplate'/*, 'getwork'*/];
        $status = ['code' => 0, 'reason' => 'intial message'];

        // for every record
        foreach ($this->data as $key)
        {
            // and every protocols
            foreach ($protocols as $protocol)
            {
                // try to load matching mining properties 
                $miningProp = MiningProp::where('server_id', $key->id)
                                        ->where('address', $key->address)
                                        ->where('port', $key->number)
                                        ->where('protocol', $protocol)
                                        ->first();

                // send probe request on actual protocol
                switch ($protocol)
                {
                    case 'stratum':
                        $status = $this->service->miningProbeStratum($key->number, $key->address);
                        break;

                    case 'getblocktemplate':
                        // echo $key->address . ':' $key->number;
                        // echo $key->address . ":" . $key->number . "\n";
                        $status = $this->service->miningProbeGetblocktemplate($key->number, $key->address);
                        break;

                    // case 'getwork':
                    //     $status = $this->service->miningProbeGetwork($key->number, $key->address);
                    //     break;
                    
                    default:
                        $status['code'] = 0;
                        $status['reason'] = "Default value - job";
                        break;
                }

                // keep actual time
                $timestamp = Carbon::now();

                // where record with "$key" params not exist, create new
                if(!$miningProp)
                {
                    $miningPropId = $this->service->addMiningPropRecord(
                                $key->id, $key->address, $key->number, $protocol, $status, $timestamp);

                }

                // else update status of existing
                else
                {
                    $miningProp->status = $status['code'];
                    $miningProp->reason = $status['reason'];
                    $miningProp->updated_at = $timestamp;
                    $miningProp->push();
                    $miningPropId = $miningProp->id;
                }

                // update log info
                $this->service->addHistoryRecord($miningPropId, $status, $timestamp);
            }
        }        
    }
}
