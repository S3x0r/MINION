<?php
/* Copyright (c) 2013-2020, S3x0r <olisek@gmail.com>
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
$GLOBALS['TIMER5'] = time();
$GLOBALS['TIMER6'] = time();
$GLOBALS['TIMER7'] = time();
$GLOBALS['TIMER8'] = time();
$GLOBALS['TIMER9'] = time();
$GLOBALS['TIMER10'] = time();
$GLOBALS['TIMER11'] = time();
$GLOBALS['TIMER12'] = time();
$GLOBALS['TIMER13'] = time();
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
    /* TIMERS - 10 minutes */
    if (time()-$GLOBALS['TIMER3'] > 600) {
        every_10_minutes();
        $GLOBALS['TIMER3'] = time();
    }
    /* TIMERS - 15 minutes */
    if (time()-$GLOBALS['TIMER4'] > 900) {
        every_15_minutes();
        $GLOBALS['TIMER4'] = time();
    }
    /* TIMERS - 20 minutes */
    if (time()-$GLOBALS['TIMER5'] > 1200) {
        every_20_minutes();
        $GLOBALS['TIMER5'] = time();
    }
    /* TIMERS - 25 minutes */
    if (time()-$GLOBALS['TIMER6'] > 1500) {
        every_25_minutes();
        $GLOBALS['TIMER6'] = time();
    }
    /* TIMERS - 30 minutes */
    if (time()-$GLOBALS['TIMER7'] > 1800) {
        every_30_minutes();
        $GLOBALS['TIMER7'] = time();
    }
    /* TIMERS - 35 minutes */
    if (time()-$GLOBALS['TIMER8'] > 2100) {
        every_35_minutes();
        $GLOBALS['TIMER8'] = time();
    }
    /* TIMERS - 40 minutes */
    if (time()-$GLOBALS['TIMER9'] > 2400) {
        every_40_minutes();
        $GLOBALS['TIMER9'] = time();
    }
    /* TIMERS - 45 minutes */
    if (time()-$GLOBALS['TIMER10'] > 2700) {
        every_45_minutes();
        $GLOBALS['TIMER10'] = time();
    }
    /* TIMERS - 50 minutes */
    if (time()-$GLOBALS['TIMER11'] > 3000) {
        every_50_minutes();
        $GLOBALS['TIMER11'] = time();
    }
    /* TIMERS - 55 minutes */
    if (time()-$GLOBALS['TIMER12'] > 3300) {
        every_55_minutes();
        $GLOBALS['TIMER12'] = time();
    }
    /* TIMERS - 60 minutes */
    if (time()-$GLOBALS['TIMER13'] > 3600) {
        every_60_minutes();
        $GLOBALS['TIMER13'] = time();
    }
}
//---------------------------------------------------------------------------------------------------------
function every_1_minute()
{
    /* check if bot can change nick to original */
    if (empty($GLOBALS['stop'])) {
        if ($GLOBALS['CONFIG_KEEP_NICK'] == 'yes' && isset($GLOBALS['I_USE_RND_NICKNAME'])) {
            toServer("ISON :{$GLOBALS['CONFIG_NICKNAME']}");
        }
    }
}
//---------------------------------------------------------------------------------------------------------
function every_5_minutes()
{
}
//---------------------------------------------------------------------------------------------------------
function every_10_minutes()
{
}
//---------------------------------------------------------------------------------------------------------
function every_15_minutes()
{
}
//---------------------------------------------------------------------------------------------------------
function every_20_minutes()
{
}
//---------------------------------------------------------------------------------------------------------
function every_25_minutes()
{
}
//---------------------------------------------------------------------------------------------------------
function every_30_minutes()
{
}
//---------------------------------------------------------------------------------------------------------
function every_35_minutes()
{
}
//---------------------------------------------------------------------------------------------------------
function every_40_minutes()
{
}
//---------------------------------------------------------------------------------------------------------
function every_45_minutes()
{
}
//---------------------------------------------------------------------------------------------------------
function every_50_minutes()
{
}
//---------------------------------------------------------------------------------------------------------
function every_55_minutes()
{
}
//---------------------------------------------------------------------------------------------------------
function every_60_minutes()
{
}
