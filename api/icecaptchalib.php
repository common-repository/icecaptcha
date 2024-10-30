<?php
# IceCaptcha 1.0 Client

class pntcaptcha {

    var $version = 100; // 1.00
    var $is_valid = false;
    var $pointx; // Click coordinate x
    var $pointy; // Click coordinate y
    var $fname; // Form name
    var $server; // PntCaptcha server URL
    var $rad = 10;
    var $scale = 1;
    var $sukey;

    function jscode()
    {
        $ses = time() . mt_rand(111111111, 999999999);
        $this->sukey = $ses;
        $serv = $_SERVER['SERVER_NAME'];
        $skey = md5($serv);

        // Print JS code
        // get click coordinate function
?>
        <script type="text/javascript">
            function encode(cor) {
                var keyres = 0;
                var res = 0;
                var key = "<?php echo $ses ?>";

                for (var i = 0; i < key.length; i++) {
                    var k = parseInt(key.charAt(i));
                    keyres += k;
                }

                res = keyres * cor;
                return res;
            }

        </script>
<?php
    }

    function htmlcode()
    {
        // Print captcha
        $ses = $this->sukey;
        $serv = $_SERVER['SERVER_NAME'];
        $skey = md5($serv);
        $s.= '<input type="hidden" name="pntcaptchacodex" id="pntcaptchacodex" value="" />';
        $s.= '<input type="hidden" name="pntcaptchacodey" id="pntcaptchacodey" value="" />';
        $s.= '<input type="hidden" name="pntcaptchacodekey" id="pntcaptchacodekey" value="' . $ses . '" />';
        $s.= '<div style="padding:0;border:0; margin:0;overlfow: hidden;clear: both;">';
        $s.= '<INPUT TYPE="image" style="margin:3px 0 10px; width:auto !important;" src="' . $this->server . 'pntimg.php?s=' . $ses . '&key=' . $skey . '&scale=' . $this->scale . '&r=' . $this->rad . '" id="pntcaptcha" /></div>';
        return $s;
    }

    function check()
    {
        // Check captcha
        $url = $this->server . "pntserver.php";

        $serv = $_SERVER['SERVER_NAME'];
        $skey = md5($serv);
        $postm = 'x=' . $this->pointx . '&y=' . $this->pointy . '&key=' . $skey . '&s=' . $this->sukey;

        $result = trim(file_get_contents($url . "?" . $postm));

        if ($result == $skey) {
            $this->is_valid = true;
        }
    }

}
?>