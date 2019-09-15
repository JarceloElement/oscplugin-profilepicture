<?php
/* Developed by WEBmods
 * Zagorski oglasnik j.d.o.o. za usluge | www.zagorski-oglasnik.com
 *
 * License: GPL-3.0-or-later
 * More info in license.txt
*/
?>
<div class="pp_upload">
    <div class="modal pp_modal" tabindex="-1" role="dialog" aria-labelledby="pp_modal_label" aria-hidden="true">
      <div class="modal-dialog" role="document">
        <div class="modal-content">
          <div class="modal-header">
            <h5 class="modal-title" id="pp_modal_label"><?php _e('Edit the profile picture', profilepic_plugin()); ?></h5>
            <button type="button" class="close" data-dismiss="modal" aria-label="<?php _e('Close', profilepic_plugin()); ?>">
              <span aria-hidden="true">&times;</span>
            </button>
          </div>
          <div class="modal-body">
            <div class="img-container">
              <img class="pp_modal_image" src="<?php echo profilepic_url(null, true); ?>">
            </div>
          </div>
          <div class="modal-footer">
            <div class="pp_modal_image_edit">
                  <a class="pp_button" data-method="zoom" data-option="0.1" title="<?php _e('Zoom in', profilepic_plugin()); ?>">
                    <span class="docs-tooltip" data-toggle="tooltip" title="" data-original-title="cropper.zoom(0.1)">
                    <span class="fa fa-search-plus"></span>
                    </span>
                </a>
                  <a class="pp_button" data-method="zoom" data-option="-0.1" title="<?php _e('Zoom out', profilepic_plugin()); ?>">
                    <span class="docs-tooltip" data-toggle="tooltip" title="" data-original-title="cropper.zoom(-0.1)">
                    <span class="fa fa-search-minus"></span>
                    </span>
                </a>
                    <a class="pp_button" data-method="rotate" data-option="-45" title="<?php _e('Rotate left (-45deg)', profilepic_plugin()); ?>">
                      <span class="docs-tooltip" data-toggle="tooltip" title="" data-original-title="cropper.rotate(-45)">
                        <span class="fa fa-rotate-left"></span>
                      </span>
                  </a>
                    <a class="pp_button" data-method="rotate" data-option="45" title="<?php _e('Rotate right (45deg)', profilepic_plugin()); ?>">
                      <span class="docs-tooltip" data-toggle="tooltip" title="" data-original-title="cropper.rotate(45)">
                        <span class="fa fa-rotate-right"></span>
                      </span>
                  </a>
            </div>
            <a class="pp_button" data-dismiss="modal"><?php _e('Cancel', profilepic_plugin()); ?></a>
            <a class="pp_button pp_crop"><?php _e('Crop', profilepic_plugin()); ?></a>
          </div>
        </div>
      </div>
    </div>
</div>
