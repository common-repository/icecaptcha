<?php

/*
  Plugin Name: Ice Captcha
  Plugin URI: http://icecaptcha.com
  Description: Adds Ice Captcha anti-spam methods to WordPress forms for comments and registration. This prevents spam from automated bots.
  Version: 2.1
  Author: Ice Captcha
  Author URI: http://icecaptcha.com/
 */

$ice_captcha_version = '1.0';
$plugin_dir_path = dirname(__FILE__);
include_once($plugin_dir_path.'/api/icecaptchalib.php');

class IceCaptcha {

    function ice_captcha_get_options()
    {
        global $ice_captcha_opt;

        $ice_captcha_option_defaults = array(
            'ice_captcha_scale' => 0.92,
            'ice_captcha_radius' => 10,
            'ice_captcha_login' => 'true',
            'ice_captcha_reg' => 'true',
            'ice_captcha_comment' => 'true',
        );

        if (!get_site_option('ice_captcha_v4')) {

            add_site_option('ice_captcha_v4', $ice_captcha_option_defaults, '', 'yes');
        }

        $ice_captcha_opt = get_site_option('ice_captcha_v4');
    }

    function ice_captcha_add_tabs()
    {
        add_submenu_page('options-general.php', __('Ice Captcha Options', 'ice-captcha'), __('Ice Captcha', 'ice-captcha'), 'manage_options', __FILE__, array(&$this, 'ice_captcha_options_page'));
    }

    function ice_captcha_plugin_action_links($links, $file)
    {
        static $this_plugin;
        if (!$this_plugin)
            $this_plugin = plugin_basename(__FILE__);

        if ($file == $this_plugin) {
            $settings_link = '<a href="options-general.php?page=ice-captcha/ice-captcha.php">' . __('Settings', 'ice-captcha') . '</a>';
            array_unshift($links, $settings_link);
        }
        return $links;
    }

    function ice_captcha_init()
    {
        if (function_exists('load_plugin_textdomain')) {
            load_plugin_textdomain('ice-captcha', false, dirname(plugin_basename(__FILE__)) . '/languages');
        }
    }

    function ice_captcha_options_page()
    {
        global $ice_captcha_opt;
        require_once(dirname(__FILE__) . '/ice-captcha-admin.php');
    }

    function ice_captcha_login_form()
    {
        global $ice_captcha_opt;
        $cpt = new pntcaptcha();
        $cpt->server = "http://icecaptcha.com/icecap/";
        $cpt->fname = 'loginform';
        $cpt->scale = $ice_captcha_opt['ice_captcha_scale'];
        $cpt->rad = $ice_captcha_opt['ice_captcha_radius'];
        echo $cpt->jscode();
        echo '<p><label>' . __('Submit form by finding circle', 'ice-captcha') . '</label><br />' . $cpt->htmlcode() . '</p>';
        echo "<script>$(document).ready(function($) { $('#pntcaptcha').click(function(e){ var px = Math.round(e.pageX - $(this).offset().left); var py = Math.round(e.pageY - $(this).offset().top); document.getElementById('pntcaptchacodex').value = encode(px + ''); document.getElementById('pntcaptchacodey').value = encode(py + '');}); $('form#loginform p.submit').css('display', 'none'); $('form#registerform').css('padding-bottom', '10px')});</script>";
    }

    function ice_captcha_register_form()
    {
        global $ice_captcha_opt;
        $cpt = new pntcaptcha();
        $cpt->server = "http://icecaptcha.com/icecap/";
        $cpt->fname = 'registerform';
        $cpt->scale = $ice_captcha_opt['ice_captcha_scale'];
        $cpt->rad = $ice_captcha_opt['ice_captcha_radius'];
        echo $cpt->jscode();
        echo '<p><label>' . __('Submit form by finding circle', 'ice-captcha') . '</label><br />' . $cpt->htmlcode() . '</p>';
        echo "<script type='text/javascript' src='http://code.jquery.com/jquery.min.js'></script><script>$(document).ready(function($) { $('#pntcaptcha').click(function(e){ var px = Math.round(e.pageX - $(this).offset().left); var py = Math.round(e.pageY - $(this).offset().top); document.getElementById('pntcaptchacodex').value = encode(px + ''); document.getElementById('pntcaptchacodey').value = encode(py + '');}); $('form#registerform p.submit').css('display', 'none'); $('form#registerform').css('padding-bottom', '10px')});</script>";
    }

