<?php
/* Developed by WEBmods
 * Zagorski oglasnik j.d.o.o. za usluge | www.zagorski-oglasnik.com
 *
 * License: GPL-3.0-or-later
 * More info in license.txt
*/

if(!defined('ABS_PATH')) exit('ABS_PATH is not loaded. Direct access is not allowed.');

class ProfilePic {
    public $model;

    function __construct() {
        osc_add_hook('init_admin', array(&$this,'includes'));
        osc_add_hook('admin_footer', array(&$this,'includes_footer'));
        osc_add_hook('init', array(&$this,'includes'));
        osc_add_hook('footer', array(&$this,'includes_footer'));

        osc_add_hook('pre_user_post', array(&$this,'userPrePost'));
        osc_add_hook('user_register_completed', array(&$this,'userPost'));
        osc_add_hook('user_edit_completed', array(&$this,'userPost'));
        osc_add_hook('user_register_form', array(&$this,'userRegForm'));
        osc_add_hook('user_profile_form', array(&$this,'userDashForm'));
        osc_add_hook('delete_user', array(&$this,'userDelete'));

        $this->model = ProfilePicModel::newInstance();
    }

    function userPrePost() {
        // Save base64 image in session.
        $base64 = Params::getParam('profilepic_base64');
        Session::newInstance()->_setForm('profilepic_base64', $base64);
        Session::newInstance()->_keepForm('profilepic_base64');
    }

    function userPost($user) {
        // Save image and add record in DB.
        if(empty(Session::newInstance()->_getForm('profilepic_base64'))) {
            return false;
        }

        $data = array('fk_i_user_id' => $user, 'dt_date' => date('Y-m-d H:i:s'));
        $data['s_name'] = profilepic_generate(Session::newInstance()->_getForm('profilepic_base64'), $user);

        if($this->model->findByPrimaryKey($user) != false) { // If a profile pic record already exists, update it.
            return $this->model->updateByPrimaryKey($data, $user);
        } else { // Otherwise create a new record.
            return $this->model->insert($data);
        }
    }

    function userRegForm() {
        // Add a form to user register.
        View::newInstance()->_exportVariableToView('profilepic_url', profilepic_url(null, true));
        include(PROFILEPIC_PATH.'views/web/user-register.php');
    }

    function userDashForm($user) {
        // Add a form to user dashboard.
        $user = $user['pk_i_id'];

        $pic = $this->model->findByPrimaryKey($user);
        if(count($pic)) {
            View::newInstance()->_exportVariableToView('profilepic_url', profilepic_url($pic['s_name']));
        } else {
            View::newInstance()->_exportVariableToView('profilepic_url', profilepic_url(null, true));
        }

        include(PROFILEPIC_PATH.'views/web/user-dashboard.php');
    }

    function userDelete($user) {
        // Delete image and record if they exist.
        $pic = $this->model->findByPrimaryKey($user);
        if(count($pic)) {
            @unlink(profilepic_path($pic, false));
            $this->model->deleteByPrimaryKey($user);
        }
    }

    function includes() {
        // Add frontend and backend JS and CSS.
        if(OC_ADMIN || (Params::getParam('action') == 'register' || Params::getParam('action') == 'profile')) {
            osc_enqueue_style('profilepic-main', osc_plugin_url('zo_profilepic/assets/web/main.css').'main.css');
            osc_enqueue_style('profilepic-modal', osc_plugin_url('zo_profilepic/assets/web/modal.css').'modal.css');
            osc_enqueue_style('cropper', osc_plugin_url('zo_profilepic/assets/web/cropper.min.css').'cropper.min.css');
            osc_register_script('profilepic-main', osc_plugin_url('zo_profilepic/assets/web/main.js').'main.js');
            osc_register_script('profilepic-modal', osc_plugin_url('zo_profilepic/assets/web/modal.js').'modal.js');
            osc_register_script('cropper', osc_plugin_url('zo_profilepic/assets/web/cropper.min.js').'cropper.min.js');
            osc_enqueue_script('profilepic-main');
            osc_enqueue_script('profilepic-modal');
            osc_enqueue_script('cropper');
        }
    }

    function includes_footer() {
        if(OC_ADMIN || (Params::getParam('action') == 'register' || Params::getParam('action') == 'profile')) {
            // Add frontend and backend JS language vars.
            ?>
            <script>
            var $profilepicture_uploaded = '<?php _e('Picture uploaded. Click to upload a new one.', profilepic_plugin()); ?>';
            var $profilepicture_not_uploaded = '<?php _e('Choose a profile picture.', profilepic_plugin()); ?>';
            var $profilepicture_size = '<?php echo profilepic_pref('original_size'); ?>';
            </script>
            <?php
            include(PROFILEPIC_PATH.'views/web/modal.php');
        }
    }
}
$ProfilePic = new ProfilePic();

