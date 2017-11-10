![Powered by S3x0r](http://minionki.com.pl/powered.png)
![extreme programming](http://minionki.com.pl/xp-logo.png)
### Easy to use IRC BOT in PHP language, ready to use from first start

<dl>
<pre>
    Author: S3x0r, contact: olisek@gmail.com
</pre>
</dl>

### Important!
Before running BOT you must configure your BOT by editing CONFIG.INI file.

To be bot owner msg to bot by typing: /msg <bot_nickname> register <password_from_config>
You will be added to owner hosts.

You can also edit owner host in CONFIG.INI:

<dl>
<pre>
	      nick ! ident@ host
                |      |      |
example: S3x0r!~S3x0r@12-13-38-219.dsl.dynamic.simnet.is
</pre>
</dl>

Bot was writted to run from Windows systems (tested on Windows 7)
but you can also run it from Linux/Unix by typing: 'php -f BOT.php'
To have almost all plugins working on Linux/Unix you need to enable
two extension modules in your php.ini config, modules: php_openssl, php_curl
and set allow_url_fopen=1 in php.ini

From Windows you don't need to download PHP, just run bot from START_BOT.BAT file

To run bot with diffrent config file: php.exe "../../BOT.php" -c some_other_config.ini

Plugins from 'USER' directory are for all users, everybody can use it
Owners can use plugins 'OWNER' & 'USER'
If you want to block some plugin(s) from users just move it from 'USER' to 'OWNER' dir.

BOT has also web panel, to start it use !panel start <port> 
and go to http://yourhost:portnumber
To shutdown panel: !panel stop

You can also check for bot update, command: !checkupdate
And command: !update for downloading and installing new version.

To communicate with bot msg to it on channel using prefix: !<command>
You can change prefix in config file.

## BOT Commands:

|      Plugin      | Description                              | Command                       | Permission   |
|------------------|------------------------------------------|-------------------------------|--------------|
|    addowner      | Adds Owner host to config file           | !addowner <nick!ident@host>   |   OWNER      |
|    autoop        | Adds host to auto op list in config file | !autoop <nick!ident@host>     |   OWNER      |
|    ban           | Ban specified hostname                   | !ban <nick!ident@host>        |   OWNER      |
|    bash          | Shows quotes from bash.org               | !bash                         |   USER       |
|    cham          | Shows random text from file              | !cham <nick>                  |   USER       |
|    checkupdate   | Checking for updates                     | !checkupdate                  |   OWNER      |
|    deop          | Deops someone                            | !deop <nick>                  |   OWNER      |
|    devoice       | Devoice someone                          | !devoice <nick>               |   OWNER      |
|    gethost       | Ip address to hostname                   | !gethost <ip>                 |   USER       |
|    fetch         | Plugins repository list                  | !fetch list                   |   OWNER      |
|                  | Downloads plugins from repository        | !fetch get <plugin>           |   OWNER      |
|    hash          | Changing string to choosed algorithm     | !hash <algo> <string>         |   USER       |
|                  | Lists available algorithms               | !hash help                    |   USER       |
|    help          | Shows BOT commands                       | !help                         |   USER       |
|    info          | Shows BOT information                    | !info                         |   OWNER      |
|    join          | BOT joins given channel                  | !join <#channel>              |   OWNER      |
|    kick          | BOT kicks given user from channel        | !kick <#channel> <nick>       |   OWNER      |
|    leave         | BOT parts given channel                  | !leave <#channel>             |   OWNER      |
|    listowners    | Shows BOT owners hosts                   | !listowners                   |   OWNER      |
|    load          | Loads plugin to BOT                      | !load <plugin>                |   CORE/OWNER |
|    math          | Solves mathematical tasks                | !math <2+2>                   |   USER       |
|    md5           | Changing string to MD5 hash              | !md5 <string>                 |   USER       |
|    memusage      | Shows how much ram is being used by BOT  | !memusage                     |   OWNER      |
|    morse         | Converts given string to morse code      | !morse <string>               |   USER       |
|    newnick       | Changes BOT nickname                     | !newnick <newnick>            |   OWNER      |
|    op            | BOT gives op to given nick               | !op <nick>                    |   OWNER      |
|    panel         | Starts web admin panel for BOT           | !panel                        |   CORE/OWNER |
|                  | Lists panel commands                     | !panel help                   |   CORE/OWNER |
|                  | Starts web panel at specified port       | !panel start <port>           |   CORE/OWNER |
|                  | Stops web panel                          | !panel stop                   |   CORE/OWNER |
|    plugin        | Plugins manipulation                     | !plugin                       |   OWNER      |
|                  | Deletes plugin from directory            | !plugin delete <plugin>       |   OWNER      |
|                  | Lists plugin commands                    | !plugin help                  |   OWNER      |
|                  | Loads given plugin to BOT                | !plugin load <plugin>         |   OWNER      |
|                  | Move plugin from OWNER dir to USER dir   | !plugin move <plugin>         |   OWNER      |
|                  | Unloads plugin from BOT                  | !plugin unload <plugin>       |   OWNER      |
|    ping          | Ping given host                          | !ping <address>               |   USER       |
|    quit          | Shutdown BOT                             | !quit                         |   OWNER      |
|    raw           | Sends raw string to server               | !raw <string> <2> <3> <4>     |   OWNER      |
|    remowner      | Removes owner host from config file      | !remowner <nick!ident@host>   |   OWNER      |
|    restart       | Restarts BOT                             | !restart                      |   OWNER      |
|    ripe          | Checks ip/host address and show results  | !ripe <address>               |   USER       |
|    save          | Saving to config file                    | !save                         |   OWNER      |
|                  | Saving auto join value in config         | !save auto_join <string>      |   OWNER      |
|                  | Saving auto op value in config           | !save auto_op <string>        |   OWNER      |
|                  | Saving auto op list value in config      | !save auto_op_list <string>   |   OWNER      |
|                  | Saving auto rejoin value in config       | !save auto_rejoin <string>    |   OWNER      |
|                  | Saving bot owners value in config        | !save bot_owners <string>     |   OWNER      |
|                  | Saving bot response value in config      | !save bot_response <string>   |   OWNER      |
|                  | Saving command prefix value in config    | !save command_prefix <string> |   OWNER      |
|                  | Saving connect delay value in config     | !save connect_delay <string>  |   OWNER      |
|                  | Saving ctcp finger value in config       | !save ctcp_finger <string>    |   OWNER      |
|                  | Saving ctcp response value in config     | !save ctcp_response <string>  |   OWNER      |
|                  | Saving ctcp version value in config      | !save ctcp_version <string>   |   OWNER      |
|                  | Saving channel value in config           | !save channel <string>        |   OWNER      |
|                  | Saving fetch server value in config      | !save fetch_server <string>   |   OWNER      |
|                  | Saving ident value in config             | !save ident <string>          |   OWNER      |
|                  | Saving logging value in config           | !save logging <string>        |   OWNER      |
|                  | Saving name value in config              | !save name <string>           |   OWNER      |
|                  | Saving nick value in config              | !save nick <string>           |   OWNER      |
|                  | Saving owner password value in config    | !save owner_password <string> |   OWNER      |
|                  | Saving port value in config              | !save port <string>           |   OWNER      |
|                  | Saving server value in config            | !save server <string>         |   OWNER      |
|                  | Saving show raw value in config          | !save show_raw <string>       |   OWNER      |
|                  | Saving time zone value in config         | !save time_zone <string>      |   OWNER      |
|                  | Saving try connect value in config       | !save try_connect <string>    |   OWNER      |
|    showconfig    | Shows BOT configuration                  | !showconfig                   |   OWNER      |
|    topic         | Changes topic on channel                 | !topic <string>               |   OWNER      |
|    unload        | Unloads plugin from BOT                  | !unload <plugin>              |   CORE/OWNER |
|    update        | Updates BOT if new version is available  | !update                       |   OWNER      |
|    uptime        | Shows BOT uptime                         | !uptime                       |   USER       |
|    voice         | BOT gives voice                          | !voice <nick>                 |   OWNER      |
|    weather       | Shows actual weather                     | !weather <city/place>         |   USER       |
|    webstatus     | Shows http status info from given number | !webstatus <number>           |   USER       |
|    webtitle      | Shows webpage titile                     | !webtitle <web address>       |   USER       |
|    wikipedia     | Search wikipedia                         | !wikipedia <lang> <string>    |   USER       |
|    winamp        | Controls winamp                          | !winamp                       |   OWNER      |
|                  | Next song                                | !winamp next                  |   OWNER      |
|                  | Pause song                               | !winamp pause                 |   OWNER      |
|                  | Play song                                | !winamp play                  |   OWNER      |
|                  | Previous song                            | !winamp prev                  |   OWNER      |
|                  | Stop song                                | !winamp stop                  |   OWNER      |
|                  | Shows song title                         | !winamp title                 |   OWNER      |
|    youtube       | Shows youtube video title from link      | !youtube <link>               |   USER       |
