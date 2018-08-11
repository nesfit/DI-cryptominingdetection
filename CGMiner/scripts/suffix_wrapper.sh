#!/bin/bash

# adds configuration parameters to the config files
# example - input format: CGminer config file, containts url, username and password in JSON
# ./suffix_wrapper.sh cgminer.conf.s0

FILE=$1

echo ",
\"intensity\" : \"13\",
\"vectors\" : \"1\",
\"worksize\" : \"256\",
\"kernel\" : \"scrypt\",
\"lookup-gap\" : \"2\",
\"thread-concurrency\" : \"8192\",
\"shaders\" : \"2048,\",
\"gpu-engine\" : \"0-0\",
\"gpu-fan\" : \"0\",
\"gpu-memclock\" : \"0\",
\"gpu-memdiff\" : \"0\",
\"gpu-powertune\" : \"0\",
\"gpu-vddc\" : \"0.000\",
\"temp-cutoff\" : \"75\",
\"temp-overheat\" : \"85\",
\"temp-target\" : \"65\",
\"api-port\" : \"4028\",
\"expiry\" : \"120\",
\"gpu-dyninterval\" : \"7\",
\"gpu-platform\" : \"0\",
\"gpu-threads\" : \"2\",
\"hotplug\" : \"5\",
\"log\" : \"5\",
\"no-pool-disable\" : true,
\"queue\" : \"1\",
\"scan-time\" : \"60\",
\"scrypt\" : true,
\"temp-hysteresis\" : \"3\",
\"shares\" : \"0\",
\"kernel-path\" : \"/usr/local/bin\"
}" >> $FILE