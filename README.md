# csgo_admin_page
A php based web front end to manage a CounterStrike Global Offensive server

Functions:

* Display current game mode and type along with map group and current map
* Change game mode (competative, deathmatch, demolition, armsrace)
* Change map (drop-down provided of all maps in the current map group)
* Switch to community map (input the id number of a community workshop map. This will trigger a download of the map and then switch to it.)
* Switch to community collection (input the id number of a community workshop map collection. This will trigger a download of all maps with in the collection. A new mapgroup will be created with that collection and then the server will switch to the first map.)

## PHP-Source-Query-Class

Based heavily on https://github.com/kruvas/PHP-Source-Query-Class, provides the Source Query protocol functions that allow communication with the server.

## index.php

The page displayed to the user

## csgo.php

Provides all functions that collect information from and manipulate the running game server. Where possible information used to populate the page is retrieved by querying the server via Source Query protocol.
