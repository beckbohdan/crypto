<?php 

namespace Drupal\crypto\Controller;

use Drupal\Core\Access\AccessResult;
use Drupal\Core\Session\AccountInterface;
use Drupal\Core\Controller\ControllerBase;

class cryptoController extends ControllerBase{
	public function cryptoApi(){
		$content = "";
		$coin_ = crypto_get_api();

		$header = array(
					array('data'=>t("Назва")),
					array('data'=>t("Ціна")),
					array('data'=>t("Обсяг за добу")),
					array('data'=>t("Капіталізація")),
					array('data'=>t("Зворотня пропозиція")),
					array('data'=>t("Зміна за добу")),
			);
			$rowsArr = [];
			if(!empty($coin_['currency'])){
				foreach($coin_['currency'] as $cur){
					$rowsArrCr =[];
					foreach($cur as $code=>$value_){
						if(!in_array($code,["name","market_cap_usd","price_usd","24h_volume_usd","total_supply","percent_change_24h"])) continue;
						switch($code){
							case "24h_volume_usd":
							case "market_cap_usd":
								$value_ = '<span class="price_all">$' .$value_.'</span>';
								break;
							case "total_supply":
								$value_ = '<span class="total_supply">'.$value_.' '.$cur["symbol"].'</span>';
                            	break;
                       		case "price_usd":
                            	$value_ = '<span class="price_usd">$ '.$value_.'</span>';
                           		break;
                       		case "percent_change_24h":
                            	$sc_ = ($value_>0?'Up':'Down');
                            	$value_ = '<span class="'.$sc_.'">'.$value_.' %</span>';
                            	break;
                       		case "name":
                            	$value_ = '<span class="nameCurrency">'.$value_.'</span>';
                            	break;                    
						}
						$rowsArrCr['date_'.$code] = ['data'] => ['data' => ['#markup'] => $value_]];
					}
					$rowsArr[] = $rowsArrCr;
				}
			}
			$table = array(
					'#type' => 'table',
					'#header' => $header,
					'#rows' => $rowsArr,
					'#attributes'=> array ('id' => 'crypto-modules-table-content',
					),
			);
			$content .= drupal_render($table);
			$output = array();
			$output['#title'] = t('Криптовалюти')."станом на ".("d/m/Y H:i",$coin_['date']);
			$output['#markup'] = $content;
			$output['#attached']['library'][]='crypto/crypto_css';
			return $output;
	}
}
