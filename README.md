# csgo_admin_page
A php based web front end to manage a Counter Strike Global Offensive server. We used this for internal games of Counter Strike GO at my company. People wanted to change the map or game time, but were unwilling or unable to RCON into the server and run the neccessary admin commands. We wanted a quick and easy front end to perform the most common admin tasks, so I created this tool.

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

## Usage

To run on a remote server this requires a php process manager (php-fpm) but it can be accessed without if the web browser has direct access to the IP and port of the steam server and has php installed.

* Checkout and copy the contents to your web root
* Edit csgo.php to give it the location and credentials of your steam server
>        define( 'SQ_SERVER_ADDR', '127.0.0.1' );
>        define( 'SQ_SERVER_PORT', 27015 );
>        define( 'SQ_CVAR_PASS', 'password');

* Direct your HTTP server (apache, nginx, etc) to index.php
* Point your web-browser to the web server path the now points to index.php
