<?php
/* Developed by WEBmods
 * Zagorski oglasnik j.d.o.o. za usluge | www.zagorski-oglasnik.com
 *
 * License: GPL-3.0-or-later
 * More info in license.txt
*/

if(!defined('ABS_PATH')) exit('ABS_PATH is not loaded. Direct access is not allowed.');

/* Get plugin folder. */
function profilepic_plugin() {
    return 'zo_profilepic';
}

/* Get asset URL. */
function profilepic_asset_url($file = '') {
    return osc_base_url().'oc-content/plugins/'.profilepic_plugin().'/'.$file;
}

/* Get preference. */
function profilepic_pref($pref, $trim = 0) {
    if($trim)
        return trim(osc_get_preference($pref, 'plugin_profilepic'));

    return osc_get_preference($pref, 'plugin_profilepic');
}

/* Check if current page belongs to Profile Pic. */
function profilepic_is($params) {
    if(array_key_exists('file', $params)) {
        return (strpos($params['file'], profilepic_plugin()) !== false);
    } else if(array_key_exists('route', $params)) {
        if(preg_match('/^profilepic.*$/', $params['route'])) return true;
    }

    return false;
}

/* Compare current route with route name. */
function profilepic_is_route($route) {
    return (Params::getParam('route') == $route);
}

/* Generate and save image from base64. */
function profilepic_generate($picture, $user) {
    $name = $user.'_'.uniqid().'.jpg';
    $full_name = PROFILEPIC_PATH.'uploads/'.$name; // Generate filename (zo_profilepic/uploads/user_rand.jpg).
    $picture_parts = explode(";base64,", $picture); // Remove ";base64," from picture string.
    $picture_type_aux = explode("image/", $picture_parts[0]); // Get picture extension.
    $picture_type = $picture_type_aux[1];
    $picture_base64 = base64_decode($picture_parts[1]); // Get picture data.
    $source = imagecreatefromstring($picture_base64);
    $picture_save = imagejpeg($source, $full_name, profilepic_pref('quality'));

    /* Save. */
    $picture_resized = imagescale($source, profilepic_pref('original_size'), profilepic_pref('original_size'));
    $picture_save = imagejpeg($picture_resized, $full_name, profilepic_pref('quality'));

    imagedestroy($source);
    imagedestroy($picture_resized);

    return $name;
}

/* Get URL of a profile picture for a specific user. */
function profilepic_user_url($user) {
    $pic = ProfilePicModel::newInstance()->findByPrimaryKey($user);

    if(!$pic) { // No profile picutre. Use default.
        $url = profilepic_url(null, true);
    } else { // Profile picture active. Use it.
        $url = profilepic_url($pic['s_name']);
    }

    return $url;
}

/* Get path of a profile picture for a specific user. */
function profilepic_user_path($user) {
    $pic = ProfilePicModel::newInstance()->findByPrimaryKey($user);

    if(!(count($pic))) { // No profile picutre. Use default.
        $path = profilepic_path(null, true);
    } else { // Profile picture active. Use it.
        $path = profilepic_path($user);
    }

    return $path;
}

/* URL helper... */
function profilepic_url($name, $default = false) {
    if(!is_null($name) && !$default) {
        return osc_base_url().'oc-content/plugins/'.profilepic_plugin().'/uploads/'.$name;
    } else {
        return osc_base_url().'oc-content/plugins/'.profilepic_plugin().'/uploads/'.profilepic_pref('default_picture');
    }
}

/* Path helper... */
function profilepic_path($name, $default = false) {
    if(!is_null($name) && !$default) {
        return PROFILEPIC_PATH.'uploads/'.$name;
    } else {
        return PROFILEPIC_PATH.'uploads/'.profilpic_pref('default_picture');
    }
}

/* Get profile picture URL from View. */
function profilepic_get_pic() {
    return View::newInstance()->_get('profilepic_url');
}
