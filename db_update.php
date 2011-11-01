<?php
//PHP file to interface with the DB.
//actual DB interface code is in another file which is quite obviously not going to be public
include ("db_connect.php");

//first, what's the file we're working with?
$file = "ffxiah_items.xml";

//Cool, now lets get the parsing machine set up
$xml = simplexml_load_file($file);

//now let's do the heavy lifting
//we're gonna have it throw away anything with a name of ".", since it's trash
foreach($xml->item as $item)
{
	if($item->en_name != ".")
	{
		//it's a good item! Let's get it in the database
		
		//let's form the start of the query
		$query = "INSERT INTO item (item_name, item_description, level_id, slot_id, race_id, job_id) values (";
		
		//now let's get some of that data we need
		$name = $item->en_name;
		$desc = $item->en_description;
		$level = $item->level;
		$slotID = $slots[$item->slots];
		$raceID = $races[$item->races];
		$jobID = $jobs[$item->jobs];
		$query .= "'$name', '$desc', '$level',  '$slotID', '$raceID', '$jobID')";

		//cool, let's send this down the pipe.
		
		if(!mysql_query($query, $con))
		{
			die('Could not add items to the database because ' . mysql_error());
		}
		else
			echo "Added '$item->en_name'.<br />";
	}
}

mysql_close($con);

?>