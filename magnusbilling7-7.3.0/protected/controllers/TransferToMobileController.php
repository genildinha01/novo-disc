<?php
/**
 * =======================================
 * ###################################
 * MagnusBilling
 *
 * @package MagnusBilling
 * @author Adilson Leffa Magnus.
 * @copyright Copyright (C) 2005 - 2016 MagnusBilling. All rights reserved.
 * ###################################
 *
 * This software is released under the terms of the GNU Lesser General Public License v2.1
 * A copy of which is available from http://www.gnu.org/copyleft/lesser.html
 *
 * Please submit bug reports, patches, etc to https://github.com/magnusbilling/mbilling/issues
 * =======================================
 * Magnusbilling.com <info@magnusbilling.com>
 *
 */

/*
add the cron to check the BDService transaction status
echo "
 * * * * * php /var/www/html/mbilling/cron.php bdservice
" >> /var/spool/cron/root
 */

class TransferToMobileController extends Controller
{

    public function actionRead($asJson = true, $condition = null)
    {

        $modelTransferToMobile = TransferToMobile::model()->findByPk((int) Yii::app()->session['id_user']);

        $methods = [];

        if ($modelTransferToMobile->transfer_international) {
            $methods["Mobile_Credit"] = "Mobile Credit";
        }
        if ($modelTransferToMobile->transfer_flexiload) {
            $methods["Mobile_Money"] = "Mobile Money";
        }
        if ($modelTransferToMobile->transfer_bkash) {
            $methods["Payment"] = "Payment";
        }

        //check the number and methods.
        if (isset($_POST['TransferToMobile']['method'])) {

            if ($_POST['TransferToMobile']['method'] == '') {
                $modelTransferToMobile->addError('method', Yii::t('yii', 'Please select a method'));

                $this->render('index', array(
                    'modelTransferToMobile' => $modelTransferToMobile,
                    'methods'               => $methods,
                ));
                return;

            }

            $this->redirect(
                array(
                    'Transfer' . preg_replace('/_/', '', $_POST['TransferToMobile']['method']) . '/index',

                ));

        }

        if (count($methods)) {
            $this->render('index', array(
                'modelTransferToMobile' => $modelTransferToMobile,
                'methods'               => $methods,
            ));
        } else {
            echo '<div align=center id="container">';
            echo '<font color=red>Not available any refill method for you</font>';
            echo '</div>';
            exit;
        }

    }
}
