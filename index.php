<!DOCTYPE html>

<?php

require __DIR__ . '/csgo.php';

$Query = new SourceQuery( );
$Query->Connect( SQ_SERVER_ADDR, SQ_SERVER_PORT, SQ_TIMEOUT, SQ_ENGINE );
$Query->SetRconPassword( SQ_CVAR_PASS );

try
        {

		if (isset($_POST['Competitive'])) {
                	set_mode("competitive", $Query);
		} elseif (isset($_POST['Deathmatch'])) {
			set_mode("deathmatch", $Query);
		} elseif (isset($_POST['Demolition'])) {
			set_mode("demolition", $Query);
                } elseif (isset($_POST['Armsrace'])) {
                        set_mode("armsrace", $Query);
		}


                if (isset($_POST['map'])) {
			$newmap = $_POST["map"];
			trigger_newmap($Query, $newmap);
                }

                if (isset($_POST['mapidnumber'])) {
                        $newmap = $_POST["mapidnumber"];
                        workshop_map($Query, $newmap);
                }

                if (isset($_POST['collectionidnumber'])) {
                        $collection = $_POST["collectionidnumber"];
                        workshop_collection($Query, $collection);
                }


        }
        catch( Exception $e )
        {
                print( $e->getMessage( ));
        }



?>

<html>

<head>
<style>
table, th, td {
    border: 1px solid black;
}
</style>
</head>

<body>

<?php

        try
        {
		$maps = get_maps_in_group($Query);
		$map = get_map($Query);
                $mapgroup = get_mapgroup($Query);
                $cvars = get_all_cvars($Query);

                if ($cvars["game_mode"] == "1" && $cvars["game_type"] == "0") {
                        $description = "Competitive";
                } elseif ($cvars["game_mode"] == "2" && $cvars["game_type"] == "1" ) {
                        $description = "Deathmatch";
                } elseif ($cvars["game_mode"] == "1" && $cvars["game_type"] == "1" ) {
                        $description = "Demolition";
                } elseif ($cvars["game_mode"] == "0" && $cvars["game_type"] == "1" ) {
                        $description = "Arms Race";
                } else {
                        $description = "Unknown";
                }

        }
        catch( Exception $e )
        {
                print( $e->getMessage( ));
        }

        $Query->Disconnect( );
?>

<h2>Current server game mode information</h2>

<table>
  <tr>
    <td>Game Mode</td>
    <td><?php echo $cvars["game_mode"] ?></td>
  </tr>
  <tr>
    <td>Game Type</td>
    <td><?php echo $cvars["game_type"] ?></td>
  </tr>
  <tr>
    <td>Description</td>
    <td><?php echo $description ?></td>
  </tr>
  <tr>
    <td>Map Group</td>
    <td><?php echo $mapgroup ?></td>
  </tr>
  <tr>
    <td>Map</td>
    <td><?php echo $map ?></td>
  </tr>
</table>

<p></p>

<form action="/admin" method="post">
    <input type="submit" id="refresh" name="refresh" value="Refresh">
</form>


<h2>Change game mode</h2>

<p>
Changes will only take effect at the end of the round. To force the change through change the map below.
</p>

<form action="admin" method="post">
    <input type="submit" id="Competitive" name="Competitive" value="Competitive" class="login_form_submit">

    <input type="submit" id="Deathmatch" name="Deathmatch" value="Deathmatch" class="login_form_submit">

    <input type="submit" id="Demolition" name="Demolition" value="Demolition" class="login_form_submit">

    <input type="submit" id="Armsrace" name="Armsrace" value="Arms Race" class="login_form_submit">

</form>

<h2>Change Map</h2>

<p>
Change this will trigger an immediate map change, ending the current round. USE WITH CAUTION.
Map changes may take a few seconds, hit the refresh button above to check it has gone through. Do not hit the browser refresh button, it will send the map change again!
</p>

<form action="admin" method="post">
<select name="map">

<?php

foreach($maps as $mapname) {
	echo "<option value=\"$mapname\">$mapname</option>";
}

?>

</select>
<br><br>
<input type="submit" value="Trigger map change">

</form>

<h2>Switch to community map</h2>

<p>
input the id number of a community workshop map. This will trigger a download of the map and then switch to it, which may take a few minutes depending on whether the map has been used before.
</p>

<form action="admin" method="post">
<input type="text" name="mapidnumber">
<br><br>
<input type="submit" value="Community workshop map">

</form>

<h2>Switch to community collection</h2>

<p>
input the id number of a community workshop map collection. This will trigger a download of all maps with in the collection, which may take a few minutes. A new mapgroup will be created with that collection and then the server will switch to the first map.
</p>

<form action="admin" method="post">
<input type="text" name="collectionidnumber">
<br><br>
<input type="submit" value="Community workshop collection">

</form>


</body>
</html>