    function ice_captcha_comment_form()
    {
        if (function_exists('WPWall_Widget') && isset($_POST['wpwall_comment'])) {
            return;
        }
        if (is_user_logged_in ()) {
            return;
        }
        // skip captcha for comment replies from admin menu
        if (isset($_POST['action']) && $_POST['action'] == 'replyto-comment' &&
                ( check_ajax_referer('replyto-comment', '_ajax_nonce', false) || check_ajax_referer('replyto-comment', '_ajax_nonce-replyto-comment', false))) {
            // skip capthca
            return;
        }
        global $ice_captcha_opt;
        $cpt = new pntcaptcha();
        $cpt->server = "http://icecaptcha.com/icecap/";
        $cpt->fname = 'commentform';
        $cpt->scale = $ice_captcha_opt['ice_captcha_scale'];
        $cpt->rad = $ice_captcha_opt['ice_captcha_radius'];
        echo $cpt->jscode();
        echo '<p><label>' . __('Submit form by finding circle', 'ice-captcha') . '</label><br />' . $cpt->htmlcode() . '</p>';
        echo "<script type='text/javascript' src='http://code.jquery.com/jquery.min.js'></script><script>$(document).ready(function($) { $('#pntcaptcha').click(function(e){ var px = Math.round(e.pageX - $(this).offset().left); var py = Math.round(e.pageY - $(this).offset().top); document.getElementById('pntcaptchacodex').value = encode(px + ''); document.getElementById('pntcaptchacodey').value = encode(py + '');}); $('form#commentform').attr('name', 'commentform'); $('form#commentform p.form-submit').css('display', 'none'); });</script>";
    }

    function ice_captcha_register_post($errors)
    {
        if (empty($_POST['pntcaptchacodex']) || $_POST['pntcaptchacodex'] == '') {
            $errors->add('captcha_blank', '<strong>' . __('ERROR', 'ice-captcha') . '</strong>: ' . __('Please select circle on the image.', 'ice-captcha'));
            return $errors;
        } else {
            $captcha_code = trim(strip_tags($_POST['captcha_code']));
        }

        $cpt = new pntcaptcha();
        $cpt->server = "http://icecaptcha.com/icecap/";
        $cpt->pointx = $_POST['pntcaptchacodex'];
        $cpt->pointy = $_POST['pntcaptchacodey'];
        $cpt->sukey = $_POST['pntcaptchacodekey'];
        $cpt->check();

        if ($cpt->is_valid) {
            // ok can continue
        } else {
            $errors->add('captcha_wrong', '<strong>' . __('ERROR', 'ice-captcha') . '</strong>: ' . __('You didn\'t find circle! Please, try again.', 'ice-captcha'));
        }
        return $errors;
    }

    function ice_captcha_comment_post($comment)
    {
        // added for compatibility with WP Wall plugin
        if (function_exists('WPWall_Widget') && isset($_POST['wpwall_comment'])) {
            return $comment;
        }

        if (is_user_logged_in ()) {
            return $comment;
        }
        // skip captcha for comment replies from admin menu
        if (isset($_POST['action']) && $_POST['action'] == 'replyto-comment' &&
                ( check_ajax_referer('replyto-comment', '_ajax_nonce', false) || check_ajax_referer('replyto-comment', '_ajax_nonce-replyto-comment', false))) {
            // skip capthca
            return $comment;
        }
        // Skip captcha for trackback or pingback
        if ($comment['comment_type'] != '' && $comment['comment_type'] != 'comment') {
            // skip capthca
            return $comment;
        }
        if (empty($_POST['pntcaptchacodex']) || $_POST['pntcaptchacodex'] == '') {
            wp_die(__('Please select circle on the image.', 'ice-captcha'));
        }
        $cpt = new pntcaptcha();
        $cpt->server = "http://icecaptcha.com/icecap/";



        $cpt->pointx = $_POST['pntcaptchacodex'];
        $cpt->pointy = $_POST['pntcaptchacodey'];
        $cpt->sukey = $_POST['pntcaptchacodekey'];

        $cpt->check();
        if ($cpt->is_valid) {
            // ok can continue
            return($comment);
        } else {
            wp_die(__('You didn\'t find circle! Please, try again.', 'ice-captcha'));
        }
    }

