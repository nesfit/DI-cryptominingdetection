# How to Detect Bitcoin Miners? By Traffic Forensics! 
# Vladimír Veselý, Martin Žádník for Digital Investigation journal
In order to verify and reproduce results outlined in the paper, we publicly disclose all our materials including source-codes and datasets. This repository contains following folders:


## PCAPs
PCAP files related to online mining server catalogue

## WEKA-CSV
CSV files containing feature vectors that are ready for Weka tool.
File wekaready_miners.csv contains feature vectors of positive samples, i.e. of miners.
File wekaready_notminers.csv contains feature vectors of negative samples, i.e. of not-miners.

The feature vector consists of the following features in this order:
1. ackpush/all - Number of flows with ACK+PUSH flags to all flows
2. bpp - Bytes per packet per flow per all flows
3. ppf - Packets per flow per all flows
4. ppm - Packets per minute
5. req/all - Request flows to all flows (request flow is considered a flow where src port is greater than dst port)
6. syn/all - Number of flows with SYN flag to all flows
7. rst/all - Number of flows with RST flag to all flows
7. fin/all - Number of flows with FIN flag to all flows
7. class - miner or notminer

For futher details on compuation of statistics see:
https://github.com/CESNET/Nemea-Detectors/blob/master/miner_detector/miner_detector.cpp

