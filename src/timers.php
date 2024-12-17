<?php
/* Copyright (c) 2013-2024, minions
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

//---------------------------------------------------------------------------------------------------------
 !in_array(PHP_SAPI, array('cli', 'cli-server', 'phpdbg')) ?
  exit('This script can\'t be run from a web browser. Use CLI terminal to run it<br>'.
       'Visit <a href="https://github.com/S3x0r/MINION/">this page</a> for more information.') : false;
//---------------------------------------------------------------------------------------------------------

/* set timers */
$GLOBALS['TIMER1'] = time();
$GLOBALS['TIMER2'] = time();
$GLOBALS['TIMER3'] = time();
$GLOBALS['TIMER4'] = time();
//---------------------------------------------------------------------------------------------------------
function StartTimers()
{
    /* TIMERS - 1 minute */
    if (time()-$GLOBALS['TIMER1'] > 60) {
        every_1_minute();
        $GLOBALS['TIMER1'] = time();
    }
    /* TIMERS - 5 minutes */
    if (time()-$GLOBALS['TIMER2'] > 300) {
        every_5_minutes();
        $GLOBALS['TIMER2'] = time();
    }
    /* TIMERS - 30 minutes */
    if (time()-$GLOBALS['TIMER3'] > 1800) {
        every_30_minutes();
        $GLOBALS['TIMER3'] = time();
    }
    /* TIMERS - 60 minutes */
    if (time()-$GLOBALS['TIMER4'] > 3600) {
        every_60_minutes();
        $GLOBALS['TIMER4'] = time();
    }
}
//---------------------------------------------------------------------------------------------------------
function every_1_minute()
{
    /* keep nick - check if bot can change nick to original */
    if (loadValueFromConfigFile('AUTOMATIC', 'keep nick') == true && isset($GLOBALS['I_USE_RND_NICKNAME'])) {
        toServer('ISON :'.loadValueFromConfigFile('BOT', 'nickname'));
    }
    
    /* keep topic */
    if (BotOpped()) {
        if (loadValueFromConfigFile('CHANNEL', 'keep topic') == true && !empty(loadValueFromConfigFile('CHANNEL', 'channel topic'))) {
            toServer('TOPIC '.getBotChannel());
        }
    }
}
//---------------------------------------------------------------------------------------------------------
function every_5_minutes()
{
}
//---------------------------------------------------------------------------------------------------------
function every_30_minutes()
{
}
//---------------------------------------------------------------------------------------------------------
function every_60_minutes()
{
    /* try to compress old logs */
    zipLogs();
}
