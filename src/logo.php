<?php
/* Copyright (c) 2013-2017, S3x0r <olisek@gmail.com>
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
    die('<h2>This script can\'t be run from a web browser. Use CLI to run it -> php BOT.php</h2>');
}
//---------------------------------------------------------------------------------------------------------
function Logo()
{
    if ($GLOBALS['CONFIG_SHOW_LOGO'] == 'yes') {
        if (!IsSilent()) {
            echo "
    B@B@B@B@@@B@B@B@B@B@B@B@@@B@B@B@B@B@@@@@@@B@B@B@B@B@@@B@B
    @B@BGB@B@B@B@B@@@B@@@B@B@B@@@B@B@B@B@B@@@B@B@B@@@B@@@@@B@
    B@B@  :@Bi:@B@B@B@@@B@BGS522s22SXMB@B@B@B@B@B@B@B@@@B@B@B
    @: r   :   H@B@B@B@9sr;rrs5ssss2H2229M@@@B@B@B@B@B@B@B@@@
    B         S@B@@@B,      ,::rsGB5:,  ,:i9@@B@B@B@B@B@, B@B
    @B@M,     @B@X@X   rMB@Mr:,:MS          iB@B@B2  B@   @@@
    B@@@B@    :@BGB  sB@B@;sBBrii  rB@B@B2:, :B@B@i         s
    @@@B@@@ii:sB@9X ,@@B,    BSi  9Bi ,B@B@r,  M@B@B        S
    B@@@B@B@92,@9,X  @B@,   ,@2i  @     B@GX:,  B@@,     X@@B
    @B@@@B@BMs:r@r;i i@B@G2M@S::, @s  ,X@G92,   ,B@    B@B@B@
    @@B@B@M@B2r:sssr: i29@B5i,  r :@B@B@BXr,,   ,@;::rM@B@B@B
    @B@B@B@B@Gs:rHSSsi:,,,,     ,:,,rssri,,,iir,9s  rB@B@B@B@
    B@B@B@B@B@si:XSSSsrsi::,,,::,:::,,,, ,,:;rsr,  :B@B@B@B@B
    @B@B@B@@@BG: :XXG: :rssssS3x0rS2ssr::irrrrrr  ,B@B@B@B@B@
    B@B@B@B@B@Bs  :SGM                 :rrrsr,    G@B@@@B@B@@
    @B@@@B@B@B@Xs  :SM@               ,ssss,     r@B@B@B@B@B@
    B@B@B@@@B@B2Hs  :SM@@sr:,      :sMG22s,   ,r:@@@B@B@B@B@B
    @B@B@B@B@B@2s9s,  ,::r222sHSX222srri:   ,rrirB@B@B@B@B@B@
    B@B@B@B@B@B2s292                       :rri:2@B@B@B@B@B@B
    @B@B@B@@@B@Ss29s,  ,, ,         ,     rrrii,M@@B@@@B@B@B@
    B@B@B@B@B@@MsXGs,,,,, ,,:i:,,,       ,ssrriiB@B@B@@@B@B@B
    @B@B@B@@@B@r:r5r ,,,, ,,,,, ,,       ,rii:,,@B@B@@@B@B@B@
    B@B@B@B@B@@:   ,,:,,,,          ,,          G@@@B@B@B@B@B
    @B@B@B@B@B@B   ,,,,,,,,   ,                X@B@B@B@B@B@@@
    B@B@B@B@B@B@B        , , ,,               9@B@B@B@B@B@B@B
    @B@B@@@B@B@B@Br                         i@@B@B@B@B@B@B@B@
    B@B@B@B@B@@@B@B@Br:                  rM@B@B@B@B@B@B@B@B@@
    @B@B@B@B@@@B@B@@@B@B@2           :GB@BBG9XXSSS9X9999G9GGM
    B@B@@@B@B@B@B@@@B@B@@s           Srri;i;rrrssssssss22S5HS
    @B@B@B@B@B@BBMMGG9G:              :,::::iir;rs22SXGGMMMMB".PHP_EOL;

             echo PHP_EOL.'    MINION - ver: '.VER.', '.TR_10.' S3x0r, '.TR_11.' olisek@gmail.com'.PHP_EOL;
             echo '                   '.TR_12.' '.TotalLines().' :)'.PHP_EOL.PHP_EOL.PHP_EOL;
        }
    }
}
