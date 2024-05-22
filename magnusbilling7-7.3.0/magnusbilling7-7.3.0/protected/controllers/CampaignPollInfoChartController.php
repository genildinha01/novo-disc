<?php
/**
 * Acoes do modulo "CampaignPollInfo".
 *
 * =======================================
 * ###################################
 * MagnusBilling
 *
 * @package MagnusBilling
 * @author  Adilson Leffa Magnus.
 * @copyright   Todos os direitos reservados.
 * ###################################
 * =======================================
 * Magnusbilling.com <info@magnusbilling.com>
 * 28/10/2012
 */

class CampaignPollInfoChartController extends Controller
{
    public $attributeOrder = 't.id';

    public function actionRead($asJson = true, $condition = null)
    {
        $filter = isset($_GET['filter']) ? json_decode($_GET['filter']) : null;
        $filter = json_decode($filter[0]->value);

        $this->filter = $this->createCondition($filter);

        $records = CampaignPollInfo::model()->findAll(array(
            'select'    => 'id, resposta AS resposta2, COUNT( resposta ) AS sumresposta',
            'join'      => $this->join,
            'condition' => $this->filter,
            'params'    => $this->paramsFilter,
            'order'     => 'resposta DESC',
            'group'     => 'resposta',

        ));
        echo json_encode(array(
            $this->nameRoot  => $records,
            $this->nameCount => count($records),
        ));

    }

}
