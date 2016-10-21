<?php

require __DIR__ . '/PHP-Source-Query-Class/SourceQuery/SourceQuery.class.php';

	define( 'SQ_SERVER_ADDR', '127.0.0.1' );
        define( 'SQ_SERVER_PORT', 27015 );
        define( 'SQ_TIMEOUT',     1 );
        define( 'SQ_ENGINE',      SourceQuery :: SOURCE );
	define( 'SQ_CVAR_PASS', 'password');
	define( 'SQ_CONFIG_DIR', '/home/steam/games/cs_go/csgo');

function get_all_cvars($Query) {
	$all_cvars = explode("\n",$Query -> Rcon("cvarlist"));
	$cvars_array = array();

	foreach ($all_cvars as $this_cvar){
		$this_cvar_array = explode(":",$this_cvar);
                if (sizeof($this_cvar_array) > 1) {
			$cvars_array[trim($this_cvar_array[0])] = trim($this_cvar_array[1]);
		}
	}

        return $cvars_array;
}

function get_mapgroup($Query) {
try {
        $response = explode("\n",$Query -> Rcon("print_mapgroup_sv"));
        $mapgroup = explode(":",$response[0]);
	}
catch( Exception $e )
        {
                print( $e->getMessage( ));
        }

return trim($mapgroup[1]);
}

function get_maps_in_group($Query) {
   try {
      $response = explode("\n",$Query -> Rcon("print_mapgroup_sv"));
       }
   catch( Exception $e )
       {
       print( $e->getMessage( ));
       }

   $maps_array = array();
   $key = 1;

   while($key < (sizeof($response)-2)) {
	$mapname = explode("/", trim($response[$key]));
	array_push($maps_array, end($mapname));
	$key++;
   }
   return $maps_array;
}

function get_map($Query) {

        $game_info = $Query->GetInfo();
        return $game_info["Map"];
}


function trigger_newmap($Query, $map) {

try {
        	$Query -> Rcon("changelevel $map");
        }
catch( Exception $e )
        {
                print( $e->getMessage( ));
        }
}

function workshop_map($Query, $map) {

	try {
                $Query -> Rcon("host_workshop_map $map");
        }
	catch( Exception $e )
        {
                print( $e->getMessage( ));
        }
}

function workshop_collection($Query, $collection) {

        try {
                $Query -> Rcon("host_workshop_collection $collection");
        }
        catch( Exception $e )
        {
                print( $e->getMessage( ));
        }
}



function set_mode($mode, $Query) {

// Default to competitive
$game_mode = 1;
$game_type = 0;
$mapgroup = "mg_allmaps";

	switch ($mode) {
	case "competitive":
		$game_mode = 1;
		$game_type = 0;
		$mapgroup = "mg_allmaps";
		break;
        case "deathmatch":
                $game_mode = 2;
                $game_type = 1;
                $mapgroup = "mg_deathmatch";
                break;
        case "demolition":
                $game_mode = 1;
                $game_type = 1;
                $mapgroup = "mg_demolition";
                break;
        case "armsrace":
                $game_mode = 0;
                $game_type = 1;
                $mapgroup = "mg_armsrace";
                break;
        }

try {
	$Query->Rcon("game_mode $game_mode");
	$Query->Rcon("game_type $game_type");
	$Query->Rcon("mapgroup $mapgroup");
	}
catch( Exception $e )
        {
                print( $e->getMessage( ));
        }

}

function get_maps_in_group_old($mapgroup) {

$maps_array = get_maps_from_file($mapgroup, "/gamemodes_server.txt");
if (sizeof($maps_array) > 0) {
	return $maps_array;
} else {
	$maps_array = get_maps_from_file($mapgroup, "/gamemodes.txt");
}

return $maps_array;

}

function get_maps_from_file($mapgroup, $filename) {

$mapsfile = fopen( SQ_CONFIG_DIR . $filename,"r");
$maps_array = array();

// Search the file for mapgroups
while(!feof($mapsfile)) {
  $line = trim(fgets($mapsfile));
  if ($line == "\"mapgroups\"") {
        break;
  }
}

// Keep going until we get to the mapgroup we are interested in
while(!feof($mapsfile)) {
  $line = trim(fgets($mapsfile));
  if ($line == "\"$mapgroup\"") {
        break;
  }
}

// Keep going until we get to the list of maps
while(!feof($mapsfile)) {
  $line = trim(fgets($mapsfile));
  if ($line == "\"maps\"") {
        break;
  }
}

// Go down the list of maps until we find a closing curly brace
while(!feof($mapsfile) && $line != "}") {
  $line = trim(fgets($mapsfile));
  if ($line == "\"}\"") {
        break;
  }
  else {
        $mapname = explode('"',$line);
        if (sizeof($mapname) > 1) {
                array_push($maps_array, $mapname[1]);
        }
  }
}

fclose($mapsfile);

return $maps_array;
}
