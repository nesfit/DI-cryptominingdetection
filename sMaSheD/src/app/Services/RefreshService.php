<?php

namespace App\Services;

use DB;

class RefreshService
{
	// Parsing received string data to array of JSON messages 
	public function parse_JSON($msg)
	{
		// echo $msg;
		$messages = [];
		$start = 0;
		$lastIndex = 0;

		for ($i = 0; $i < strlen($msg); $i++)
		{
		    // if($msg[$i] == '\\' && $msg[$i+1] == 'n')
		    if($msg[$i] == "\n")
		    {
		    	$messages[] = substr($msg, $start, $i - $start);
		    	$start = $i+1;
		    }
		    $lastIndex = $i;
		}
    	$messages[] = substr($msg, $start, $lastIndex+1 - $start);

    	// convert strings to JSON
    	foreach ($messages as $str)
    	{
	    	// var_dump(json_decode($str, true));
    		if(trim($str) != "")
    		{
    			$decodedMsg = json_decode($str, true);
    			// skip empty
		    	if(!empty($decodedMsg))
			    	$decodedMessages[] = $decodedMsg;
    		}
    	}

    	if(isset($decodedMessages))
	    	return $decodedMessages;
	    // in case response object is in different format --> HTTP
	    // parse errors to expected format
	    else
	    {
	    	// Unsupported response type: HTTP ---> 
	    	//		string(103) "HTTP/1.1 400 Bad Request
			// 					Content-Type: text/plain; charset=utf-8
			// 					Connection: close
			if(strpos($msg, "HTTP/1.1 400 Bad Request") !== false)
			{
				$httpError = [[
					'error' => 'HTTP/1.1 400 Bad Request',
					'id' => null,
					'result' => null
				]];
				return $httpError;
			}
			else
			{
				$undefinedError = [[
					'error' => 'Unknown server reply',
					'id' => null,
					'result' => null
				]];
		    	return $undefinedError;
			}
	    }
	}

	// return true if error is false positive (message not mean an ERROR)
	public function checkErrorMessage($msg)
	{
		// definition of false positive messages
		$falsePositiveMessages = [
			"invalid address used for login",
			"invalid payment address provided"
		];

		foreach ($falsePositiveMessages as $m)
		{
			if(strpos($msg, $m) !== false)
				return true;
		}

		return false;
	}

	// Check responses and classify status of the server
	public function checkResponse($parsed)
	{
		$retCode = [
				'0' => 0,	// OK
				'1' => 0,	// EMPTY
				'2' => 0,	// OTHER
				'3' => 0	// ERR
		];
		$result = ['code' => 0, 'reason' => 'Default checkResponse'];
		$errmsg = array();

		// process every message individually
		foreach ($parsed as $msg)
		{
			$isOther = true;
			if(empty($msg))
			{
				$retCode['1']++;	// EMPTY MESSAGE
				continue;
			}

			// check error parameter
	        if(array_key_exists('error', $msg))
	        {
	        	// error not null
	        	if(isset($msg['error']))
	        	{
		    		if(is_string($msg['error']))    // load error message if simple string
		    		{
		    			$retCode['3']++;
		    			$errmsg[] = $msg['error'];
		    		}
					else 	// error is object, contains multiple keys
					{
						if(in_array('login', $msg['error']))	// specific key in error for login method
						{
					        $retCode['0']++;	// OK (unsuccessfull login => RESULT = false)
					        $retCode['3']--;	// false positive
						}
						else
						{
					        $retCode['3']++;	//  RPC-ERR

					        // store error message to array
					        if(array_key_exists('message', $msg['error']))
								$errmsg[] = $msg['error']['message'];
							else
								$errmsg[] = implode(" ", $msg['error']);
						}
					}

					// check false positive
					if($this->checkErrorMessage(strtolower(implode(" ", $errmsg))))
					{
						if(isset($errmsg))
							array_pop($errmsg);
				        $retCode['3']--;	// NOT ERR
				        $retCode['0']++;	// NOT ERR
					}					
		        	$isOther = false;	// certainly error state, not needed to do other checks
				}
			}

	        // check result key
		    if(array_key_exists('result', $msg) && $isOther)
		    {
		    	if(!empty($msg['result']))
		    	{
			    	$retCode['0']++;	// STATUM-OK
					$isOther = false;
		    	}
		    	else
		    	{
		    		$retCode['3']++;
		    		$errmsg[] = "Result field in response message = false";
		    		$isOther = false;
		    	}
		    }

		    // for example method mining.set_difficulty or mining.notify;
		    // messages without result and error parameter 
		    if(array_key_exists('method', $msg) && $isOther)
		    {
		    	$isOther = false;
		    	if($msg['method'] === "mining.set_difficulty")
		    		$retCode['0']++;
		    	elseif($msg['method'] === "mining.notify")
		    		$retCode['0']++;
		    	elseif($msg['method'] === "mining.set_target")
		    		$retCode['0']++;
		    	else
		    		$isOther = true;
		    }

		    if($isOther)
		        $retCode['2']++;
		}
		if($retCode['0'] != 0 && $retCode['3'] == 0)	// OK
		{
			$result['code'] = 1;
			$result['reason'] = "OK" ;
		}
		elseif($retCode['0'] == 0 && $retCode['3'] != 0) // ERR
		{
			$result['code'] = 0;
			$result['reason'] = "ERR: " . implode(",", $errmsg);
		}
		else  // LISTEN
		{
			if($retCode['2'] == 0 && $retCode['1'] != 0)
				$result['reason'] = "EMPTY RESPONSE!" ;
			else
				$result['reason'] = "Unknown method!" ;

			$result['code'] = 2;	
		}

		return $result;
	}

