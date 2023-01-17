### Easy to use IRC BOT in PHP language, ready to use from first start

<dl>
<pre>
    Author: S3x0r
</pre>
</dl>

### Important!
Before running the Bot, it must be configured in the file 'CONFIG.INI'
To become a Bot owner and have access to all commands
You have to write to Bot in a private message:
/msg <bot_nickname> register <password_from_config>
After entering the correct password, you
will be added to the host and have access to all commands.

You can also edit owner host in CONFIG.INI:

<dl>
<pre>
	  nick!ident@hostname
           |      |      |
example: S3x0r!~ident@hostname.com
</pre>
</dl>

Bot was writted to run from Windows systems (tested on Windows 11)
but you can also run it from Linux/Unix by typing: 'php BOT.php'
To have almost all plugins working on Linux/Unix you need to enable
two extension modules in your 'php.ini' config:

modules:
- php_openssl
- php_curl

and set: allow_url_fopen=1 in php.ini

From Windows systems you don't need to download PHP, just run bot from START_BOT.BAT

You can also run bot with arguments:
On Windows: php.exe "../../BOT.php" -h (to list options)
        or: php.exe BOT.php
On Linux: php BOT.php -h

To run bot with diffrent config file: php.exe "../../BOT.php" -c some_other_config.ini

Access Hierarchy:
Owner has access to: CORE, ADMIN & USER plugins
Admin has access to: ADMIN, USER plugins
User has access to: USER and some CORE plugins
If you want to block some plugin(s) from user's or admin's just move it from folder
to folder, etc...

BOT has also web panel, to start it use !panel start <port> and go to http://yourhost:portnumber
To shutdown panel: !panel stop

You can also check for bot update, command: !checkupdate
And command: !update for downloading and installing new version.

To communicate with bot msg to it on channel using prefix: !<command>
You can change prefix in config file.

## BOT Commands:

|      Plugin      | Description                               | Command                           | Permission   | OS  |
|------------------|-------------------------------------------|-----------------------------------|--------------|-----|
|    addowner      | Adds owner host to config file            | !addowner <nick!ident@host>       |  OWNER       | All |
|    autoop        | Adds host to auto op list in config file  | !autoop <nick!ident@host>         |  OWNER/ADMIN | All |
|    ban           | Ban specified hostname                    | !ban <hostname>                   |  OWNER/ADMIN | All |
|    cham          | Shows random text from file               | !cham <nick>                      |  OWNER/ADMIN | All |
|    checkupdate   | Checking for updates                      | !checkupdate                      |  OWNER       | All |
|    deop          | Deops someone                             | !deop <nick>                      |  OWNER/ADMIN | All |
|    devoice       | Devoice someone                           | !devoice <nick>                   |  OWNER/ADMIN | All |
|    gethost       | Ip address to hostname                    | !gethost <ip>                     |  OWNER/ADMIN | All |
|    fetch         | Plugins repository list / get             | !fetch list                       |  OWNER       | All |
|                  | Downloads plugins from repository         | !fetch get <plugin> <permissions> |  OWNER       | All |
|    hash          | Changing string to choosed algorithm      | !hash <algo> <string>             |  ALL         | All |
|                  | Lists available algorithms                | !hash help                        |  ALL         | All |
|    help          | Shows BOT commands                        | !help                             |  ALL         | All |
|    info          | Shows BOT information                     | !info                             |  OWNER       | All |
|    join          | BOT joins given channel                   | !join <#channel>                  |  OWNER       | All |
|    kick          | BOT kicks given user from channel         | !kick <#channel> <nick>           |  OWNER/ADMIN | All |
|    leave         | BOT parts given channel                   | !leave <#channel>                 |  OWNER       | All |
|    listowners    | Shows BOT owner(s) host(s)                | !listowners                       |  OWNER       | All |
|    load          | Loads plugin to BOT                       | !load <plugin>                    |  OWNER       | All |
|    md5           | Changing string to MD5 hash               | !md5 <string>                     |  ALL         | All |
|    memusage      | Shows how much ram is being used by BOT   | !memusage                         |  OWNER       | All |
|    morse         | Converts given string to morse code       | !morse <string>                   |  ALL         | All |
|    note          | Adds a note                               | !note                             |  ALL         | All |
|                  | Delete all notes                          | !note clear                       |  ALL         | All |
|                  | Delete specified note                     | !note del <numer>                 |  ALL         | All |
|                  | Shows help                                | !note help                        |  ALL         | All |
|                  | Lists notes                               | !note list                        |  ALL         | All |
|    newnick       | Changes BOT nickname                      | !newnick <newnick>                |  OWNER       | All |
|    op            | BOT gives op to given nick                | !op <nick>                        |  OWNER/ADMIN | All |
|    pause         | Pause BOT activity (plugins use, etc)     | !pause                            |  OWNER       | All |
|    ping          | Ping given host/ip                        | !ping <host/ip>                   |  OWNER/ADMIN | WIN |
|    quit          | Shutdown BOT                              | !quit                             |  OWNER       | All |
|    raw           | Sends raw string to server                | !raw <string> <2> <3> <4>         |  OWNER       | All |
|    remowner      | Removes owner host from config file       | !remowner <nick!ident@host>       |  OWNER       | All |
|    restart       | Restarts BOT                              | !restart                          |  OWNER       | All |
|    ripe          | Checks ip address and show results        | !ripe <ip address>                |  OWNER/ADMIN | All |
|    say           | Say specified text to channel             | !say <text>                       |  OWNER/ADMIN | All |
|    seen          | Check nick when was last seen on channel  | !seen <nickname>                  |  ALL         | All |
|    server        | Connects to specified server              | !server <server port>             |  OWNER       | All |
|    topic         | Changes topic on channel                  | !topic <string>                   |  OWNER/ADMIN | All |
|    unload        | Unloads plugin from BOT                   | !unload <plugin>                  |  OWNER       | All |
|    update        | Updates BOT if new version is available   | !update                           |  OWNER       | All |
|    uptime        | Shows BOT uptime                          | !uptime                           |  OWNER/ADMIN | All |
|    unban         | Unban specified user/hostmask             | !unban <nick!ident@host>          |  OWNER/ADMIN | All |
|    unpause       | Restore BOT from !pause mode              | !unpause                          |  OWNER       | All |
|    voice         | BOT gives voice                           | !voice <nick>                     |  OWNER/ADMIN | All |
|    webstatus     | Shows http status info from given number  | !webstatus <code>                 |  ALL         | All |
|    webtitle      | Shows webpage titile                      | !webtitle <web address>           |  ALL         | All |
|    whoami        | Displays user assigned name/privilege lvl | !whoami                           |  ALL         | All |
|    wiki          | Search wikipedia                          | !wiki <lang> <string>             |  ALL         | All |
|    winamp        | Controls winamp                           | !winamp                           |  OWNER       | WIN |
|                  | Next song                                 | !winamp next                      |  OWNER       | WIN |
|                  | Pause song                                | !winamp pause                     |  OWNER       | WIN |
|                  | Play song                                 | !winamp play                      |  OWNER       | WIN |
|                  | Previous song                             | !winamp prev                      |  OWNER       | WIN |
|                  | Stop song                                 | !winamp stop                      |  OWNER       | WIN |
|                  | Shows song title                          | !winamp title                     |  OWNER       | WIN |