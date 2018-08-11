<?php
// Helper functions for view layer


// interpret integer values as string
function convertMiningPropStatus($status)
{
	if($status == 0)
		return "down";
	else if($status == 1)
		return "up";
	else if($status == 2)
		return "listen";
}