<?php

namespace Drupal\crypto\Controller;

use Drupal\Core\Access\AccessResult;
use Drupal\Core\Session\AccountInterface;
use Drupal\Core\Controller\ControllerBase;


class cryptoController extends ControllerBase {
	
    public function cryptoApi(){
        $content = "";
        $vc_ = crypto_get_api();
	    
        $header = array(
            array('data' => t("Назва")),
            array('data' => t("Ціна")),
            array('data' => t("Обсяг за добу")),
            array('data' => t("Капіталізація")),
            array('data' => t("Зворотня пропозиція")),
            array('data' => t("Зміна за добу")),
            
        );
        $rowsArr = [];
        if(!empty($vc_['currency'])){
            foreach($vc_['currency'] as $cur){
                $rowsArrCur = [];
                foreach ($cur as $code=>$val_){
                    if(!in_array($code,["name","market_cap_usd","price_usd","24h_volume_usd","total_supply","percent_change_24h"])) continue;
                    switch ($code){
                        case "24h_volume_usd":
                        case "market_cap_usd":
                            $val_ = '<span class="price_all">$ '.$val_.'</span>';
                            break;
                        case "total_supply":
                            $val_ = '<span class="total_supply">'.$val_.' '.$cur["symbol"].'</span>';
                            break;
                        case "price_usd":
                            $val_ = '<span class="price_usd">$ '.$val_.'</span>';
                            break;
                        case "percent_change_24h":
                            $sc_ = ($val_>0?'positive_change':'negative_change');
                            $val_ = '<span class="'.$sc_.'">'.$val_.' %</span>';
                            break;
                        case "name":
                            $val_ = '<span class="nameCurrency">'.$val_.'</span>';
                            break;
                    }
                    $rowsArrCur['date_' . $code] = ['data' => ['#markup' => $val_]];
                }
                $rowsArr[] = $rowsArrCur;
            }
        }
        $table = array(
            '#type' => 'table',
            '#header' => $header,
            '#rows' =>  $rowsArr,
            '#attributes' => array(
                'id' => 'crypto-modules-table-content',
            ),
        );
        $content .= drupal_render($table);
        //
        $output = array();
        $output['#title'] = t('Криптовалюти')." станом на ".date("d.m H:i",$vc_['date']);
        $output['#markup'] = $content;
        $output['#attached']['library'][] = 'crypto/crypto_css';
        return $output;
    }
}