	// send data to the socket
	public function sentTcpRequest($socket, $data)
	{
		$result = ['code' => 0, 'reason' => 'Default semtTcpRequest!'];
	    $sent = @stream_socket_sendto($socket, $data);
	    if ($sent > 0)
	    {
	    	// selected size is in bytes, for RPC reply is enough
	        $server_response = fread($socket, 4096);
	        // echo $server_response;
	        // echo "\n-------------------\n";

	        if(!empty($server_response))
	        {
	        	// process reply
		        $parsed = $this->parse_JSON($server_response);
		        $result = $this->checkResponse($parsed);
	        }
		    else
		    {
		    	$result['code'] = 2; // empty response
		    	$result['reason'] = "Empty response from the server";
		    }
	    }
	    else
	    {
	    	$result['code'] = 0;	// Sent error
	    	$result['reason'] = "Error during stream_socket_sendto() call";
	    }

	    return $result;
	}

	// Returns socket descriptor for conection containing IP address and port
	public function connectToServer($connection)
	{
		
		$timeoutSec = 1;	// connection timeout
		$socket = @stream_socket_client($connection, $errno, $errstr, $timeoutSec);
		if($socket)
		{
			$timeoutUsec = 300000; //0.3 s; stram timeout
			if(!stream_set_timeout($socket, $timeoutSec, $timeoutUsec))
				$socket = -1;	//timeout setup err
		}
		else
		    $socket = -2; // unreachable

		return $socket;
	}


