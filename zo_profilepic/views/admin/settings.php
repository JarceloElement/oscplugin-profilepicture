<?php
/* Developed by WEBmods
 * Zagorski oglasnik j.d.o.o. za usluge | www.zagorski-oglasnik.com
 *
 * License: GPL-3.0-or-later
 * More info in license.txt
*/

if(!defined('ABS_PATH')) exit('ABS_PATH is not loaded. Direct access is not allowed.');

$pref = array();
$pref['original_size'] = profilepic_pref('original_size');
$pref['quality'] = profilepic_pref('quality');
$pref['default_picture'] = profilepic_pref('default_picture');
?>

<h1><?php _e('Settings', profilepic_plugin()); ?></h1>
<form id="profilepic_form" class="row nocsrf" method="POST" action="<?php echo osc_route_admin_url('profilepic-settings'); ?>">
    <input type="hidden" name="save" value="1">
    <div class="col-md-6 form-group">
        <label><?php _e('Size', profilepic_plugin()); ?></label>
        <input type="number" name="original_size" class="form-control" id="original_size" aria-describedby="original_size_help" value="<?php echo $pref['original_size']; ?>">
        <small id="original_size_help" class="form-text text-muted"><?php _e('Size of the profile picture in pixels (image ratio is 1:1).', profilepic_plugin()); ?></small>
    </div>
    <div class="col-md-6 form-group">
        <label><?php _e('Quality', profilepic_plugin()); ?></label>
        <input type="number" name="quality" class="form-control" id="quality" aria-describedby="quality_help" value="<?php echo $pref['quality']; ?>">
        <small id="quality_help" class="form-text text-muted"><?php _e('Image quality from 0 to 100. Bigger quality = bigger file size.', profilepic_plugin()); ?></small>
    </div>
    <div class="col-md-12 form-group">
        <label><?php _e('Default profile picture', profilepic_plugin()); ?></label>
        <div class="pp_upload">
            <figure class="figure" style="display: block;">
                <figcaption class="figure-caption"><label><?php _e('Current picture', profilepic_plugin()); ?></label></figcaption>
                <img src="<?php echo profilepic_url(null, true); ?>" class="figure-img img-fluid rounded" style="max-width: 120px;">
            </figure>
            <input type="file" name="image" class="pp_input" id="pp_input_"/>
            <label for="pp_input_">
                <svg xmlns="http://www.w3.org/2000/svg" width="20" height="17" viewBox="0 0 20 17">
                    <path d="M10 0l-5.2 4.9h3.3v5.1h3.8v-5.1h3.3l-5.2-4.9zm9.3 11.5l-3.2-2.1h-2l3.4 2.6h-3.5c-.1 0-.2.1-.2.1l-.8 2.3h-6l-.8-2.2c-.1-.1-.1-.2-.2-.2h-3.6l3.4-2.6h-2l-3.2 2.1c-.4.3-.7 1-.6 1.5l.6 3.1c.1.5.7.9 1.2.9h16.3c.6 0 1.1-.4 1.3-.9l.6-3.1c.1-.5-.2-1.2-.7-1.5z"/>
                </svg>
                <span><?php _e('Choose a profile picture', profilepic_plugin()); ?>&hellip;</span>
            </label>
            <input type="hidden" class="pp_data" name="default_picture">
        </div>
    </div>
    <div class="col-md-12">
        <div class="float-right">
            <button class="btn btn-primary" type="submit"><?php _e('Save', profilepic_plugin()); ?></button>
        </div>
    </div>
</form>
