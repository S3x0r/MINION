<?php
/* Copyright (c) 2013-2018, S3x0r <olisek@gmail.com>
 *
 * Permission to use, copy, modify, and distribute this software for any
 * purpose with or without fee is hereby granted, provided that the above
 * copyright notice and this permission notice appear in all copies.
 *
 * THE SOFTWARE IS PROVIDED "AS IS" AND THE AUTHOR DISCLAIMS ALL WARRANTIES
 * WITH REGARD TO THIS SOFTWARE INCLUDING ALL IMPLIED WARRANTIES OF
 * MERCHANTABILITY AND FITNESS. IN NO EVENT SHALL THE AUTHOR BE LIABLE FOR
 * ANY SPECIAL, DIRECT, INDIRECT, OR CONSEQUENTIAL DAMAGES OR ANY DAMAGES
 * WHATSOEVER RESULTING FROM LOSS OF USE, DATA OR PROFITS, WHETHER IN AN
 * ACTION OF CONTRACT, NEGLIGENCE OR OTHER TORTIOUS ACTION, ARISING OUT OF
 * OR IN CONNECTION WITH THE USE OR PERFORMANCE OF THIS SOFTWARE.
 */

if (PHP_SAPI !== 'cli') {
    die('<h2>This script can\'t be run from a web browser. Use terminal to run it<br>
         Visit https://github.com/S3x0r/MINION/ website for more instructions.</h2>');
}
    $VERIFY = 'bfebd8778dbc9c58975c4f09eae6aea6ad2b621ed6a6ed8a3cbc1096c6041f0c';
    $plugin_description = 'Shows http status: '.$GLOBALS['CONFIG_CMD_PREFIX'].'webstatus <number>';
    $plugin_command = 'webstatus';

function plugin_webstatus()
{
    if (OnEmptyArg('webstatus <number>')) {
    } else {
             BOT_RESPONSE(httpstatus($GLOBALS['args']));

             CLI_MSG('[PLUGIN: webstatus] by: '.$GLOBALS['USER'].' ('.$GLOBALS['USER_HOST'].') | chan: '.
                     $GLOBALS['channel'], '1');
    }
}

function httpstatus($args)
{
    $codes = array(
    "100" => "Continue (1xx Informational)",
    "101" => "Switching Protocols (1xx Informational)",
    "102" => "Processing (1xx Informational, WebDAV, RFC 2518)",
    "103" => "CheckPoint (1xx Informational, Unofficial)",
    "200" => "OK (2xx Success)",
    "201" => "Created (2xx Success)",
    "202" => "Accepted (2xx Success)",
    "203" => "Non-Authoritative Information (2xx Success, since HTTP/1.1)",
    "204" => "No Content (2xx Success)",
    "205" => "Reset Content (2xx Success)",
    "206" => "Partial Content (2xx Success, RFC 7233)",
    "207" => "Multi-Status (2xx Success, WebDAV, RFC 4918)",
    "208" => "Already Reported (2xx Success, WebDAV, RFC 5842)",
    "226" => "IM Used (2xx Success, RFC 3229)",
    "300" => "Multiple Choices (3xx Redirection)",
    "301" => "Moved Permanently (3xx Redirection)",
    "302" => "Found (3xx Redirection)",
    "303" => "See Other (3xx Redirection, since HTTP/1.1)",
    "304" => "Not Modified (3xx Redirection, RFC 7232)",
    "305" => "Use Proxy (3xx Redirection, since HTTP/1.1)",
    "306" => "Switch Proxy (3xx Redirection)",
    "307" => "Temporary Redirect (3xx Redirection, since HTTP/1.1)",
    "308" => "Permanent Redirect (3xx Redirection, RFC 7538)",
    "400" => "Bad Request (4xx Client Error)",
    "401" => "Unauthorized (4xx Client Error, RFC 7235)",
    "402" => "Payment Required (4xx Client Error)",
    "403" => "Forbidden (4xx Client Error)",
    "404" => "Not Found (4xx Client Error)",
    "405" => "Method Not Allowed (4xx Client Error)",
    "406" => "Not Acceptable (4xx Client Error)",
    "407" => "Proxy Authentication Required (4xx Client Error, RFC 7235)",
    "408" => "Request Timeout (4xx Client Error)",
    "409" => "Conflict (4xx Client Error)",
    "410" => "Gone (4xx Client Error)",
    "411" => "Length Required (4xx Client Error)",
    "412" => "Precondition Failed (4xx Client Error, RFC 7232)",
    "413" => "Payload Too Large (4xx Client Error, RFC 7231)",
    "414" => "URI Too Long (4xx Client Error, RFC 7231)",
    "415" => "Unsupported Media Type (4xx Client Error)",
    "416" => "Range Not Satisfiable (4xx Client Error, RFC 7233)",
    "417" => "Expectation Failed (4xx Client Error)",
    "418" => "I'm a teapot (4xx Client Error, RFC 2324)",
    "419" => "I'm a fox (4xx Client Error, Unofficial, Smoothwall/Foxwall)",
    "420" => "Method Failure (4xx Client Error, Unofficial, Spring Framework)
    || Enhance Your Calm (4xx Client Error, Unofficial, Twitter)",
    "421" => "Misdirected Request (4xx Client Error, RFC 7540)",
    "422" => "Unprocessable Entity (4xx Client Error, WebDAV, RFC 4918)",
    "423" => "Locked (4xx Client Error, WebDAV, RFC 4918)",
    "424" => "Failed Dependency (4xx Client Error, WebDAV, RFC 4918)",
    "426" => "Upgrade Required (4xx Client Error)",
    "428" => "Precondition Required (4xx Client Error, RFC 6585)",
    "429" => "Too Many Requests (4xx Client Error, RFC 6585)",
    "431" => "Request Header Fields Too Large (4xx Client Error, RFC 6585)",
    "440" => "Login Timeout (4xx Client Error, Unofficial, Internet Information Services",
    "444" => "No Response (4xx Client Error, Unofficial, nginx)",
    "449" => "Retry With (4xx Client Error, Unofficial, Internet Information Services",
    "450" => "Blocked by Windows Parental Controls (4xx Client Error, Unofficial, Microsoft)",
    "451" => "Unavailable For Legal Reasons (4xx Client Error) || Redirect
    (4xx Client Error, Unofficial, Internet Information Services)",
    "495" => "SSL Certificate Error (4xx Client Error, Unofficial, nginx)",
    "496" => "SSL Certificate Required (4xx Client Error, Unofficial, nginx)",
    "497" => "HTTP Request Sent to HTTPS Port (4xx Client Error, Unofficial, nginx)",
    "498" => "Invalid Token (4xx Client Error, Unofficial, Esri)",
    "499" => "Token Required (4xx Client Error, Unofficial, Esri) || Request has been forbidden
    by antivirus (4xx Client Error, Unofficial) || Client Closed Request (4xx Client Error, Unofficial, nginx)",
    "500" => "Internal Server Error (5xx Server Error)",
    "501" => "Not Implemented (5xx Server Error)",
    "502" => "Bad Gateway (5xx Server Error)",
    "503" => "Service Unavailable (5xx Server Error)",
    "504" => "Gateway Timeout (5xx Server Error)",
    "505" => "HTTP Version Not Supported (5xx Server Error)",
    "506" => "Variant Also Negotiates (5xx Server Error, RFC 2295)",
    "507" => "Insufficient Storage (5xx Server Error, WebDAV, RFC 4918)",
    "508" => "Loop Detected (5xx Server Error, WebDAV, RFC 5842)",
    "509" => "Bandwidth Limit Exceeded (5xx Server Error, Unofficial, cPanel)",
    "510" => "Not Extended (5xx Server Error, RFC 2774)",
    "511" => "Network Authentication Required (5xx Server Error, RFC 6585)",
    "520" => "Unknown Error (5xx Server Error, Unofficial, CloudFlare)",
    "521" => "Web Server Is Down (5xx Server Error, Unofficial, CloudFlare)",
    "522" => "Connection Timed Out (5xx Server Error, Unofficial, CloudFlare)",
    "523" => "Origin is Unreachable (5xx Server Error, Unofficial, CloudFlare)",
    "524" => "A Timeout Occurred (5xx Server Error, Unofficial, CloudFlare)",
    "525" => "SSL Handshake Failed (5xx Server Error, Unofficial, CloudFlare)",
    "526" => "Invalid SSL Certificate (5xx Server Error, Unofficial, CloudFlare)",
    "530" => "Site is frozen (5xx Server Error, Unofficial, Pantheon)"
    );

    if ((int)$args < 100 || (int)$args > 599) {
        return 'This is not a valid HTTP status code';
    } else {
        if (array_key_exists((int)$args, $codes)) {
            return (int)$args.' means: '.$codes[(int)$args];
        } else {
            return 'This HTTP status code is not in use';
        }
    }
}
