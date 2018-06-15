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

|      Plugin      | Description                              | Command                           | Permission   | OS  |
|------------------|------------------------------------------|-----------------------------------|--------------|-----|
|    addadmin      | Adds user host to ADMIN list in config   | !addadmin <nick!ident@host>       |  OWNER       | All |
|    addowner      | Adds owner host to config file           | !addowner <nick!ident@host>       |  OWNER       | All |
|    autoop        | Adds host to auto op list in config file | !autoop <nick!ident@host>         |  OWNER/ADMIN | All |
|    ban           | Ban specified hostname                   | !ban <nick!ident@host>            |  OWNER/ADMIN | All |
|    bash          | Shows quotes from bash.org               | !bash                             |  ALL         | All |
|    cham          | Shows random text from file              | !cham <nick>                      |  OWNER/ADMIN | All |
|    checkupdate   | Checking for updates                     | !checkupdate                      |  OWNER       | All |
|    deop          | Deops someone                            | !deop <nick>                      |  OWNER/ADMIN | All |
|    devoice       | Devoice someone                          | !devoice <nick>                   |  OWNER/ADMIN | All |
|    gethost       | Ip address to hostname                   | !gethost <ip>                     |  ALL         | All |
|    fetch         | Plugins repository list / get            | !fetch list                       |  OWNER       | All |
|                  | Downloads plugins from repository        | !fetch get <plugin> <permissions> |  OWNER       | All |
|    hash          | Changing string to choosed algorithm     | !hash <algo> <string>             |  ALL         | All |
|                  | Lists available algorithms               | !hash help                        |  ALL         | All |
|    help          | Shows BOT commands                       | !help                             |  ALL         | All |
|    info          | Shows BOT information                    | !info                             |  OWNER       | All |
|    join          | BOT joins given channel                  | !join <#channel>                  |  OWNER       | All |
|    kick          | BOT kicks given user from channel        | !kick <#channel> <nick>           |  OWNER/ADMIN | All |
|    leave         | BOT parts given channel                  | !leave <#channel>                 |  OWNER       | All |
|    listadmins    | Shows BOT admin(s) host(s)               | !listadmins                       |  OWNER       | All |
|    listowners    | Shows BOT owner(s) host(s)               | !listowners                       |  OWNER       | All |
|    load          | Loads plugin to BOT                      | !load <plugin>                    |  OWNER       | All |
|    md5           | Changing string to MD5 hash              | !md5 <string>                     |  ALL         | All |
|    memusage      | Shows how much ram is being used by BOT  | !memusage                         |  OWNER       | All |
|    morse         | Converts given string to morse code      | !morse <string>                   |  ALL         | All |
|    note          | Adds a note                              | !note                             |  ALL         | All |
|                  | Delete all notes                         | !note clear                       |  ALL         | All |
|                  | Delete specified note                    | !note del <numer>                 |  ALL         | All |
|                  | Shows help                               | !note help                        |  ALL         | All |
|                  | Lists notes                              | !note list                        |  ALL         | All |
|    newnick       | Changes BOT nickname                     | !newnick <newnick>                |  OWNER       | All |
|    op            | BOT gives op to given nick               | !op <nick>                        |  OWNER/ADMIN | All |
|    panel         | Starts web admin panel for BOT           | !panel                            |  OWNER       | WIN |
|                  | Lists panel commands                     | !panel help                       |  OWNER       | WIN |
|                  | Starts web panel at specified port       | !panel start <port>               |  OWNER       | WIN |
|                  | Stops web panel                          | !panel stop                       |  OWNER       | WIN |
|    pause         | Pause BOT activity (plugins use, etc)    | !pause                            |  OWNER       | All |
|    plugin        | Plugins manipulation                     | !plugin                           |  OWNER       | All |
|                  | Deletes plugin from directory            | !plugin delete <plugin>           |  OWNER       | All |
|                  | Lists plugin commands                    | !plugin help                      |  OWNER       | All |
|                  | Loads given plugin to BOT                | !plugin load <plugin>             |  OWNER       | All |
|                  | Move plugin from one group to another    | !plugin move <plugin> <from> <to> |  OWNER       | All |
|                  | Unloads plugin from BOT                  | !plugin unload <plugin>           |  OWNER       | All |
|    ping          | Ping given host/ip                       | !ping <host/ip>                   |  ALL         | WIN |
|    quit          | Shutdown BOT                             | !quit                             |  OWNER       | All |
|    raw           | Sends raw string to server               | !raw <string> <2> <3> <4>         |  OWNER       | All |
|    remadmin      | Removes admin from config file           | !remadmin <nick!ident@host>       |  OWNER       | All |
|    remowner      | Removes owner host from config file      | !remowner <nick!ident@host>       |  OWNER       | All |
|    restart       | Restarts BOT                             | !restart                          |  OWNER       | All |
|    ripe          | Checks ip address and show results       | !ripe <ip address>                |  ALL         | All |
|    save          | Saving to config file                    | !save                             |  OWNER       | All |
|                  | Saving auto join value in config         | !save auto_join <string>          |  OWNER       | All |
|                  | Saving auto op value in config           | !save auto_op <string>            |  OWNER       | All |
|                  | Saving auto op list value in config      | !save auto_op_list <string>       |  OWNER       | All |
|                  | Saving auto rejoin value in config       | !save auto_rejoin <string>        |  OWNER       | All |
|                  | Saving bot owners value in config        | !save bot_owners <string>         |  OWNER       | All |
|                  | Saving bot response value in config      | !save bot_response <string>       |  OWNER       | All |
|                  | Saving command prefix value in config    | !save command_prefix <string>     |  OWNER       | All |
|                  | Saving connect delay value in config     | !save connect_delay <string>      |  OWNER       | All |
|                  | Saving ctcp finger value in config       | !save ctcp_finger <string>        |  OWNER       | All |
|                  | Saving ctcp response value in config     | !save ctcp_response <string>      |  OWNER       | All |
|                  | Saving ctcp version value in config      | !save ctcp_version <string>       |  OWNER       | All |
|                  | Saving channel value in config           | !save channel <string>            |  OWNER       | All |
|                  | Saving fetch server value in config      | !save fetch_server <string>       |  OWNER       | All |
|                  | Saving ident value in config             | !save ident <string>              |  OWNER       | All |
|                  | Saving logging value in config           | !save logging <string>            |  OWNER       | All |
|                  | Saving name value in config              | !save name <string>               |  OWNER       | All |
|                  | Saving nick value in config              | !save nick <string>               |  OWNER       | All |
|                  | Saving owner password value in config    | !save owner_password <string>     |  OWNER       | All |
|                  | Saving port value in config              | !save port <string>               |  OWNER       | All |
|                  | Saving server value in config            | !save server <string>             |  OWNER       | All |
|                  | Saving show raw value in config          | !save show_raw <string>           |  OWNER       | All |
|                  | Saving time zone value in config         | !save time_zone <string>          |  OWNER       | All |
|                  | Saving try connect value in config       | !save try_connect <string>        |  OWNER       | All |
|    say           | Say specified text to channel            | !say <text>                       |  OWNER/ADMIN | All |
|    seen          | Check nick when was last seen on channel | !seen <nickname>                  |  ALL         | All |
|    server        | Connects to specified server             | !server <server port>             |  OWNER       | All |
|    showconfig    | Shows BOT configuration                  | !showconfig                       |  OWNER       | All |
|    topic         | Changes topic on channel                 | !topic <string>                   |  OWNER/ADMIN | All |
|    unload        | Unloads plugin from BOT                  | !unload <plugin>                  |  OWNER       | All |
|    update        | Updates BOT if new version is available  | !update                           |  OWNER       | All |
|    uptime        | Shows BOT uptime                         | !uptime                           |  ALL         | All |
|    unban         | Unban specified user/hostmask            | !unban <nick!ident@host>          |  OWNER/ADMIN | All |
|    unpause       | Restore BOT from !pause mode             | !unpause                          |  OWNER       | All |
|    voice         | BOT gives voice                          | !voice <nick>                     |  OWNER/ADMIN | All |
|    weather       | Shows actual weather                     | !weather <city/place>             |  ALL         | All |
|    webstatus     | Shows http status info from given number | !webstatus <number>               |  ALL         | All |
|    webtitle      | Shows webpage titile                     | !webtitle <web address>           |  ALL         | All |
|    wikipedia     | Search wikipedia                         | !wikipedia <lang> <string>        |  ALL         | All |
|    winamp        | Controls winamp                          | !winamp                           |  OWNER       | WIN |
|                  | Next song                                | !winamp next                      |  OWNER       | WIN |
|                  | Pause song                               | !winamp pause                     |  OWNER       | WIN |
|                  | Play song                                | !winamp play                      |  OWNER       | WIN |
|                  | Previous song                            | !winamp prev                      |  OWNER       | WIN |
|                  | Stop song                                | !winamp stop                      |  OWNER       | WIN |
|                  | Shows song title                         | !winamp title                     |  OWNER       | WIN |
|    youtube       | Shows youtube video title from link      | !youtube <link>                   |  ALL         | All |