	/**
	 * Run active probe for Stratum mining protocol.
	 *
	 * @param  $port, $address
	 * @return list of results (reason and code)
	 */
	public function miningProbeStratum($port, $address)
	{ 

		// client configuration parameters
		$username = "test.worker1";    // default for stratum is Bitcoin
		$email = "test.worker1@test.com";
		$password = "x";
		$agent = "Miner 1.0";


		// used protocol methods --> first approach
		// $methods = [
		// 	"mining.subscribe" => ['Miner 1.0'],
		// 	"login" => ['login' => 'test.worker1', 'pass' => 'password', 'agent' => 'xmr/1.0'],
		// 	"mining.authorize" => ['Bitcoin', 'x'],
		// 	"eth_getWork" => [],
		//  "getblocktemplate" = array(),
		//  "getwork" => array()		
		// ];


		// used protocol methods --> second approach
		$requests = [
			// "mining.subscribe" => ['Miner 1.0'],
			// '{"jsonrpc": "2.0", "method": "mining.subscribe", "params": ["Miner 1.0"], "id": 1}',
			'{"jsonrpc": "2.0", "method": "mining.subscribe", "params": ["'. $agent .'"], "id": 1}',

			// "mining.authorize" => ['Bitcoin', 'x'],
			// '{"jsonrpc": "2.0", "method": "mining.authorize", "params": ["Bitcoin", "x"], "id": 1}',
			'{"jsonrpc": "2.0", "method": "mining.authorize", "params": ["' . $username . '", "' . $password . '"], "id": 1}',


			// SlushPool userName.workerName:any-password
			// "login" => ['login' => 'test.worker1', 'pass' => 'password', 'agent' => 'xmr/1 .0'],
			// '{"jsonrpc": "2.0", "method": "login", "params": {"login": "test.worker1", "pass": "password", "agent": "xmr/1.0"}, "id": 1}',
			'{"jsonrpc": "2.0", "method": "login", "params": {"login": "' . $username . '", "pass": "' . $password . '", "agent": "' . $agent . '"}, "id": 1}',

			'{"worker": "eth1.0", "jsonrpc": "2.0", "params": [], "id": 3, "method": "eth_getWork"}',			
			
			// nanopool 0x52bc44d5378309ee2abf1539bf71de1b7d7be3b5.NH/mail@example.com
			// '{"worker": "eth1.0", "jsonrpc": "2.0", "params": ["0x52bc44d5378309ee2abf1539bf71de1b7d7be3b5/test.worker/mail@example", "x"], "id": 2, "method": "eth_submitLogin"}'
			'{"worker": "eth1.0", "jsonrpc": "2.0", "params": ["0x52bc44d5378309ee2abf1539bf71de1b7d7be3b5/' . $username . '/' . $email . '", "'. $password . '"], "id": 2, "method": "eth_submitLogin"}',
			// NICEHASH => you can add the worker's name in your stratum username; use the following format: "YourBitcoinAddress.WorkerName" where WorkerName is up to fifteen alphanumeric (As-Zz, 0-9) characters; example: 13pkLyfskZ3qWmHULpk8XZa6gACKQ2jDX.MyASIC01

			// '{"id": 1, "method": "getblocktemplate", "params": []}',

			// '{"id": 1, "method": "getwork", "params": []}'

			// add new messages in the future ...
		];


		$maxRetCode = -2;
		$resultReason = '';
		$connection = 'tcp://' . $address . ':' . $port;
		foreach ($requests as $request)
		// foreach ($methods as $method => $param)
		{
			$socket = $this->connectToServer($connection);
			$result['code'] = 0; // default error code

			if($socket == -1)
			{
				$resultReason .= "Connection timeout setup error";
			}
			elseif($socket == -2)
			{
				$result['reason'] = "Destination unreachable - connection failed";
				return $result;
			}
			elseif($socket)
			{
				// block of code for first approach, problematic -> for in case of different methods
	    		// $data = '{"jsonrpc": "2.0", "id": 1, "method": "mining.subscribe", "params": []}' . "\n";
				// prepare JSON-RPC message
				// $request = [
				// 	'jsonrpc' => '2.0',
				// 	'id' => 1,
				// 	'method' => $method,
				// 	'params' => $param
				// ];
				// $data = json_encode($request) . "\n";

				$method = json_decode($request)->method; // load method name from JSON message
				$data = $request . "\n";  	// append new line character !!!
				$resultReason .= $method;  	// built reason message

				// send request
				$result = $this->sentTcpRequest($socket, $data);

				// echo $data . "\n";
				$resultReason .= " => " . $result['reason'] . ' (' . $result['code'] . '); ';
				
				// keep highest return code
				if($maxRetCode <= $result['code'])
					$maxRetCode = $result['code'];

				// stop if test result => OK
				if($result['code'] == 1)
				{
					$result['reason'] = $resultReason;
					break;
				}

				$result['code'] = $maxRetCode;
				stream_socket_shutdown($socket, STREAM_SHUT_RDWR);			
			} 
		}
		$result['reason'] = $resultReason;

		return $result;
	}
	

