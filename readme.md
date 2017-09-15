![Powered by S3x0r](http://minionki.com.pl/powered.png)
### Easy to use IRC BOT in PHP language, ready to use from first start

<dl>
<pre>
     __                      __           __
 .--|  |.---.-.--.--.--.--. |  |--.-----.|  |_
 |  _  ||  _  |  |  |  |  | |  _  |  _  ||   _|
 |_____||___._|\___/|___  | |_____|_____||____|
                    |_____|
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
OWNERS HOSTS - EDIT IT (CONFIG.EXE or CONFIG.INI) FOR USING BOT COMMANDS!

              nick ! ident@ host
                |      |      |
example: S3x0r!~S3x0r@12-13-38-219.dsl.dynamic.simnet.is
</pre>
</dl>

Bot was writted to run from Windows systems (tested on Windows 7, XP)
but you can also run it from Linux/Unix by typing: 'php -f BOT.php'
To have all plugins working (on Linux/Unix) you need to enable
two extension modules in your php.ini config, modules: php_openssl, php_curl
and set allow_url_fopen=1 in php.ini

From windows you dont need to download PHP, just run bot from START_BOT.BAT file

To run bot with diffrent config file: php.exe "../BOT.php" some_other_config.ini

Plugins from 'USER' directory are for all users, everybody can use it
Owners can use plugins 'OWNER' & 'USER'
If you want to block some plugin from users just move it from 'USER' to 'OWNER'

You can now check for bot update, command: !checkupdate
And command: !update for downloading and installing new version.

To communicate with bot msg to it on channel using prefix: !<command>
You can change prefix in config file.

## BOT Commands:
<dl>
<pre>
add_owner -- Adds Owner host to config file: !add_owner <nick!ident@hostname>
auto_op -- Adds host to auto_op list in config file: !auto_op <nick!ident@hostname>
bash -- Shows quotes from bash.org: !bash
cham -- Shows random text from file: !cham <nick>
checkupdate -- Checking for updates: !checkupdate
deop -- Deops someone: !deop <nick>
devoice -- Devoice user: !devoice <nick>
dns -- Dns: !dns <address>
fetch list -- Lists plugins in repository
fetch get <plugin> -- Downloads plugins from repository
hash -- Changing string to choosed algorithm: !hash <help> to list algorithms
help -- Shows BOT commands: !help
htmltitle -- Shows webpage titile: !htmltitle <http://address>
http_status -- Shows http status: !http_status <number>
info -- Shows info: !info
join -- Joins channel: !join <#channel>
kick -- Kicks from channel: !kick <#channel> <who>
leave -- Leave channel: !leave <#channel>
list_owners -- Shows BOT owners: !list_owners
math -- Solves mathematical tasks: !math <eg. 8*8+6>
md5 -- Changing string to MD5: !md5 <string>
memusage -- Shows how much ram is being used by bot: !memusage
morse -- Converts to morse code: !morse <text>
newnick -- Changes nickname: !newnick <new_nick>
op -- Gives op: !op <nick>
plugin -- Plugins manipulation: !plugin help to list commands
plugin delete - Deletes plugin from directory: !plugin delete <plugin_name>
plugin move - Move plugin from OWNER dir to USER directory: !plugin move <plugin_name>
ping -- Pings a host: !ping <host>
quit -- Shutdown BOT: !quit
raw -- Sends raw string to server: !raw <string>
restart -- Restarts Bot: !restart
ripe -- Checks ip or host address and shows results: !ripe <ip or host>
save -- Saving to config file: !save <help> to list commands
save auto_join - Saving auto join on channel when connected: !save auto_join <yes/no>
save auto_op - Saving auto op when join channel: !save auto_op <yes/no>
save auto_op_list - Saving auto op list in config: !save auto_op_list <nick!ident@host, ...>
save auto_rejoin - Saving auto rejoin when kicked from channel: !save auto_rejoin <yes/no>
save bot_owners - Saving bot owners list in config: !save bot_owners <nick!ident@host, ...>
save bot_response - Saving where bot outputs messages: !save bot_response <channel/notice/priv>
save command_prefix - Saving prefix commands: !save command_prefix <new_prefix>
save connect_delay - Saving connect delay value to config: !save connect_delay <value>
save ctcp_finger - Saving ctcp finger in config: !save ctcp_finger <string>
save ctcp_response - Saving ctcp response in config: !save ctcp_response <yes/no>
save ctcp_version - Saving ctcp version in config: '!save ctcp_version <string>
save channel - Saving channel to config: !save_channel <#new_channel>
save fetch_server - Saving fetch server to config: !save_fetch_server <new_server>
save ident - Saving ident to config: !save_ident <new_ident>
save logging - Saving logging in config: !save logging <yes/no>
save name - Saving name to config: !save_name <new_name>
save nick - Saving nickname to config: !save_nick <new_nick>
save owner_password - Saving bot owner password in config: !save owner_password <password>
save port - Saving new port to config: !save_port <new_port>
save server - Saving new server to config: !save_server <new_server>
save show_raw - Saving show raw in config: !save show_raw <yes/no>
save time_zone - Saving time zone in config: !save time_zone <eg. Europe/Warsaw>
save try_connect - Saving how many times try connect to server: !save try_connect <value>
showconfig -- Shows BOT configuration: !showconfig
topic -- Changing Topic in channel: !topic <new_topic>
update -- Updates the BOT if new version is available: !update
uptime -- Shows BOT uptime: !uptime
voice -- Gives voice: !voice <nick>
weather -- Shows actual weather: !weather <city>
wikipedia -- Searchs wikipedia: !wikipedia <lang> <string>
winamp -- Controls winamp: !winamp <help>
winamp stop - Stop music: !winamp stop
winamp pause - Pause music: !winamp pause
winamp play - Play music: !winamp play
winamp next - Next song: !winamp next
winamp prev - Previous song: !winamp prev
winamp title - Show song title: !winamp title
youtube -- Shows youtube video title from link: !youtube <link>
</pre>
</dl>