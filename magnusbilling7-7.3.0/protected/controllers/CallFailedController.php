<?php
/**
 * Acoes do modulo "Call".
 *
 * =======================================
 * ###################################
 * MagnusBilling
 *
 * @package MagnusBilling
 * @author Adilson Leffa Magnus.
 * @copyright Copyright (C) 2005 - 2018 MagnusSolution. All rights reserved.
 * ###################################
 *
 * This software is released under the terms of the GNU Lesser General Public License v2.1
 * A copy of which is available from http://www.gnu.org/copyleft/lesser.html
 *
 * Please submit bug reports, patches, etc to https://github.com/magnusbilling/mbilling/issues
 * =======================================
 * Magnusbilling.com <info@magnusbilling.com>
 * 17/08/2012
 */

class CallFailedController extends Controller
{
    public $attributeOrder = 't.id DESC';
    public $extraValues    = array(
        'idUser'   => 'username',
        'idPlan'   => 'name',
        'idTrunk'  => 'trunkcode',
        'idPrefix' => 'destination',
        'idServer' => 'name',
    );

    public $fieldsInvisibleClient = array(
        'username',
        'trunk',
        'id_user',
        'provider_name',
    );

    public $fieldsInvisibleAgent = array(
        'trunk',
        'id_user',
        'provider_name',
    );

    public $fieldsFkReport = array(
        'id_user'   => array(
            'table'       => 'pkg_user',
            'pk'          => 'id',
            'fieldReport' => "username ",
        ),
        'id_trunk'  => array(
            'table'       => 'pkg_trunk',
            'pk'          => 'id',
            'fieldReport' => 'trunkcode',
        ),
        'id_prefix' => array(
            'table'       => 'pkg_prefix',
            'pk'          => 'id',
            'fieldReport' => 'destination',
        ),
        'id'        => array(
            'table'       => 'pkg_prefix',
            'pk'          => 'id',
            'fieldReport' => 'destination',
        ),
        'id_server' => array(
            'table'       => 'pkg_servers',
            'pk'          => 'id',
            'fieldReport' => 'name',
        ),
    );

    public function init()
    {
        $this->instanceModel = new CallFailed;
        $this->abstractModel = CallFailed::model();
        $this->titleReport   = Yii::t('yii', 'Call Failed');

        parent::init();

        if (!Yii::app()->session['isAdmin']) {
            $this->extraValues = array(
                'idUser'   => 'username',
                'idPlan'   => 'name',
                'idPrefix' => 'destination',
            );
        }
    }

    /**
     * Cria/Atualiza um registro da model
     */
    public function actionSave()
    {
        $values = $this->getAttributesRequest();

        if (isset($values['id']) && !$values['id']) {
            echo json_encode(array(
                $this->nameSuccess => false,
                $this->nameRoot    => 'error',
                $this->nameMsg     => 'Operation no allow',
            ));
            exit;
        }
        parent::actionSave();
    }

    public function getAttributesRequest()
    {
        $arrPost = array_key_exists($this->nameRoot, $_POST) ? json_decode($_POST[$this->nameRoot], true) : $_POST;
        //retira capos antes de salvar
        unset($arrPost['starttime']);
        unset($arrPost['callerid']);
        unset($arrPost['id_prefix']);
        unset($arrPost['username']);
        unset($arrPost['trunk']);
        unset($arrPost['terminatecauseid']);
        unset($arrPost['calltype']);
        unset($arrPost['idPrefixdestination']);

        return $arrPost;
    }

    public function actionCallInfo()
    {
        ?>

<style type="text/css">

#border {
border: 5px solid #1C6EA4;
border-radius: 9px;
}

table.blueTable {
  border: 1px solid #1C6EA4;

  width: 100%;
  text-align: left;
  border-collapse: collapse;
}
table.blueTable td, table.blueTable th {
  border: 1px solid #AAAAAA;
  padding: 3px 2px;
}
table.blueTable tbody td {
  font-size: 13px;
}
table.blueTable tr:nth-child(even) {
  background: #D0E4F5;
}
table.blueTable thead {
  background: #1C6EA4;
  background: -moz-linear-gradient(top, #5592bb 0%, #327cad 66%, #1C6EA4 100%);
  background: -webkit-linear-gradient(top, #5592bb 0%, #327cad 66%, #1C6EA4 100%);
  background: linear-gradient(to bottom, #5592bb 0%, #327cad 66%, #1C6EA4 100%);
  border-bottom: 2px solid #444444;
}
table.blueTable thead th {
  font-size: 15px;
  font-weight: bold;
  color: #FFFFFF;
  border-left: 2px solid #D0E4F5;
}
table.blueTable thead th:first-child {
  border-left: none;
}

table.blueTable tfoot {
  font-size: 14px;
  font-weight: bold;
  color: #FFFFFF;
  background: #D0E4F5;
  background: -moz-linear-gradient(top, #dcebf7 0%, #d4e6f6 66%, #D0E4F5 100%);
  background: -webkit-linear-gradient(top, #dcebf7 0%, #d4e6f6 66%, #D0E4F5 100%);
  background: linear-gradient(to bottom, #dcebf7 0%, #d4e6f6 66%, #D0E4F5 100%);
  border-top: 2px solid #444444;
}
table.blueTable tfoot td {
  font-size: 14px;
}
table.blueTable tfoot .links {
  text-align: right;
}
table.blueTable tfoot .links a{
  display: inline-block;
  background: #1C6EA4;
  color: #FFFFFF;
  padding: 2px 8px;
  border-radius: 5px;
}

</style>


    <?php
$model = CallFailed::model()->findByPk((int) $_GET['id']);

        if (!isset($model->idServer->id) || $model->idServer->type == 'mbilling') {
            $pattern = "/" . $model->calledstation . "/i";

            $ora_books = [];

            $data = @file_get_contents('/var/log/asterisk/magnus');

            $fh = fopen('/var/log/asterisk/magnus', 'r') or die($php_errormsg);
            while (!feof($fh)) {
                $line   = fgets($fh, 4096);
                $result = htmlentities($line);
                if (preg_match($pattern, $result)) {
                    $ora_books[] = $result;
                }
            }

            fclose($fh);
            echo '<br>';
            echo '<table class="blueTable" width=100%><tr>';
            echo '<tr>';
            echo '<th  colspan=4>Below data is the last SIP sinalization from trunk to the number ' . $model->calledstation . '</th>';

            echo '</tr>';
            echo '<th>Date</th>';
            echo '<th>To tag</th>';
            echo '<th>Sip Code</th>';
            echo '<th>Reason</th>';
            foreach ($ora_books as $key => $value) {
                $line = explode('|', $value);
                $data = explode('] ', $line[0]);
                echo '<tr>';
                echo '<td>' . substr($data[0], 1) . '</td>';
                echo '<td>' . $line[1] . '</td>';
                echo '<td>' . $line[2] . '</td>';
                echo '<td>' . $line[3] . '</td>';
                echo '</tr>';

            }
            echo '</tr></table>';

        } else {

            if (filter_var($model->idServer->host, FILTER_VALIDATE_IP, FILTER_FLAG_NO_PRIV_RANGE | FILTER_FLAG_NO_RES_RANGE)) {
                $ip = $model->idServer->host;
            } else {
                $ip = $model->idServer->public_ip;
            }
            header('Location: http://' . $ip . '/mbilling?id=' . $model->id);

        }
    }
}
