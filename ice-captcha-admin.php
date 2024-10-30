<?php
if (isset($_POST['submit'])) {

    

    if (function_exists('current_user_can') && !current_user_can('manage_options'))
        die(__('You do not have permissions for managing this option', 'ice-captcha'));

    check_admin_referer('ice-captcha-options_update');

    $optionarray_update = array(
        'ice_captcha_scale' => (float)$_POST['ice_captcha_scale'],
        'ice_captcha_radius' => (int)$_POST['ice_captcha_radius'],
        'ice_captcha_login' => (isset($_POST['ice_captcha_login']) ) ? 'true' : 'false',
        'ice_captcha_reg' => (isset($_POST['ice_captcha_reg']) ) ? 'true' : 'false',
        'ice_captcha_comment' => (isset($_POST['ice_captcha_comment']) ) ? 'true' : 'false',
    );

    update_site_option('ice_captcha_v4', $optionarray_update);

    $ice_captcha_opt = get_site_option('ice_captcha_v4');


    if (function_exists('wp_cache_flush')) {
        wp_cache_flush();
    }
}
?>
<?php if ( !empty($_POST ) ) : ?>
<div id="message" class="updated"><p><strong><?php _e('Options saved.', 'ice-captcha') ?></strong></p></div>
<?php endif; ?>
<div class="wrap">

    <h2>Ice Captcha</h2>

    <?php $plugin_url = plugin_dir_url(__FILE__); ?>

    <p>Ice Captha plugin | Version 2.0 | Author <a href="http://icecaptcha.com">Ice Captcha</a></p>

    <h3><?php _e('Options', 'ice-captcha') ?></h3>

    <form name="formoptions" method="POST" action="options-general.php?page=icecaptcha/ice-captcha.php">
        <input type="hidden" name="action" value="update" />
        <input type="hidden" name="form_type" value="upload_options" />
        <?php wp_nonce_field('ice-captcha-options_update'); ?>

        <fieldset class="options">

            <table width="100%" cellspacing="2" cellpadding="5" class="form-table">
                <tr>
                    <th scope="row" style="width: 125px;"><?php _e('Enabled for:', 'ice-captcha') ?></th>
                    <td>
                        <input name="ice_captcha_reg" id="ice_captcha_reg" type="checkbox" <?php if ($ice_captcha_opt['ice_captcha_reg'] == 'true')
    echo ' checked="checked" '; ?> />
                        <label for="ice_captcha_reg"><?php _e('Registration form', 'ice-captcha') ?></label>
                    </td>
                </tr>
                <tr>
                    <th scope="row" style="width: 125px;"></th>
                    <td>
                        <input name="ice_captcha_comment" id="ice_captcha_comment" type="checkbox" <?php if ($ice_captcha_opt['ice_captcha_comment'] == 'true')
    echo ' checked="checked" '; ?> />
                        <label for="ice_captcha_comment"><?php _e('Comment form', 'ice-captcha') ?></label>
                    </td>
                </tr>
                <tr>
                    <th scope="row" style="width: 125px;"></th>
                    <td>
                        <input name="ice_captcha_login" id="ice_captcha_login" type="checkbox" <?php if ($ice_captcha_opt['ice_captcha_login'] == 'true')
    echo ' checked="checked" '; ?> />
                        <label for="ice_captcha_login"><?php _e('Login form', 'ice-captcha') ?></label>
                    </td>
                </tr>
                <tr>
                    <th scope="row" style="width: 125px;"><?php _e('Circle raduis', 'ice-captcha') ?></th>
                    <td>
                        <input name="ice_captcha_radius" style="width:40px" id="ice_captcha_radius" type="text" value="<?php echo $ice_captcha_opt['ice_captcha_radius'] ?>" />
                    </td>
                </tr>
                <tr>
                    <th scope="row" style="width: 125px;"><?php _e('Scale', 'ice-captcha') ?></th>
                    <td>
                        <input name="ice_captcha_scale" style="width:40px" id="ice_captcha_scale" type="text" value="<?php echo $ice_captcha_opt['ice_captcha_scale'] ?>" />
                    </td>
                </tr>

            </table>
        </fieldset>
        <p class="submit">
            <input type="submit" name="submit" value="<?php _e('Update Options', 'ice-captcha') ?> &raquo;" />
        </p>

        </form>
</div>


