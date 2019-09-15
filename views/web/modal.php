<?php
/* Developed by WEBmods
 * Zagorski oglasnik j.d.o.o. za usluge | www.zagorski-oglasnik.com
 *
 * License: GPL-3.0-or-later
 * More info in license.txt
*/
?>
<div class="pp_upload" style="display: none;">
    <div class="pp_modal" tabindex="-1" aria-hidden="true">
        <div class="pp_modal_header">
            <h2>
                <?php _e('Edit the profile picture', profilepic_plugin()); ?>
                <a href="javascript:Custombox.modal.closeAll();" class="pp_modal_close"><span aria-label="<?php _e('Close', profilepic_plugin()); ?>">&times;</span></a>
            </h2>
        </div>
        <div class="pp_modal_body">
            <img class="pp_modal_image" src="<?php echo profilepic_url(null, true); ?>">
        </div>
        <div class="pp_modal_footer">
            <div class="pp_button_group pp_modal_image_edit">
                <a class="pp_button" data-method="zoom" data-option="0.1" title="<?php _e('Zoom in', profilepic_plugin()); ?>">
                    <i class="fa fa-search-plus"></i>
                </a>
                <a class="pp_button" data-method="zoom" data-option="-0.1" title="<?php _e('Zoom out', profilepic_plugin()); ?>">
                    <i class="fa fa-search-minus"></i>
                </a>
                <a class="pp_button" data-method="rotate" data-option="-45" title="<?php _e('Rotate left (-45deg)', profilepic_plugin()); ?>">
                    <i class="fa fa-rotate-left"></i>
                </a>
                <a class="pp_button" data-method="rotate" data-option="45" title="<?php _e('Rotate right (45deg)', profilepic_plugin()); ?>">
                    <i class="fa fa-rotate-right"></i>
                </a>
            </div>
            <div class="pp_button_group">
                <a class="pp_button" data-dismiss="modal"><?php _e('Cancel', profilepic_plugin()); ?></a>
                <a class="pp_button pp_crop"><?php _e('Crop', profilepic_plugin()); ?></a>
            </div>
        </div>
    </div>
</div>
