<?php
/* Developed by WEBmods
 * Zagorski oglasnik j.d.o.o. za usluge | www.zagorski-oglasnik.com
 *
 * License: GPL-3.0-or-later
 * More info in license.txt
*/
?>
<div class="control-group">
    <label class="control-label"><?php _e('Current profile picture', profilepic_plugin()); ?></label>
    <div class="controls">
        <img src="<?php echo profilepic_get_pic(); ?>" style="max-width: 100px;">
    </div>
</div>
<div class="control-group pp_new" style="display: none;">
    <label class="control-label"><?php _e('Profile picture preview', profilepic_plugin()); ?></label>
    <div class="controls">
        <img src="<?php echo profilepic_get_pic(); ?>" style="max-width: 100px;">
    </div>
</div>
<div class="control-group pp_upload">
    <label class="control-label"><?php _e('Add a new profile picture', profilepic_plugin()); ?></label>
    <div class="controls">
        <input type="file" name="image" class="pp_input" id="pp_input_"/>
        <label for="pp_input_">
            <svg xmlns="http://www.w3.org/2000/svg" width="20" height="17" viewBox="0 0 20 17">
                <path d="M10 0l-5.2 4.9h3.3v5.1h3.8v-5.1h3.3l-5.2-4.9zm9.3 11.5l-3.2-2.1h-2l3.4 2.6h-3.5c-.1 0-.2.1-.2.1l-.8 2.3h-6l-.8-2.2c-.1-.1-.1-.2-.2-.2h-3.6l3.4-2.6h-2l-3.2 2.1c-.4.3-.7 1-.6 1.5l.6 3.1c.1.5.7.9 1.2.9h16.3c.6 0 1.1-.4 1.3-.9l.6-3.1c.1-.5-.2-1.2-.7-1.5z"/>
            </svg>
            <span><?php _e('Choose a profile picture', profilepic_plugin()); ?>&hellip;</span>
        </label>
        <div style="clear: both;"></div>
        <input type="hidden" class="pp_data" name="profilepic_base64">
    </div>
</div>