    function si_wp_authenticate_username_password($user, $username, $password)
    {
        global $si_captcha_dir, $si_captcha_dir_ns, $si_captcha_opt, $wp_version;

        if (is_a($user, 'WP_User')) {
            return $user;
        }

        if (empty($username) || empty($password) || isset($_POST['captcha_code']) && empty($_POST['captcha_code'])) {
            $error = new WP_Error();

            if (empty($username))
                $error->add('empty_username', __('<strong>ERROR</strong>: The username field is empty.'));

            if (empty($password))
                $error->add('empty_password', __('<strong>ERROR</strong>: The password field is empty.'));

            if (empty($_POST['pntcaptchacodex']) || $_POST['pntcaptchacodex'] == '') {
                remove_filter('authenticate', 'wp_authenticate_username_password', 20, 3);
                //$errors->add('captcha_blank', '<strong>' . __('ERROR', 'ice-captcha') . '</strong>: ' . __('Please select circle on the image.', 'ice-captcha'));
            }
            return $error;
        }

        //captcha with PHP sessions

        $cpt = new pntcaptcha();
        $cpt->server = "http://icecaptcha.com/icecap/";
        $cpt->pointx = $_POST['pntcaptchacodex'];
        $cpt->pointy = $_POST['pntcaptchacodey'];
        $cpt->sukey = $_POST['pntcaptchacodekey'];
        $cpt->check();

        if ($cpt->is_valid) {
            // ok can continue
        } else {
            remove_filter('authenticate', 'wp_authenticate_username_password', 20, 3);
            return new WP_Error('captcha_wrong', '<strong>' . __('ERROR', 'ice-captcha') . '</strong>: ' . __('You didn\'t find circle! Please, try again.', 'ice-captcha'));
        }

        // end if captcha use session
        // end si captcha check

        $userdata = get_user_by('login', $username);

        if (!$userdata) {
            return new WP_Error('invalid_username', sprintf(__('<strong>ERROR</strong>: Invalid username. <a href="%s" title="Password Lost and Found">Lost your password</a>?'), site_url('wp-login.php?action=lostpassword', 'login')));
        }

        // for WP 3.0+ ONLY!
        if (version_compare($wp_version, '3', '>=')) { // wp 3.0 +
            if (is_multisite ()) {
                // Is user marked as spam?
                if (1 == $userdata->spam)
                    return new WP_Error('invalid_username', __('<strong>ERROR</strong>: Your account has been marked as a spammer.'));

                // Is a user's blog marked as spam?
                if (!is_super_admin($userdata->ID) && isset($userdata->primary_blog)) {
                    $details = get_blog_details($userdata->primary_blog);
                    if (is_object($details) && $details->spam == 1)
                        return new WP_Error('blog_suspended', __('Site Suspended.'));
                }
            }
        }
        $userdata = apply_filters('wp_authenticate_user', $userdata, $password);
        if (is_wp_error($userdata)) {
            return $userdata;
        }

        if (!wp_check_password($password, $userdata->user_pass, $userdata->ID)) {
            return new WP_Error('incorrect_password', sprintf(__('<strong>ERROR</strong>: Incorrect password. <a href="%s" title="Password Lost and Found">Lost your password</a>?'), site_url('wp-login.php?action=lostpassword', 'login')));
        }

        $user = new WP_User($userdata->ID);
        return $user;
    }

}

if (class_exists("IceCaptcha")) {
    $ice_captcha = new IceCaptcha();
}



$ice_captcha->ice_captcha_get_options();

function ice_jquery_load()
{
    wp_register_script('$', 'http://ajax.googleapis.com/ajax/libs/jquery/1.7/jquery.min.js');
    wp_enqueue_script('$');
}

add_action( 'login_enqueue_scripts', 'ice_jquery_load', 1 );
ice_jquery_load();

if ($ice_captcha_opt['ice_captcha_login'] == 'true') {
    add_action('login_form', array(&$ice_captcha, 'ice_captcha_login_form'));
    add_filter('authenticate', array(&$ice_captcha, 'si_wp_authenticate_username_password'), 9, 3);
}

if ($ice_captcha_opt['ice_captcha_reg'] == 'true') {
    add_action('register_form', array(&$ice_captcha, 'ice_captcha_register_form'));
    add_filter('registration_errors', array(&$ice_captcha, 'ice_captcha_register_post'), 10);
}

if ($ice_captcha_opt['ice_captcha_comment'] == 'true') {
    add_action('comment_form', array(&$ice_captcha, 'ice_captcha_comment_form'), 1);
    add_filter('preprocess_comment', array(&$ice_captcha, 'ice_captcha_comment_post'), 1);
}

add_filter('plugin_action_links', array(&$ice_captcha, 'ice_captcha_plugin_action_links'), 10, 2);
add_action('admin_menu', array(&$ice_captcha, 'ice_captcha_add_tabs'), 1);
add_action('init', array(&$ice_captcha, 'ice_captcha_init'));



?>
