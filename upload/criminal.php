<?php
/*
MCCodes FREE
criminal.php Rev 1.1.0
Copyright (C) 2005-2012 Dabomstew

This program is free software; you can redistribute it and/or
modify it under the terms of the GNU General Public License
as published by the Free Software Foundation; either version 2
of the License, or (at your option) any later version.

This program is distributed in the hope that it will be useful,
but WITHOUT ANY WARRANTY; without even the implied warranty of
MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
GNU General Public License for more details.

You should have received a copy of the GNU General Public License
along with this program; if not, write to the Software
Foundation, Inc., 51 Franklin Street, Fifth Floor, Boston, MA  02110-1301, USA.
*/

session_start();
require "global_func.php";
if ($_SESSION['loggedin'] == 0)
{
    header("Location: login.php");
    exit;
}
$userid = $_SESSION['userid'];
require "header.php";
$h = new headers;
$h->startheaders();
include "mysql.php";
global $c;
$is =
        mysql_query(
                "SELECT u.*,us.* FROM users u LEFT JOIN userstats us ON u.userid=us.userid WHERE u.userid=$userid",
                $c) or die(mysql_error());
$ir = mysql_fetch_array($is);
check_level();
$fm = money_formatter($ir['money']);
$cm = money_formatter($ir['crystals'], '');
$lv = date('F j, Y, g:i a', $ir['laston']);
$h->userdata($ir, $lv, $fm, $cm);
$h->menuarea();
$q = mysql_query("SELECT * FROM crimegroups ORDER by cgORDER ASC", $c);
print
        "<b>Criminal Centre</b><br />
<table width='75%'><tr><th>Crime</th><th>Cost</th><th>Do</th></tr>";
while ($r = mysql_fetch_array($q))
{
    print
            "<tr style='background-color:gray'><td colspan='3'>{$r['cgNAME']}</td></tr>";
    $q2 =
            mysql_query("SELECT * FROM crimes WHERE crimeGROUP={$r['cgID']}",
                    $c);
    while ($r2 = mysql_fetch_array($q2))
    {
        print
                "<tr><td>{$r2['crimeNAME']}</td><td>{$r2['crimeBRAVE']} Brave</td><td><a href='docrime.php?c={$r2['crimeID']}'>Do</a></td></tr>";
    }
}
$h->endpage();
