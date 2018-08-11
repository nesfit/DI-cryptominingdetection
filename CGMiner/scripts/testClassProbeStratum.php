<?php
	Class Test
	{

		// constructor definition --> initialize instant variables
		public function __construct()
		{
			$this->wasResponseWritten = false;

			$this->timeout = 0;
			$this->unhandled = 0;
			$this->offline = 0;
			$this->sentErr = 0;
			$this->emptyResponse = 0;
			$this->oks = 0;
			$this->rpcErr = 0;
			$this->listens = 0;

			$this->rowId = 1;
			$this->miningProps = [
					'oks' => array(),
					'rpcErr' => array(),
					'listens' => array(),
					'unreachable' => array(),
					'sentErr' => array(),
					'timeup-setUp' => array(),
					'unhandled' => array(),
			];
		}

		public function parse_JSON($msg)
		{
			// echo $msg;
			$messages = [];
			$start = 0;
			$lastIndex = 0;
			for ($i = 0; $i < strlen($msg); $i++)
			{
			    if($msg[$i] == "\n")
			    // if($msg[$i] == '\\' && $msg[$i+1] == 'n')
			    {
			    	$messages[] = substr($msg, $start, $i - $start);
			    	$start = $i+1;
			    }
			    $lastIndex = $i;
			}
	    	$messages[] = substr($msg, $start, $lastIndex+1 - $start);

	    	foreach ($messages as $str)
	    	{
		    	// var_dump(json_decode($str, true));
	    		if(trim($str) != "")
	    		{
	    			$decodedMsg = json_decode($str, true);
			    	if(!empty($decodedMsg))
				    	$decodedMessages[] = $decodedMsg;
	    		}
	    	}
	    	if(isset($decodedMessages))
		    	return $decodedMessages;
		    else
		    {	// Unsupported response type: HTTP ---> 
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
			    	echo "?????\n";
			    	echo $msg;
			    	echo "?????\n";
			    	return ['Mesage decoding error'];
				}
		    }
		}


		// return true if error is false positive (message not mean an ERROR)
		public function checkErrorMessage($msg)
		{
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

		// run classification
		public function checkResponse($parsed, $rowId)
		{
			$retCode = [
					'0' => 0,
					'1' => 0, //empty message
					'2' => 0, // other
					'3' => 0  //ERR
			];

			$errmsg = '';

			foreach ($parsed as $msg )
			{
				$isOther = true;
				// skip NULL array
				if(empty($msg))
				{
					$retCode['1']++;
					// echo "83: VAR_DUMP\n";
					// var_dump($msg);
					continue;
				}

				// check error parameter
		        if(array_key_exists('error', $msg))
		        {
		        	// error not null
		        	if(isset($msg['error']))
		        	{
		        		// load error message
		        		if(is_string($msg['error']))
		        		{
					        $retCode['3']++;	// RPC-ERR
		        			$errmsg = $msg['error'];
		        		}
						else
						{
							if(in_array('login', $msg['error']))
							{
						        $retCode['0']++;	// OK (unsuccessfull login => RESULT = false)
							}
							else
							{
						        $retCode['3']++;	// RPC-ERR
						        if(array_key_exists('message', $msg['error']))
									$errmsg = $msg['error']['message'];
								else
									$errmsg = implode(" ", $msg['error']);
								// echo "ERRMSG: " . $errmsg . "\n";
							}
						}
						if($this->checkErrorMessage(strtolower($errmsg)))
						{
							$errmsg = '';
					        $retCode['3']--;	// NOT ERR
					        $retCode['0']++;	// NOT ERR
						}

						$isOther = false;
		        	}
		        }

		        // check result parameter
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
			    		$errmsg = "Result field in response message = false";
			    		$isOther = false;
			    	}
			    }

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
			    {
			        $retCode['2']++;
					echo "127: VAR_DUMP\n";
			        var_dump($msg);
			        echo "---------------------\n";
			    	// echo "OTHER!!!".$this->rowId."\n";
			    }
			}
			// var_dump($retCode);
			echo "******************************************************\n";
			if($retCode['0'] != 0)			
				echo "OK ". $retCode['0'] . "\n";
	
			if($retCode['1'] != 0)			
				echo "EMPTY ARRAY ". $retCode['1'] . "\n";

			if($retCode['2'] != 0)			
				echo "UNHANDLED-STATE ". $retCode['2'] . "\n";

			if($retCode['3'] != 0)			
				echo "ERR ". $retCode['3'] . ": " . $errmsg . "\n";
	
			echo "******************************************************\n";

			if($retCode['0'] != 0 && $retCode['3'] == 0)
				return 0;
			elseif($retCode['0'] == 0 && $retCode['3'] != 0)
				return -1;
			elseif($retCode['1'] != 0 && $retCode['0'] == 0)	// empty array
				return 1;
			// elseif($retCode['2'] != 0 && $retCode['0'] == 0)	// other
				// return 2;
			else
			{
			    // echo "ROW: " . $rowId . "=> {$this->actIp}:{$this->actPort} \n";

				// var_dump($parsed);
				echo "!!! UNHANDLED STATE !!! \n";
				// var_dump($parsed);
				return 2; // UNHANDLED STATE
			}
		}


		public function sentTcpRequest($socket, $data)
		{
			$parsed;
			$retCode;
		    $sent = @stream_socket_sendto($socket, $data);
		    if ($sent > 0)
		    {
		        // stucks in some case
		        // $server_response = stream_socket_recvfrom ($socket, $bufferSize);
		        $server_response = fread($socket, 4096);
		        if(!empty($server_response))
		        {
			        $parsed = $this->parse_JSON($server_response);
			        $retCode = $this->checkResponse($parsed, $this->rowId);
			        if(!$this->wasResponseWritten)
			        {
				        echo "************ SERVER RESPONSE ***************\n";
			        	var_dump($server_response);
				        echo "********************************************\n";
				        $this->wasResponseWritten = true;
			        }
		        }
			    else
			    {
			    	echo "Empty JSON-RPC response \n";
			    	$retCode = 1; // empty response
			    	
			    }
		        	// echo "ERROR here\n";
		    }
		    else
		    {
		    	$retCode = -2;	// Sent error
		    }

		    // if($retCode != 2)
		    	// var_dump($parsed);
		    // var_dump($retCode);
		    return $retCode;
		}

		public function connectToServer($conn)
		{
			$timeoutSec = 2;
			$socket = @stream_socket_client($conn, $errno, $errstr, $timeoutSec);
			if($socket)
			{
				$timeoutUsec = 300000; //0.3 s
				if(!stream_set_timeout($socket, $timeoutSec, $timeoutUsec))
				{
					$socket = -3;
				}
			}
			else
			{
			    // echo 'Unable to connect to server' . "\n";
			    $socket = -4; // unreachable
			}

			return $socket;
		}

		public function main_old($address, $port)
		{
			$this->actIp = $address;
			$this->actPort = $port;

			// setup timeouts
			$timeoutSec = 2;
			$timeoutUsec = 300000; //0.3 s
			$bufferSize = 256;
			$retCode = 0;

			// $address = '52.31.186.94';
			// $address = '178.32.145.31';
			// $port = 3333;
			// $port = 8200;

			// $methods = [
			// 	"mining.subscribe" => ['Miner 1.0'],
			// 	"login" => ['login' => 'test.worker1', 'pass' => 'password', 'agent' => 'xmr/1.0'],
			// 	"mining.authorize" => ['Bitcoin', 'x'],
			// 	"eth_getWork" => array(),
			// 	"getblocktemplate" => array(),
			// 	"getwork" => array()

			// 	// {"worker": "eth1.0", "jsonrpc": "2.0", "params": ["0x9c99d212f7e5daa18ab50810e0fd255d1f04303b/kvetak.worker1/ivesely@mailinator", "x"], "id": 2, "method": "eth_submitLogin"}


			// 	// NICEHASH => you can add the worker's name in your stratum username; use the following format: "YourBitcoinAddress.WorkerName" where WorkerName is up to fifteen alphanumeric (As-Zz, 0-9) characters; example: 13pkLyfskZ3qWmHULpk8XZa6gACKQ2jDX.MyASIC01

			// 	// SlushPool userName.workerName:any-password

			// 	// nanopool 0x52bc44d5378309ee2abf1539bf71de1b7d7be3b5.NH/mail@example.com
			// ];
			$methods = [
				// "mining.subscribe" => ['Miner 1.0'],
				'{"jsonrpc": "2.0", "method": "mining.subscribe", "params": ["Miner 1.0"], "id": 1}',
				
				// "login" => ['login' => 'test.worker1', 'pass' => 'password', 'agent' => 'xmr/1 .0'],

				// "mining.authorize" => ['Bitcoin', 'x'],
				'{"jsonrpc": "2.0", "method": "mining.authorize", "params": ["Bitcoin", "x"], "id": 1}',

				'{"worker": "eth1.0", "jsonrpc": "2.0", "params": [], "id": 3, "method": "eth_getWork"}',

				'{"jsonrpc": "2.0", "method": "login", "params": {"login": "test.worker1", "pass": "password", "agent": "xmr/1.0"}, "id": 1}',

				'{"worker": "eth1.0", "jsonrpc": "2.0", "params": ["0x52bc44d5378309ee2abf1539bf71de1b7d7be3b5/test.worker/mail@example", "x"], "id": 2, "method": "eth_submitLogin"}',

				// "getblocktemplate" =	> array(),
				'{"id": 1, "method": "getblocktemplate", "params": []}',


				// "getwork" => arrZay()		
				'{"id": 1, "method": "getwork", "params": []}'
			];

				// {"worker": "eth1.0", "jsonrpc": "2.0", "params": ["0x9c99d212f7e5daa18ab50810e0fd255d1f04303b/kvetak.worker1/ivesely@mailinator", "x"], "id": 2, "method": "eth_submitLogin"}


			// 	// NICEHASH => you can add the worker's name in your stratum username; use the following format: "YourBitcoinAddress.WorkerName" where WorkerName is up to fifteen alphanumeric (As-Zz, 0-9) characters; example: 13pkLyfskZ3qWmHULpk8XZa6gACKQ2jDX.MyASIC01

			// 	// SlushPool userName.workerName:any-password

			// 	// nanopool 0x52bc44d5378309ee2abf1539bf71de1b7d7be3b5.NH/mail@example.com
			// ];

			$connection = 'tcp://' . $address . ':' . $port;
			$socket = @stream_socket_client($connection, $errno, $errstr, $timeoutSec);
			if($socket)
			{
				$timeoutSec = 1;
				$maxRetCode = -2;
				$maxRetCodeMeth;
				$methRetCode = array();
				$resultMeth;
				if(stream_set_timeout($socket, $timeoutSec, $timeoutUsec))
				{
					// $iterations = 0
					echo "!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!\n";
				    echo "++++++++++++++++++++++++ ROW: " . $this->rowId . "=> {$this->actIp}:{$this->actPort} +++++++++++++++++++++++++\n";

					foreach ($methods as $method/* => $param*/)
					{
						$actMethod = json_decode($method)->method;
						echo "---> trying method: " . $actMethod . " --->\n";

						// $data = '{"jsonrpc": "2.0", "method": "login", "params": {"login": "test.worker1", "pass": "password", "agent": "xmr/1.0"}, "id": 1}' . "\n";
						// $request = [
						// 	'jsonrpc' => '2.0',
						// 	'id' => 1,
						// 	'method' => $method,
						// 	'params' => $param
						// ];
						// $data = json_encode($method) . "\n";
						$data = $method . "\n";
						// $data = '{"worker": "eth1.0", "jsonrpc": "2.0", "params": [], "id": 3, "method": "eth_getWork"}' . "\n";

						// $data = '{"worker": "eth1.0", "jsonrpc": "2.0", "params": ["0x52bc44d5378309ee2abf1539bf71de1b7d7be3b5/test.worker/mail@example.com", "x"], "id": 2, "method": "eth_submitLogin"}' . "\n";
						


						$retCode = $this->sentTcpRequest($socket, $data);
						// if result of test is RPC-ERR, OTHER or  EMPTY RESPONSE keep trying available methods
						// echo "---> actual retun code is {$retCode} --->\n";
							
						$methRetCode[$actMethod] = $retCode;
						if($maxRetCode <= $retCode)
						{
							$maxRetCode = $retCode;
							$maxRetCodeMeth = $actMethod;
						}

						$resultMeth = $actMethod;
						echo "<--- END {$actMethod} ({$retCode}) <---\n"; 
						if($retCode == 0)
						{
							// echo "<--- END {$actMethod} ({$retCode}) <---\n"; 
							// echo "----------------------------\n";
							break;
						}
						elseif($retCode < -1)
						{
							$retCode = $maxRetCode;
							$resultMeth = $maxRetCodeMeth;	
							// break;
						}

						$retCode = $maxRetCode;
						$resultMeth = $maxRetCodeMeth;
						// echo "----------------------------\n";
					}
					echo "Final result: " . $resultMeth . "({$retCode})\n";
					if($retCode == 0 && $retCode != $maxRetCode)
						echo "Max result: " . $maxRetCodeMeth . "({$maxRetCode})\n";

					// foreach ($methRetCode as $key => $value)
					// {
					// 	echo "Method: {$key} ({$value})\n";
					// }
					echo "!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!\n\n";
					$this->wasResponseWritten = false;

				}
				else
				{
					// echo "Timeout set error";
				    $retCode = -3; // timeout setUp error
				}
				stream_socket_shutdown($socket, STREAM_SHUT_RDWR);
			} 
			else
			{
			    // echo 'Unable to connect to server' . "\n";
			    $retCode = -4; // unreachable
			}
			return $retCode;
		}

		// miningProbeStratum
		public function main($address, $port)
		{
			$this->actIp = $address;
			$this->actPort = $port;

			$timeoutSec = 2;
			$timeoutUsec = 300000; //0.3 s
			$bufferSize = 256;
			$retCode = 0;

			$methods = [
				// "mining.subscribe" => ['Miner 1.0'],
				'{"jsonrpc": "2.0", "method": "mining.subscribe", "params": ["Miner 1.0"], "id": 1}',
				// "login" => ['login' => 'test.worker1', 'pass' => 'password', 'agent' => 'xmr/1 .0'],
				// "mining.authorize" => ['Bitcoin', 'x'],
				'{"jsonrpc": "2.0", "method": "mining.authorize", "params": ["Bitcoin", "x"], "id": 1}',

				'{"worker": "eth1.0", "jsonrpc": "2.0", "params": [], "id": 3, "method": "eth_getWork"}',

				'{"jsonrpc": "2.0", "method": "login", "params": {"login": "test.worker1", "pass": "password", "agent": "xmr/1.0"}, "id": 1}',

				'{"worker": "eth1.0", "jsonrpc": "2.0", "params": ["0x52bc44d5378309ee2abf1539bf71de1b7d7be3b5/test.worker/mail@example", "x"], "id": 2, "method": "eth_submitLogin"}',

				// "getblocktemplate" =	> array(),
				'{"id": 1, "method": "getblocktemplate", "params": []}',
				// "getwork" => arrZay()		
				'{"id": 1, "method": "getwork", "params": []}'
			];

			$connection = 'tcp://' . $address . ':' . $port;


			foreach ($methods as $method/* => $param*/)
			{
				$socket = $this->connectToServer($connection);
				if($socket && $socket > 0)
				{
					$maxRetCode = -2;

					$actMethod = json_decode($method)->method;
					echo "---> trying method: " . $actMethod . " --->\n";
					$data = $method . "\n";

					$retCode = $this->sentTcpRequest($socket, $data);

					$methRetCode[$actMethod] = $retCode;
					if($maxRetCode <= $retCode)
					{
						$maxRetCode = $retCode;
						$maxRetCodeMeth = $actMethod;
					}

					$resultMeth = $actMethod;
					echo "<--- END {$actMethod} ({$retCode}) <---\n"; 
					if($retCode == 0)
					{
						// echo "<--- END {$actMethod} ({$retCode}) <---\n"; 
						// echo "----------------------------\n";
						break;
					}
					elseif($retCode < -1)
					{
						$retCode = $maxRetCode;
						$resultMeth = $maxRetCodeMeth;	
						// break;
					}

					$retCode = $maxRetCode;
					$resultMeth = $maxRetCodeMeth;
					// echo "----------------------------\n";

					stream_socket_shutdown($socket, STREAM_SHUT_RDWR);
				}
			}
			
			return $retCode;
		}

		// opens file and collect statistics
		public function runTestOnCSV($filename, $specificPort = NULL)
		{
			$handle = fopen($filename, "r");
			while (($data = fgetcsv($handle)) !== false)
			{
				if(isset($specificPort) && $data[1] != $specificPort)
				{
				// 	// echo "skip\n";
					continue;
				}

				$status = $this->main($data[0], $data[1]);
				switch ($status)
				{
					//OFFLINE
					case -4:
						$this->offline++;
						$this->miningProps['unreachable'][] = $this->rowId . "\t" . $data[0] . ":" . $data[1];
						break;	

					//TIMEOUT setUP ERR
					case -3:
						$this->timeout++;
						$this->miningProps['timeup-setUp'][] = $this->rowId . "\t" . $data[0] . ":" . $data[1];
						break;	

					//SENT ERROR 
					case -2:
						$this->sentErr++;
						$this->miningProps['sentErr'][] = $this->rowId . "\t" . $data[0] . ":" . $data[1];
						break;	

					//RPC-ERROR
					case -1:
						$this->rpcErr++;
						$this->miningProps['rpcErr'][] = $this->rowId . "\t" . $data[0] . ":" . $data[1];
						break;

					//OK
					case 0:
						# code...
						$this->oks++;
						$this->miningProps['oks'][] = $this->rowId . "\t" . $data[0] . ":" . $data[1];
						break;

					// EMPTY RESPONSE
					case 1:
						$this->emptyResponse++;
						$this->miningProps['emptyResp'][] = $this->rowId . "\t" . $data[0] . ":" . $data[1];
						break;

					//SERVER-LISTEN
					case 2:
						$this->listens++;
						$this->miningProps['listens'][] = $this->rowId . "\t" .  $data[0] . ":" . $data[1];
						break;					

					//UNHANDLED-STATE
					case 3:
						$this->unhandled++;
						$this->miningProps['unhandled'][] = $this->rowId . "\t" .  $data[0] . ":" . $data[1];
						break;
				}
				if($status >= 0 && $status <= 2)
					echo "\n\n";
				$this->rowId++;
			}
		}

		// print statistics
		public function printStats()
		{
			var_dump($this->miningProps);
			echo "OK: " . $this->oks;
			echo "\n";
			echo "RPC-ERROR: " . $this->rpcErr;
			echo "\n";
			echo "EMPTY RESPONSE: " . $this->emptyResponse;
			echo "\n";
			echo "LISTEN: " . $this->listens;
			echo "\n";
			echo "UNHANDLED: " . $this->unhandled;
			echo "\n";
			echo "SENT ERR: " . $this->sentErr;
			echo "\n";
			echo "TIMEOUT SETUPERR: " . $this->timeout;
			echo "\n";
			echo "UNREACHABLE: " . $this->offline;
			echo "\n";
		}

		private $wasResponseWritten;
		public $miningProps;

		public $timeout;
		public $offline;
		public $sentErr;
		public $emptyResponse;
		public $oks;
		public $rpcErr;
		public $listens;
		public $unhandled;
		public $rowId;
		public $actIp;
		public $actPort;

	}

	$x = new Test();

	$x->runTestOnCSV("../csv/miningProps.csv", 3333);
	$x->runTestOnCSV("../csv/miningProps.csv");
	$x->printStats();  

	// for testing single address comment lines above, and uncomment following line
	// $x->main("120.55.29.131",8080); 

?>
