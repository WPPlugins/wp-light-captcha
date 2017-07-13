<?php
/**
 * Plugin Name: WP Light Captcha
 * Plugin URI: http://learn24bd.com/portfolio/wp-light-captcha
 * Description: WP Light Captcha is very simple and lightweight captcha plugins for your WordPress site.You can protect unwanted login request from hackers.
 * Version: 1.0.1
 * Author: Harun
 * Author URI: http://learn24bd.com
 * License:GPL2
 */
require __DIR__ . "/lib/mc.class.php";
add_action('login_form', 'wplc_login_form');

function wplc_login_form()
{
    Mc::putMcData();
    ?>
    <style>
    #wplc_user_answer{
    width: 100%;
    height: 28px!important;
    position: relative;
    top: 7px;
    font-size: 15px;
    border-radius: 4px;
    font-weight: 800;
    color: #666;
    }
    </style>
    <table>
        <tr>
            <td width="45%"><strong>Captcha:</strong> <?=Mc::getMcQuestion();?></td>
            <td>
                <input type="hidden" name="wplc_a" value="<?=md5(Mc::getMcAnswer())?>">
                <input type="text" id="wplc_user_answer" name="wplc_user_answer" placeholder="Put Answer" required="required">
            </td>
        </tr>
    </table>

    <?php
}

add_action('wp_authenticate_user', 'wplc_authenticate', 10, 2);
function wplc_authenticate($user, $password)
{
    if (isset($_POST['wplc_user_answer']) && isset($_POST['wplc_a'])) {
        $userAnswer   = md5($_POST['wplc_user_answer']);
        $actualAnswer = $_POST['wplc_a'];
        if ($userAnswer == $actualAnswer) {
            return $user;
        } else {
            $error = new WP_Error('denied', __('<strong>Incorrect!<strong>: Your captcha is incorrect'));
            return $error;
        }
    } else {
        $error = new WP_Error('denied', __('<strong>Incorrect!<strong>: Your captcha is incorrect'));
        return $error;
    }

}
