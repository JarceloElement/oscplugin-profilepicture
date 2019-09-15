<?php
/* Developed by WEBmods
 * Zagorski oglasnik j.d.o.o. za usluge | www.zagorski-oglasnik.com
 *
 * License: GPL-3.0-or-later
 * More info in license.txt
*/

if(!defined('ABS_PATH')) exit('ABS_PATH is not loaded. Direct access is not allowed.');
?>
<h1><?php _e('Help', profilepic_plugin()); ?></h1>
<div class="row">
    <div class="col-12">
        <p>Get URL of a  profile picture for an user.</p>
        <p>
            <var>$user</var> - here you need to specify user ID. Example values:
            <ul>
                <li><var>osc_logged_user_id()</var> - for logged user</li>
                <li><var>osc_item_user_id()</var> - for item page</li>
                <li><var>osc_comment_user_id()</var> - for item comments</li>
                <li><var>osc_user_id()</var> - for public profile</li>
            </ul>
        </p>
        <pre><code>profilepic_user_url(<var>$user</var>)</code></pre>
    </div>
</div>