	/**
	 * Run active probe for Getblocktemplate mining protocol.
	 *
	 * @param  $port, $address
	 * @return list of results (reason and code)
	 */
	public function miningProbeGetblocktemplate($port, $address)
	{      
		$urlPref = 'http://';
		$user = 'Bitcoin';
		$password = 'x';
		$extensions="longpoll midstate rollntime submitold";

		// create JSON-RPC message
		// $data = [
		// 	'id' => 1,
		// 	'method' => 'getblocktemplate',
		// 	'params' => [array("capabilities" => array("coinbasetxn", "workid", "coinbase/append"))]
		// ];
		// $encodedData = json_encode($data);

		// define message
		$encodedData = '{"id": 1, "method": "getblocktemplate", "params": [{"capabilities": ["coinbasetxn", "workid", "coinbase/append"]}]}' . "\n";

		// use CURL to send HTTP request
		$ch = curl_init();

		// configure request parameters
		curl_setopt($ch, CURLOPT_URL, $urlPref . $address);
		curl_setopt($ch, CURLOPT_PORT, $port);
		curl_setopt($ch, CURLOPT_POST, true);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
		curl_setopt($ch, CURLOPT_TIMEOUT, 1);
		curl_setopt($ch, CURLOPT_HEADER, false);
		curl_setopt($ch, CURLOPT_USERPWD, $user . ":" . $password); // if auth
		curl_setopt($ch, CURLOPT_USERAGENT, 'Miner 1.0');
		curl_setopt($ch, CURLOPT_ENCODING, "deflate, gzip");
		curl_setopt($ch, CURLOPT_POSTFIELDS, $encodedData);
		curl_setopt($ch, CURLOPT_HTTPHEADER, array(         
		    'Content-Type: application/json',
		    'Content-Length: ' . strlen($encodedData),
			'X-Mining-Extensions: ' . $extensions)
		);

		// execute request
		$response = curl_exec($ch);

		// decode response message
		$msg = json_decode($response, true);

		if(!empty($msg))
		{
			// check error parameter
	        if(array_key_exists('error', $msg))
	        {
	        	// error not null
	        	if(isset($msg['error']))
	        	{
	        		// load error message
		    		if(is_string($msg['error']))
		    		{
		    			$errmsg = $msg['error'];
		    		}
					else
					{

				        if(array_key_exists('message', $msg['error']))
							$errmsg = $msg['error']['message'];
						else
							$errmsg = implode(" ", $msg['error']);
					}
				}
		        $result['reason'] = "ERR: " . $errmsg;	
		        $result['code'] = 0;	//  RPC-ERR
			}
			// check result parameter
			if(array_key_exists('result', $msg))
			{
				if(!empty($msg['result']))
				{
			    	$result['code'] = 1;;	// GBT-OK
			    	$result['reason'] = "getblocktemplate => OK;";
				}
			}
		}
		else if($curl_error = curl_error($ch) )
		{
			$result['reason'] = 'CURL error: ' . $curl_error . ';';
			if(strpos(strtolower($curl_error), "empty reply") !== false)
				$result['code'] = 2;
			else
				$result['code'] = 0;
		}
		else
		{
			$result['reason'] = 'Undefined error';
			$result['code'] = 0;
		}

		curl_close($ch);
	    return $result;
	}

	public function miningProbeGetwork($port, $address)
	{      
		// for futer implementation
	    $result = false;
	    return $result;
	}

	// helper function, create new record in History table
	public function addHistoryRecord($miningPropId, $status, $timestamp)
	{
		DB::table('history')->insert([
			'miningProp_id' => $miningPropId,
			'status' => $status['code'],
			'reason' => $status['reason'],
			'created_at' => $timestamp
		]);
	}

	// helper function, create new record in MiningProperties table
	public function addMiningPropRecord($serverId, $address, $port, $protocol, $status, $timestamp)
	{
		$id = DB::table('miningProperties')->insertGetId([
			'server_id' => $serverId,
			'address' => $address,
			'port' => $port,
			'protocol' => $protocol,
			'status' => $status['code'],
			'reason' => $status['reason'],
			'created_at' => $timestamp
		]);

		return $id;
	}

	// remove records from miningProperties table 
	public function clearMiningProp()
	{
	    DB::statement('SET FOREIGN_KEY_CHECKS=0;');
	    DB::table('miningProperties')->truncate();
	    DB::statement('SET FOREIGN_KEY_CHECKS=1;');
	}

	// remove records from historyTable table 
	public function clearHistory()
	{
	    DB::statement('SET FOREIGN_KEY_CHECKS=0;');
	    DB::table('history')->truncate();
	    DB::statement('SET FOREIGN_KEY_CHECKS=1;');
	}
}