<?php
/* Developed by WEBmods
 * Zagorski oglasnik j.d.o.o. za usluge | www.zagorski-oglasnik.com
 *
 * License: GPL-3.0-or-later
 * More info in license.txt
*/

/*
Plugin Name: Profile Picture Lite
Plugin URI: https://www.zagorski-oglasnik.com/
Description: Users can add profile pictures. As a bonus they can crop, rotate, flip, resize and reposition them.
Version: 1.1.0
Author: WEBmods by Zagorski Oglasnik jdoo
Author URI: https://www.zagorski-oglasnik.com/
Plugin update URI: http://loveosclass.com/update/profilepiclite/free
*/

define('PROFILEPIC_PATH', dirname(__FILE__) . '/' );
define('PROFILEPIC_FOLDER', osc_plugin_folder(__FILE__) . '/' );
define('PROFILEPIC_VERSION', 1100);

require_once PROFILEPIC_PATH.'oc-load.php';

function profilepic_install() {
    ProfilePicModel::newInstance()->install();
    osc_set_preference('original_size', '192', 'plugin_profilepic');
    osc_set_preference('quality', '80', 'plugin_profilepic');
    osc_set_preference('default_picture', 'default.jpg', 'plugin_profilepic');
    osc_set_preference('version', PROFILEPIC_VERSION, 'plugin_profilepic');
}
osc_register_plugin(osc_plugin_path(__FILE__), 'profilepic_install');

function profilepic_uninstall() {
    ProfilePicModel::newInstance()->uninstall();
    Preference::newInstance()->delete(array('s_section' => 'plugin_profilepic'));
}
osc_add_hook(osc_plugin_path(__FILE__) . '_uninstall', 'profilepic_uninstall');

function profilepic_check_update() {
    $current_version = osc_get_preference('version', 'plugin_profilepic');
    if(!$current_version || version_compare(PROFILEPIC_VERSION, $current_version)) {
        profilepic_update($current_version);
    }
}

function profilepic_update($current_version) {
    osc_set_preference('version', PROFILEPIC_VERSION, 'plugin_profilepic');
}
