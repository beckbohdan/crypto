<?php 

namespace Drupal\crypto\Controller;

use Drupal\Core\Access\AccessResult;
use Drupal\Core\Session\AccountInterface;
use Drupal\Core\Controller\ControllerBase;

class cryptoController extends ControllerBase{
	public function cryptoApi(){
		$content = "";
		$coin = crypto_get_api();

		$header = array(
					array('data'=>t("Назва")),
					array('data'=>t("Капіталізація")),
					array('data'=>t("Ціна")),
					array('data'=>t("Обсяг(доба)")),
					array('data'=>t("Зворотня пропозиція")),
					array('data'=>t('Зміна(доба)')),
			);
			$rowArr = [];
			if(!empty($coin['currency'])){
				foreach($coin['currency'] as $cur){
					$rowArrCr =[];
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
						$rowArrCr['date_'.$code] = ['data'] => ['data' => ['#markup'] => $value_]];
					}
					$rowArr[] = $rowArrCr;
				}
			}
			$table = array(
					'#type' => 'table',
					'#header' => $header,
					'#rows' => $rowArr,
					'#attributes'=> array (
									'id' => 'crypto-modules-table-content',
					),
			);
			$content .= drupal_render($table);
			$output = array();
			$output['title'] = t('Криптовалюти')."станом на ".("d/m/Y H:i",$coin_['date']);
			$output['#markup'] = $content;
			$output['#attached']['library'][]='crypto/crypto_css';
			return $output;
	}
}