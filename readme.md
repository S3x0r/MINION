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
but you can also run it from Linux/Unix by typing: 'php BOT.php'
To have almost all plugins working on Linux/Unix you need to enable
two extension modules in your 'php.ini' config:

modules:
- php_openssl
- php_curl

and set: allow_url_fopen=1 in php.ini

From Windows systems you don't need to download PHP, just run bot from START_BOT.BAT
There is also silent mode without output to console & no logs, run: START_SILENT.BAT

You can also run bot with arguments:
On Windows: php.exe "../../BOT.php" -h (to list options)
On Linux: php BOT.php -h

To run bot with diffrent config file: php.exe "../../BOT.php" -c some_other_config.ini

Access Hierarchy:
Owner has access to: CORE commands, ADMIN plugins & USER plugins
Admin has access to: ADMIN plugins & USER plugins
User has access to: User plugins
If you want to block some plugin(s) from user's or admin's just move it from folder
to folder, etc...

BOT has also web panel, to start it use !panel start <port> and go to http://yourhost:portnumber
To shutdown panel: !panel stop

You can also check for bot update, command: !checkupdate
And command: !update for downloading and installing new version.

To communicate with bot msg to it on channel using prefix: !<command>
You can change prefix in config file.

## BOT Commands:

|      Plugin      | Description                              | Command                       | Permission   | Platform  |
|------------------|------------------------------------------|-------------------------------|--------------|-----------|
|    addadmin      | Adds user host to ADMIN list in config   | !addadmin <nick!ident@host>   |  OWNER       |  All      |
|    addowner      | Adds owner host to config file           | !addowner <nick!ident@host>   |  OWNER       |  All      |
|    autoop        | Adds host to auto op list in config file | !autoop <nick!ident@host>     |  OWNER/ADMIN |  All      |
|    ban           | Ban specified hostname                   | !ban <nick!ident@host>        |  OWNER/ADMIN |  All      |
|    bash          | Shows quotes from bash.org               | !bash                         |  USER        |  All      |
|    cham          | Shows random text from file              | !cham <nick>                  |  USER        |  All      |
|    checkupdate   | Checking for updates                     | !checkupdate                  |  OWNER       |  All      |
|    deop          | Deops someone                            | !deop <nick>                  |  OWNER/ADMIN |  All      |
|    devoice       | Devoice someone                          | !devoice <nick>               |  OWNER/ADMIN |  All      |
|    gethost       | Ip address to hostname                   | !gethost <ip>                 |  USER        |  All      |
|    fetch         | Plugins repository list                  | !fetch list                   |  OWNER       |  All      |
|                  | Downloads plugins from repository        | !fetch get <plugin>           |  OWNER       |  All      |
|    hash          | Changing string to choosed algorithm     | !hash <algo> <string>         |  USER        |  All      |
|                  | Lists available algorithms               | !hash help                    |  USER        |  All      |
|    help          | Shows BOT commands                       | !help                         |  USER        |  All      |
|    info          | Shows BOT information                    | !info                         |  OWNER       |  All      |
|    join          | BOT joins given channel                  | !join <#channel>              |  OWNER       |  All      |
|    kick          | BOT kicks given user from channel        | !kick <#channel> <nick>       |  OWNER/ADMIN |  All      |
|    leave         | BOT parts given channel                  | !leave <#channel>             |  OWNER       |  All      |
|    listowners    | Shows BOT owners hosts                   | !listowners                   |  OWNER       |  All      |
|    load          | Loads plugin to BOT                      | !load <plugin>                |  CORE/OWNER  |  All      |
|    md5           | Changing string to MD5 hash              | !md5 <string>                 |  USER        |  All      |
|    memusage      | Shows how much ram is being used by BOT  | !memusage                     |  OWNER       |  All      |
|    morse         | Converts given string to morse code      | !morse <string>               |  USER        |  All      |
|    note          | Adds a note                              | !note                         |  USER        |  All      |
|                  | Delete all notes                         | !note clear                   |  USER        |  All      |
|                  | Delete specified note                    | !note del <numer>             |  USER        |  All      |
|                  | Shows help                               | !note help                    |  USER        |  All      |
|                  | Lists notes                              | !note list                    |  USER        |  All      |
|    newnick       | Changes BOT nickname                     | !newnick <newnick>            |  OWNER       |  All      |
|    op            | BOT gives op to given nick               | !op <nick>                    |  OWNER/ADMIN |  All      |
|    panel         | Starts web admin panel for BOT           | !panel                        |  CORE/OWNER  |  WIN      |
|                  | Lists panel commands                     | !panel help                   |  CORE/OWNER  |  WIN      |
|                  | Starts web panel at specified port       | !panel start <port>           |  CORE/OWNER  |  WIN      |
|                  | Stops web panel                          | !panel stop                   |  CORE/OWNER  |  WIN      |
|    plugin        | Plugins manipulation                     | !plugin                       |  OWNER       |  All      |
|                  | Deletes plugin from directory            | !plugin delete <plugin>       |  OWNER       |  All      |
|                  | Lists plugin commands                    | !plugin help                  |  OWNER       |  All      |
|                  | Loads given plugin to BOT                | !plugin load <plugin>         |  OWNER       |  All      |
|                  | Move plugin from OWNER dir to USER dir   | !plugin move <plugin>         |  OWNER       |  All      |
|                  | Unloads plugin from BOT                  | !plugin unload <plugin>       |  OWNER       |  All      |
|    ping          | Ping given host                          | !ping <address>               |  USER        |  WIN      |
|    quit          | Shutdown BOT                             | !quit                         |  OWNER       |  All      |
|    raw           | Sends raw string to server               | !raw <string> <2> <3> <4>     |  OWNER       |  All      |
|    remadmin      | Removes admin from config file           | !remadmin <nick!ident@host>   |  OWNER       |  All      |
|    remowner      | Removes owner host from config file      | !remowner <nick!ident@host>   |  OWNER       |  All      |
|    restart       | Restarts BOT                             | !restart                      |  OWNER       |  All      |
|    ripe          | Checks ip/host address and show results  | !ripe <address>               |  USER        |  All      |
|    save          | Saving to config file                    | !save                         |  OWNER       |  All      |
|                  | Saving auto join value in config         | !save auto_join <string>      |  OWNER       |  All      |
|                  | Saving auto op value in config           | !save auto_op <string>        |  OWNER       |  All      |
|                  | Saving auto op list value in config      | !save auto_op_list <string>   |  OWNER       |  All      |
|                  | Saving auto rejoin value in config       | !save auto_rejoin <string>    |  OWNER       |  All      |
|                  | Saving bot owners value in config        | !save bot_owners <string>     |  OWNER       |  All      |
|                  | Saving bot response value in config      | !save bot_response <string>   |  OWNER       |  All      |
|                  | Saving command prefix value in config    | !save command_prefix <string> |  OWNER       |  All      |
|                  | Saving connect delay value in config     | !save connect_delay <string>  |  OWNER       |  All      |
|                  | Saving ctcp finger value in config       | !save ctcp_finger <string>    |  OWNER       |  All      |
|                  | Saving ctcp response value in config     | !save ctcp_response <string>  |  OWNER       |  All      |
|                  | Saving ctcp version value in config      | !save ctcp_version <string>   |  OWNER       |  All      |
|                  | Saving channel value in config           | !save channel <string>        |  OWNER       |  All      |
|                  | Saving fetch server value in config      | !save fetch_server <string>   |  OWNER       |  All      |
|                  | Saving ident value in config             | !save ident <string>          |  OWNER       |  All      |
|                  | Saving logging value in config           | !save logging <string>        |  OWNER       |  All      |
|                  | Saving name value in config              | !save name <string>           |  OWNER       |  All      |
|                  | Saving nick value in config              | !save nick <string>           |  OWNER       |  All      |
|                  | Saving owner password value in config    | !save owner_password <string> |  OWNER       |  All      |
|                  | Saving port value in config              | !save port <string>           |  OWNER       |  All      |
|                  | Saving server value in config            | !save server <string>         |  OWNER       |  All      |
|                  | Saving show raw value in config          | !save show_raw <string>       |  OWNER       |  All      |
|                  | Saving time zone value in config         | !save time_zone <string>      |  OWNER       |  All      |
|                  | Saving try connect value in config       | !save try_connect <string>    |  OWNER       |  All      |
|    showconfig    | Shows BOT configuration                  | !showconfig                   |  OWNER       |  All      |
|    topic         | Changes topic on channel                 | !topic <string>               |  OWNER/ADMIN |  All      |
|    unload        | Unloads plugin from BOT                  | !unload <plugin>              |  CORE/OWNER  |  All      |
|    update        | Updates BOT if new version is available  | !update                       |  OWNER       |  All      |
|    uptime        | Shows BOT uptime                         | !uptime                       |  USER        |  All      |
|    unban         | Unban specified user/hostmask            | !unban <nick!ident@host>      |  OWNER/ADMIN |  All      |
|    voice         | BOT gives voice                          | !voice <nick>                 |  OWNER/ADMIN |  All      |
|    weather       | Shows actual weather                     | !weather <city/place>         |  USER        |  All      |
|    webstatus     | Shows http status info from given number | !webstatus <number>           |  USER        |  All      |
|    webtitle      | Shows webpage titile                     | !webtitle <web address>       |  USER        |  All      |
|    wikipedia     | Search wikipedia                         | !wikipedia <lang> <string>    |  USER        |  All      |
|    winamp        | Controls winamp                          | !winamp                       |  OWNER       |  WIN      |
|                  | Next song                                | !winamp next                  |  OWNER       |  WIN      |
|                  | Pause song                               | !winamp pause                 |  OWNER       |  WIN      |
|                  | Play song                                | !winamp play                  |  OWNER       |  WIN      |
|                  | Previous song                            | !winamp prev                  |  OWNER       |  WIN      |
|                  | Stop song                                | !winamp stop                  |  OWNER       |  WIN      |
|                  | Shows song title                         | !winamp title                 |  OWNER       |  WIN      |
|    youtube       | Shows youtube video title from link      | !youtube <link>               |  USER        |  All      |
