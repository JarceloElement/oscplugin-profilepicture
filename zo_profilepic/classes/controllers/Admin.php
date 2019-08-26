<?php
/* Developed by WEBmods
 * Zagorski oglasnik j.d.o.o. za usluge | www.zagorski-oglasnik.com
 *
 * License: GPL-3.0-or-later
 * More info in license.txt
*/

if(!defined('ABS_PATH')) exit('ABS_PATH is not loaded. Direct access is not allowed.');

class ProfilePicController_Admin extends AdminSecBaseModel {
    public function __construct() {
        parent::__construct();
    }

    public function doModel() {
        parent::doModel();
        switch(Params::getParam('route')) {
            // Settings
            case 'profilepic-settings':
                if(Params::getParam('save')) {
                    osc_set_preference('original_size', Params::getParam('original_size'), 'plugin_profilepic');
                    osc_set_preference('quality', Params::getParam('quality'), 'plugin_profilepic');
                    $picture_base64 = Params::getParam('default_picture');
                    if(!empty($picture_base64)) {
                        $old = profilepic_pref('default_picture');
                        if($old != 'default.jpg') {
                            unlink(PROFILEPIC_PATH.'uploads/'.$old);
                        }
                        $name = profilepic_generate($picture_base64, 'default');
                        osc_set_preference('default_picture', $name, 'plugin_profilepic');
                    }

                    osc_add_flash_ok_message(__('Setting(s) saved successfully.', profilepic_plugin()), 'admin');
                    $this->redirectTo(osc_route_admin_url('profilepic-settings'));
                }
            break;
        }
    }

    function doView($file) {
        osc_run_hook("before_admin_html");
        osc_current_admin_theme_path($file);
        Session::newInstance()->_clearVariables();
        osc_run_hook("after_admin_html");
    }
}
?>
