<?php
/**
 * Actions of module "Pedido".
 *
 * MagnusBilling <info@magnusbilling.com>
 * 05/06/2013
 */

class SiteController extends Controller
{

    public function actionIndex()
    {
        if (isset($_GET['paypal'])) {
            exit(isset($this->config['global']['paypal-softphone']) ? $this->config['global']['paypal-softphone'] : 0);
        }

        if (isset($_GET['callback'])) {
            exit(isset($this->config['global']['callback-softphone']) ? $this->config['global']['callback-softphone'] : 0);
        }

        $base_language = $this->config['global']['base_language'];
        echo 'window.lang = ' . json_encode($base_language) . ';';
        Yii::app()->session['language'] = $base_language;
        Yii::app()->setLanguage(Yii::app()->session['language']);

        $template = $this->config['global']['template'];
        echo 'window.theme = ' . json_encode($template) . ';';
        Yii::app()->session['theme'] = $template;

        $layout = $this->config['global']['layout'];
        echo 'window.layout = ' . json_encode($layout) . ';';
        Yii::app()->session['layout'] = $layout;

        $wallpaper = $this->config['global']['wallpaper'];
        echo 'window.wallpaper = ' . json_encode($wallpaper) . ';';
        Yii::app()->session['wallpaper'] = $wallpaper;
        echo 'window.colorMenu = ' . json_encode($this->config['global']['color_menu']) . ';';
        echo 'window.moduleExtra = ' . json_encode($this->config['global']['module_extra']) . ';';
        echo 'window.moduleExtra2 = ' . json_encode($this->config['global']['module_extra2']) . ';';
        echo 'window.moduleExtra3 = ' . json_encode($this->config['global']['module_extra3']) . ';';
        $reCaptchaKey = isset($this->config['global']['reCaptchaKey']) &&
        strlen($this->config['global']['reCaptchaSecret']) > 10 &&
        strlen($this->config['global']['reCaptchaKey']) > 10
        ? $this->config['global']['reCaptchaKey']
        : "";
        echo 'window.reCaptchaKey = ' . json_encode($reCaptchaKey) . ';';
        $upload_max_size = ini_get('upload_max_filesize');
        echo 'window.uploadFaxFilesize = "' . $upload_max_size . '";';
        echo 'window.uploadFaxFilesizebites = "' . intval($upload_max_size) . '";';
        echo 'window.show_signup_button = ' . $this->config['global']['show_signup_button'] . ';';
        echo 'window.auto_generate_user_signup = ' . $this->config['global']['auto_generate_user_signup'] . ';';
        echo 'window.enable_signup = ' . $this->config['global']['enable_signup'] . ';';
        if (isset($this->config['global']['login_header']) && strlen($this->config['global']['login_header']) > 5) {
            echo 'window.loginheader = "' . $this->config['global']['login_header'] . '";';
        }
        if ($this->config['global']['signup_auto_pass'] > 5) {
            $pass = '"' . Util::generatePassword($this->config['global']['signup_auto_pass'], true, true, true, false) . '"';
        } else {
            $pass = 0;
        }
        echo 'window.signup_auto_pass = ' . $pass . ';';
        echo 'window.backgroundColor = "' . $this->config['global']['backgroundColor'] . '";';
        echo 'window.default_codes = "' . $this->config['global']['default_codeds'] . '";';
    }
}