class ProfilePicAdmin {
    public function __construct() {
        osc_add_hook('init_admin', array(&$this, 'includes'));
        osc_add_hook('renderplugin_controller', array(&$this,'controller'));
        osc_add_hook('admin_menu_init', array(&$this, 'admin_menu'));
        osc_add_hook('admin_users_table',  array(&$this,'userTableCol'));
        osc_add_hook('users_processing_row',  array(&$this,'userTableRow'));

        $this->addRoutes();
    }

    function controller() {
        if(is_null(Params::getParam('route'))) return;

        if (profilepic_is(Params::getParamsAsArray())) {
            $controller = new ProfilePicController_Admin();
            $controller->doModel();
        }
    }

    function includes() {
        // Add backend JS and CSS.
        if(!profilepic_is(Params::getParamsAsArray())) return;

        osc_add_hook('admin_header', array(&$this, 'adminHeader'));
        osc_register_script('jquery', osc_plugin_url('zo_profilepic/assets/admin/js/plugins/jquery/jquery.min.js') . 'jquery.min.js');
        osc_register_script('bootstrap.bundle', osc_plugin_url('zo_profilepic/assets/admin/js/plugins/bootstrap/js/bootstrap.bundle.min.js') . 'bootstrap.bundle.min.js', array('jquery'));
        osc_enqueue_script('bootstrap.bundle');

        osc_enqueue_style('adminlte.min', osc_plugin_url('zo_profilepic/assets/admin/css/adminlte.css') . 'adminlte.css');
        osc_enqueue_style('fontawesome', osc_plugin_url('zo_profilepic/assets/admin/css/fa.css') . 'fa.css');
        osc_enqueue_style('profilepic-admin', osc_plugin_url('zo_profilepic/assets/admin/main.css') . 'main.css');
    }

    function addRoutes() {
        // Add backend routes.
        osc_add_route('profilepic-settings', 'profilepic/settings/', 'profilepic/settings/', PROFILEPIC_FOLDER.'views/admin/settings.php');
        osc_add_route('profilepic-help', 'profilepic/help/', 'profilepic/help/', PROFILEPIC_FOLDER.'views/admin/help.php');
    }

    function adminHeader() {
        // Add custom admin header.
        osc_remove_hook('admin_page_header', 'customPageHeader');
        osc_add_hook('admin_page_header', array(&$this,'pageHeader'), 9);
    }

    function admin_menu() {
        // Add admin submenu (under Plugins menu).
        osc_add_admin_submenu_divider('plugins', __('Profile Picture', profilepic_plugin()), 'zo_profilepic_divider', 'moderator');
        osc_add_admin_submenu_page('plugins', __('Settings', profilepic_plugin()), osc_route_admin_url('profilepic-settings'), 'profilepic_settings', 'administrator');
        osc_add_admin_submenu_page('plugins', __('Help', profilepic_plugin()), osc_route_admin_url('profilepic-help'), 'profilepic_help', 'moderator');
    }

    function pageHeader() {
        // Add custom admin header - include.
        include(PROFILEPIC_PATH.'views/admin/header.php');
    }

    function userTableCol($table) {
        // Add profile pic column to users table in admin.
        $table->addColumn('plugin_profilepic', '<span>'.__('Profile pic', profilepic_plugin()).'</span>');
    }

    function userTableRow($row, $aRow) {
        // Add profile pic row to users table in admin.
        $user = $aRow['pk_i_id'];
        $pic = profilepic_user_url($user);
        $html = '<img src="'.$pic.'" style="height: 64px;" />';
        $row['plugin_profilepic'] = $html;

        return $row;
    }

    public function loadJSON($url) {
        if (ini_get('allow_url_fopen') == true) {
            return $this->loadJSONfgc($url);
        } else if (function_exists('curl_init')) {
            return $this->loadJSONcurl($url);
        } else {
            return $this->loadJSONlocal();
        }
    }

    private function loadJSONfgc($url) {
        return file_get_contents($url);
    }

    private function loadJSONcurl($url) {
        $curl = curl_init($url);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
        $result = curl_exec($curl);
        curl_close($curl);
        return $result;
    }

    private function loadJSONlocal() {
        ob_start();
        include PROFILEPIC_PATH.'assets/admin/plugins.json';
        $result = ob_get_clean();
        return $result;
    }
}
$ProfilePicAdmin = new ProfilePicAdmin();
