<?php
///By Qisho
ini_set('register_globals','off');
ini_set('session.use_cookies','0');
//ini_set('session.use_trans_sid',1);
//ini_set('arg_separator.output','&amp;');
if (!session_id()){
	#Trans SID sucks also...
//	@ini_set('url_rewriter.tags', '');
//	@ini_set('session.use_trans_sid', 0);
	if (isset($_GET['PHPSESSID']))
		session_id($_GET['PHPSESSID']);
	elseif (isset($_POST['PHPSESSID']))
		session_id($_POST['PHPSESSID']);
	elseif (isset($_COOKIE['PHPSESSID']))
		session_id($_COOKIE['PHPSESSID']);
	session_start();
}
if (preg_match("/Opera Mini/i", $_SERVER['HTTP_USER_AGENT']))  {  
    preg_match_all("|([0-9]{1,3}\.){3}[0-9]{1,3}|",$_SERVER['HTTP_X_FORWARDED_FOR'],$arr_ip);  
    $ip = $arr_ip[0][0];  
    if(empty($ip)) $ip=$_SERVER['REMOTE_ADDR'];  
    $ua = "Opera Mini/ ".$_SERVER['HTTP_X_OPERAMINI_PHONE_UA'];  
    if (empty($_SERVER['HTTP_X_OPERAMINI_PHONE_UA'])) $ua=$_SERVER['HTTP_USER_AGENT'];  
}  
else  {  
$ip=$_SERVER['REMOTE_ADDR'];  
$ua=$_SERVER['HTTP_USER_AGENT'];  
}  
$ip1=ip2long($ip);
$ip2=ip2long($ip);
$cid=session_id();
$ses="PHPSESSID=$cid";

require_once('classes/db_class.php');
require_once('classes/xhtml_class.php');
require_once('classes/game_class.php');
require_once('configs/game_configs.php');
if (!empty($_SESSION['user_id']) && $_SESSION['user_id']!=7066) {
	if ($ip!=$_SESSION['ip'] || $ua!=$_SESSION['ua']){
		unset($_SESSION['user_id']);
	}
}
$db= new mysql();
/*
if (empty($_SESSION['user_id']) && empty($_SESSION['lang'])){
	$db->Query("SELECT ips.opid,op.* FROM ipbase as ips LEFT JOIN countryoperators as op on op.id=ips.opid WHERE ips.ip1<=".$ip1." AND ips.ip2>=".$ip2."");
	if ($row=$db->FetchAssoc()){
	    if ($row['tld']=='GE') $_SESSION['lang']=1;
	    else
	    	$_SESSION['lang']=3;
    }
    else{
    	$_SESSION['lang']=3;
    }

}
*/
if (isset($_GET['ver'])){
	$_SESSION['lang']=intval($_GET['ver']);
	redirect('index.php');
}

switch ($_SESSION['lang']){
	case 1;
		$langfile='geo.php';
		DEFINE(LANG,'ge');
	break;
	case 2;
	    $langfile='ru.php';
	break;
     
	default;
	    $langfile='eng.php';
	    DEFINE(LANG,'eng');
    break;
}

require_once('classes/lang/'.$langfile);
$xhtml = new xhtml();
$lang = new lang();
$gClass= new game();


require_once('places.php');

DEFINE(SES,$ses);
function update_heroe($uid){
	global $gClass,$db,$xhtml,$LVL_EXP;
	//$pstats=$gClass->countAddingStats($_SESSION['user_id']);
	$gClass->updateLevel(USER_EXP,USER_LVL);
	//$db->Query("SELECT life,mana,life_points,mana_points,last_update FROM rpg_users where id='".$uid."' AND act=0");
	//if ($db->getAffectedRows()>0){
//	    $row=$db->FetchAssoc();
	    if (LIFE_SPEED>0){
	    	$life_speed=LIFE_UPDATE/((LIFE_SPEED/100)+1);
	    }
	    else $life_speed=LIFE_UPDATE;
	    $life=$gClass->life_updater(NOW_LIFE,LIFE_POINTS,LAST_UPDATE,$life_speed);
	    $mana=$gClass->life_updater(NOW_MANA,MANA_POINTS,LAS_UPDATE,MANA_UPDATE);
	    if ($life==0) $life=1;
	    if ($mana!=NOW_MANA || $life!=NOW_LIFE){
	        $db->Query("UPDATE rpg_users set life='".$life."', mana='".$mana."', last_update='".time()."' WHERE id='".$uid."'");
	        DEFINE(RENOW_LIFE,$life);
	    }
	    else{
	    	$db->Query("UPDATE rpg_users set last_update='".time()."' WHERE id='".$uid."'");
	    }
	    
}
function redirect($url){
	global $ses;
	header('Location:'.$url.(substr_count($url,'?')>0?'&':'?').$ses.'');
}

function topUsers(){
	global $db,$xhtml,$lang;
	$topc.=$lang->top20gamers;
	$db->Query("SELECT * FROM rpg_users ORDER BY exp DESC LIMIT 20");
	$n=0;
	while($row=$db->FetchAssoc()){
		$n++;
		$middlec.=$xhtml->getBlock($n.') '.$row['login'].' [lvl '.$row['level'].']','b');
	}
	$downc.=$xhtml->getLink('index.php',$lang->main);
	$xhtml->createPage($title,$topc,$middlec,$downc);
}

function index_main(){
	global $db,$xhtml,$lang,$gClass;
	$title=$lang->main_title;
	$topc.=$xhtml->getBlock($xhtml->getImage('icon/hd.gif','Legend'),'cnt');
	$middlec.=$xhtml->getBlock('legeda.wapop.org','z');
	$middlec.=$xhtml->getBlock('Online:'.$xhtml->getSpan($gClass->getOnline(),'color_red'),'z');
	$middlec.=$lang->game.' '.$xhtml->getSpan('LEGENDA','color_red').' '.$lang->gamelozung.$xhtml->getBR();
	$middlec.=$xhtml->getBlock($lang->reglozung1.$xhtml->getLink('index.php?reg',$lang->registration).$lang->reglozung2);
	$middlec.=$xhtml->startForm('index.php?'.$ses.'');
	if (defined('ENTER_ERROR')) $middlec.=$xhtml->getSpan(ENTER_ERROR,'color_red');
	$middlec.=$xhtml->getBlock($lang->name.$xhtml->getBR().$xhtml->getInput('us_login',(!empty($_COOKIE['LegendaLogin'])?$_COOKIE['LegendaLogin']:''),'text','input_100').$xhtml->getBR()
	.$lang->password.$xhtml->getBR().$xhtml->getInput('us_pass',(!empty($_COOKIE['LegendaPass'])?$_COOKIE['LegendaPass']:''),'password','input_100').$xhtml->getBR()
	.$xhtml->getInput('okin',$lang->enter,'submit','submit_50'));
	$middlec.=$xhtml->endForm();
	$downc.=$xhtml->getLink('index.php?reg',$lang->registration).$xhtml->getBR();
	$db->Query("SELECT COUNT(id) as s FROM rpg_users");
	$row=$db->FetchAssoc();
	$downc.=$xhtml->getLink('index.php?top_users','TOP 20').$xhtml->getBR();
	$downc.=$lang->registrs.':'.$xhtml->getSpan($row['s'],'color_red');
	$downc.=$xhtml->getBlock('Language:'.$xhtml->getLink('index.php?ver=3',$xhtml->getImage('icon/en.png','en',16,16)).$xhtml->getLink('index.php?ver=1',$xhtml->getImage('icon/ge.png','ge',16,16)));
	
	$xhtml->createPage($title,$topc,$middlec,$downc);
}
/*
dzala //Strength
intuicia //Accuracy
moqniloba //Dexterity
sicocxle //life
mana /mana
broni /bron
anti.krit //ant_Accuracy
anti.moqniloaba// ant_Dexterity
*/
function bag(){
	global $db,$xhtml,$gClass,$lang;
	$title=$lang->bag;
	$topc.=$gClass->getLifeManaLine();
	
    if (!empty($_SESSION['onitem'])){
    	$gClass->questSubmiter(2);
		$middlec.=$lang->uputon.':'.$_SESSION['onitem'].'';
		$hvdressed=1;
		unset($_SESSION['onitem']);
    }
	if (isset($_GET['onit'])){
		$onit=intval($_GET['onit']);
	    $db->Query("SELECT * FROM users_items WHERE usid='".$_SESSION['user_id']."' AND utid='".$onit."' AND act=0");
	    if ($db->getAffectedRows()==1){
	        $row=$db->FetchAssoc();
	        if ($row['lvl']<=USER_LVL){
	        $type=$row['ittype'];
	        if ($type!=20){
	        	
	        	if ($type==1 || $type==23){
	        		$qftp="(act='1' OR act='23')";
	        	}
	        	else{
	        		$qftp="act='".$type."'";
	        	}
	            $db->Query("SELECT utid,itname FROM users_items WHERE usid='".$_SESSION['user_id']."' and  ".$qftp."");
	            
	            $n=$db->getAffectedRows();
	            $r=$db->FetchAssoc();
	            if ($type==1 || $type==23){
	    	        if ($n>=2){
		                $db->Query("UPDATE users_items SET act=0 WHERE utid='".$r['utid']."' AND usid='".$_SESSION['user_id']."'");
		                $db->Query("UPDATE users_items SET act='".$type."' WHERE utid='".$onit."' AND usid='".$_SESSION['user_id']."'");
		            }
		            else{
		    	        $db->Query("UPDATE users_items SET act='".$type."' WHERE utid='".$onit."' AND usid='".$_SESSION['user_id']."'");
		             }
		             $_SESSION['onitem']=$row['itname'];
	            }
	            elseif($type==7){
    	    	    if ($n==4){
	    		        $db->Query("UPDATE users_items SET act=0 WHERE utid='".$r['utid']."' AND usid='".$_SESSION['user_id']."'");
		                $db->Query("UPDATE users_items SET act='".$type."' WHERE utid='".$onit."' AND usid='".$_SESSION['user_id']."'");
	    	        }
	    	        else{
	    	  	        $db->Query("UPDATE users_items SET act='".$type."' WHERE utid='".$onit."' AND usid='".$_SESSION['user_id']."'");
	    	        }
	    	        $_SESSION['onitem']=$row['itname'];
	            }
	            else{
	            	if ($type<20) {
    	    	        $db->Query("UPDATE users_items SET act=0 WHERE utid='".$r['utid']."' AND usid='".$_SESSION['user_id']."'");
    	    	       
    	    	    }
		            if ($type==21){
		            	$db->Query("UPDATE users_items SET acttime=acttime+".time().", act='".$type."' WHERE utid='".$onit."' AND usid='".$_SESSION['user_id']."'");
		            }
		            else {
		                $db->Query("UPDATE users_items SET act='".$type."' WHERE utid='".$onit."' AND usid='".$_SESSION['user_id']."'");
		                $_SESSION['onitem']=$row['itname'];
		            }
	            }
	            
	         }
	         else{
	         	 $gClass->addLifeMana($_SESSION['user_id'],$row['rest_life'],$row['rest_mana']);
	         	 $db->Query("DELETE FROM users_items WHERE utid='".$onit."' AND usid='".$_SESSION['user_id']."'");
	         	 redirect('index.php?bag',1);
	         }
	         $xhtml->redirect('index.php?bag&c='.intval($_GET['c']).'');
	        } 
	        else $middlec.=$lang->ulvlcantputon;
	    }
	    else $middlec.= $lang->uhnotitem;
	}
	if (isset($_GET['c'])){

		$c=intval($_GET['c']);
		if ($c==200) $fq='ittype=20 OR ittype=21';
		if ($c<200) $fq="ittype='".$c."'";
		if ($c==300) $fq='ittype=23';
		$db->Query("SELECT * FROM users_items WHERE usid='".$_SESSION['user_id']."' AND (".$fq.") AND act=0");
		if ($db->getAffectedRows()>0){
		while ($row=$db->FetchAssoc()){
			   $middlec.=$xhtml->getBlock($row['itname'].'['.$row['lvl'].'lvl]'.(!empty($row['modit'])?'(Mod:'.$row['modit'].')':''),'baypars').$xhtml->getBlock('','divhr');
	           $middlec.=$xhtml->getImage(ITEMS_PATH.$row['img'],$row['itname']).$xhtml->getBR();
	           if (!empty($row['description'])) $middlec.=$gClass->languageReplace($row['description'],$_SESSION['lang']).$xhtml->getBR();
	           $middlec.=$lang->lifetime.':'.$gClass->retMinSec($row['broktime']-time()).$xhtml->getBR();
	           $middlec.=$xhtml->getLink('index.php?bag&amp;c='.$c.'&amp;onit='.$row['utid'].'',($row['ittype']==20 || $row['ittype']==21?$lang->drink:$lang->puton));
		}

		}
		else{
			if(empty($hvdressed)){
			   $middlec.=$xhtml->getBlock($lang->uhnotittyepitem,'b');
			}
			
		}
		$downc.=$xhtml->getLink('index.php?bag',$lang->bag).$xhtml->getBR();
	}
	else{
	$db->Query("SELECT ittype FROM users_items WHERE usid='".$_SESSION['user_id']."' AND act=0");
	$arr=array_fill(1,30,0);
	$arr[200]=0;
	$arr[300]=0;
	while ($row=$db->FetchAssoc()){
		if ($row['ittype']==20 || $row['ittype']==21){
			$arr[200]++;
		}
		elseif($row['ittype']==23){
			$arr[300]++;
		}
		else{
		   $arr[$row['ittype']]++;
		}
	}

	$middlec.=$lang->bagvolume.':'.$gClass->countBagItems().'/'.((USER_LVL+1)*5).$xhtml->getBR();
	$middlec.=$xhtml->getLink('index.php?bag&amp;c=1',$lang->weapons).' ['.$arr[1].']'.$xhtml->getBR();
	$middlec.=$xhtml->getLink('index.php?bag&amp;c=2',$lang->helmets).' ['.$arr[2].']'.$xhtml->getBR();
	$middlec.=$xhtml->getLink('index.php?bag&amp;c=3',$lang->armors).' ['.$arr[3].']'.$xhtml->getBR();
	$middlec.=$xhtml->getLink('index.php?bag&amp;c=4',$lang->pants).' ['.$arr[4].']'.$xhtml->getBR();
	$middlec.=$xhtml->getLink('index.php?bag&amp;c=5',$lang->shoes).' ['.$arr[5].']'.$xhtml->getBR();
	$middlec.=$xhtml->getLink('index.php?bag&amp;c=6',$lang->gloves).' ['.$arr[6].']'.$xhtml->getBR();
	$middlec.=$xhtml->getLink('index.php?bag&amp;c=7',$lang->rings).' ['.$arr[7].']'.$xhtml->getBR();
	$middlec.=$xhtml->getLink('index.php?bag&amp;c=8',$lang->amulets).' ['.$arr[8].']'.$xhtml->getBR();
	$middlec.='-------'.$xhtml->getBR();
	$middlec.=$xhtml->getLink('index.php?bag&amp;c=300','Xelsawyoebi').' ['.$arr[300].']'.$xhtml->getBR();
	$middlec.=$xhtml->getLink('index.php?bag&amp;c=200',$lang->elexirs).' ['.$arr[200].']'.$xhtml->getBR();
	}
	/*
	while ($row=$db->FetchAssoc()){
		$middlec.=$xhtml->getBlock($row['itname'],'baypars').$xhtml->getBlock('','divhr');
	    $middlec.=$xhtml->getImage(ITEMS_PATH.$row['img'],$row['itname']).$xhtml->getBR();
	    if (!empty($row['description'])) $middlec.=$row['description'].$xhtml->getBR();
	    $middlec.=$xhtml->getLink('index.php?bag&onit='.$row['utid'].'',($row['ittype']>=20?'Daleva':'Chacma'));
	}
	*/
	$downc.=$xhtml->getLink('index.php',$lang->main);
	$xhtml->createPage($title,$topc,$middlec,$downc);
}

function shop(){
	global $db,$xhtml,$gClass,$lang;
	$title=$lang->shop;
	$topc.=$gClass->getLifeManaLine();
	if (isset($_GET['sell'])){
		if (isset($_GET['c'])){
			$c=intval($_GET['c']);
		    	if (isset($_GET['item'])){
		    		$item=intval($_GET['item']);
		    		$db->Query("SELECT itname,utid,price FROM users_items WHERE utid='".$item."' AND usid='".USER_ID."' AND ittype='".$c."' AND act=0");
		    		if ($db->getAffectedRows()>0){
		    			$srow=$db->FetchAssoc();
		    			$addmoney=round($srow['price']/5,2);
		    			$db->Query("UPDATE rpg_users SET money=money+".$addmoney." WHERE id='".USER_ID."'");
		    			$db->Query("DELETE FROM users_items WHERE utid='".$item."'");
		    			$_SESSION['answer']=$lang->usellitem.$srow['itname'].' - '.$lang->sellgetgold.' '.$addmoney.' '.$lang->gold;
		    		}
		    		else{
		    			$_SESSION['answer']=$lang->itemnotfound;
		    		}
		    		redirect('index.php?shop&sell&c='.$c.'&ans',1);
		    	}
		    	if (isset($_GET['ans'])){
		    	    $middlec.=$_SESSION['answer'];
		    	    unset($_SESSION['answer']);
		    	}
		    	$db->Query("SELECT * FROM users_items WHERE usid='".$_SESSION['user_id']."' AND ittype='".$c."' AND act=0");
		    	if ($db->getAffectedRows()>0){
		            while ($row=$db->FetchAssoc()){
			               $middlec.=$xhtml->getBlock($row['itname'],'baypars').$xhtml->getBlock('','divhr');
	                       $middlec.=$xhtml->getImage(ITEMS_PATH.$row['img'],$row['itname']).$xhtml->getBR();
	                       if (!empty($row['description'])) $middlec.=$gClass->languageReplace($row['description'],$_SESSION['lang']).$xhtml->getBR();
	                       $middlec.=$lang->sellcost.':'.round($row['price']/5,2).''.$xhtml->getBR();
	                       $middlec.=$xhtml->getLink('index.php?shop&amp;sell&amp;c='.$c.'&amp;item='.$row['utid'].'',$lang->sell);
		            }
		        }
		        else{
		        	$middlec.=$lang->uhnotittyepitem;
		        }
		}
		else{
    	$db->Query("SELECT ittype FROM users_items WHERE usid='".$_SESSION['user_id']."' AND act=0");
	    $arr=array_fill(1,30,0);
	    while ($row=$db->FetchAssoc()){
		       $arr[$row['ittype']]++;
	    }
	    $middlec.=$xhtml->getLink('index.php?shop&amp;sell&amp;c=1',$lang->weapons).' ['.$arr[1].']'.$xhtml->getBR();
	    $middlec.=$xhtml->getLink('index.php?shop&amp;sell&amp;c=2',$lang->helmets).' ['.$arr[2].']'.$xhtml->getBR();
	    $middlec.=$xhtml->getLink('index.php?shop&amp;sell&amp;c=3',$lang->armors).' ['.$arr[3].']'.$xhtml->getBR();
	    $middlec.=$xhtml->getLink('index.php?shop&amp;sell&amp;c=4',$lang->pants).' ['.$arr[4].']'.$xhtml->getBR();
	    $middlec.=$xhtml->getLink('index.php?shop&amp;sell&amp;c=5',$lang->shoes).' ['.$arr[5].']'.$xhtml->getBR();
	    $middlec.=$xhtml->getLink('index.php?shop&amp;sell&amp;c=6',$lang->gloves).' ['.$arr[6].']'.$xhtml->getBR();
	    $middlec.=$xhtml->getLink('index.php?shop&amp;sell&amp;c=7',$lang->rings).' ['.$arr[7].']'.$xhtml->getBR();
	    $middlec.=$xhtml->getLink('index.php?shop&amp;sell&amp;c=8',$lang->amulets).' ['.$arr[8].']'.$xhtml->getBR();
	    $middlec.='-------'.$xhtml->getBR();
	    $middlec.=$xhtml->getLink('index.php?shop&amp;sell&amp;c=23','Xelsawyoebi').' ['.$arr[23].']'.$xhtml->getBR();
	    $middlec.='---'.$xhtml->getBR();
	    $middlec.=$xhtml->getLink('index.php?shop&amp;sell&amp;c=20',$lang->elexirs).' ['.$arr[20].']'.$xhtml->getBR();
	    }
	}
	else{
	if (isset($_GET['c'])){
    	$c=intval($_GET['c']);
	    if (($c>0 && $c<9) || $c==23){
	    	if (isset($_GET['item'])){
	    		$item=intval($_GET['item']);
	    		$db->Query("SELECT * FROM rpg_items WHERE itid='".$item."'");
	    		if ($db->getAffectedRows()<=0){
	    			$middlec.=$lang->itemnotfound;
	    		}
	    		else {
	    			
	    			$itinfo=$db->FetchAssoc();
	    			if (isset($_GET['bay'])){
	    				if (((USER_LVL+1)*5)<=$gClass->countBagItems()){
	    					$_SESSION['answer']=$lang->uhnotbagplace;
	    				}
	    				else{
	    				$_SESSION['answer']=$gClass->bayItem($itinfo,$_SESSION['user_id']);
	    				}
	    				redirect('index.php?shop&c='.$c.'&item='.$item.'&wb='.$ans.'',1);
	    			}
	    			    if (isset($_GET['wb'])) {
	    			        $middlec.=$_SESSION['answer'];
	    			        unset($_SESSION['answer']);
	    			    }
	    			    $middlec.=$xhtml->getBlock($itinfo['itname'],'baypars').$xhtml->getBlock('','divhr');
	    			    $middlec.=$xhtml->getImage(ITEMS_PATH.$itinfo['img'],$itinfo['itname'],'','','item_block');
	    			    $middlec.=$xhtml->getBlock($lang->parametrs,'parblock').$xhtml->getBlock('','divhr');
	    			    if (!empty($itinfo['strength_p'])){
	    				    $parst.=$lang->strength.': '.$itinfo['strength_p'].$xhtml->getBR();
	    			    }
	    			    if (!empty($itinfo['accuracy_p'])){
	    				    $parst.=$lang->accuracy.': '.$itinfo['accuracy_p'].$xhtml->getBR();
	    			    }
	    			    if (!empty($itinfo['dexterity_p'])){
	    				    $parst.=$lang->dexterity.': '.$itinfo['dexterity_p'].$xhtml->getBR();
	    			    }
	    			    if (!empty($itinfo['blocking_p'])){
	    				    $parst.=$lang->blocking.': '.$itinfo['blocking_p'].$xhtml->getBR();
	    			    }
	    			    if (!empty($itinfo['life_p'])){
	    				    $parst.=$lang->life.': '.$itinfo['life_p'].$xhtml->getBR();
	    			    }
	    			    if (!empty($itinfo['mana_p'])){
	    				    $parst.=$lang->mana.': '.$itinfo['mana_p'].$xhtml->getBR();
	    			    }
	    			    if (!empty($itinfo['ant_accuracy_p'])){
	    				    $parst.=$lang->antaccuracy.':'.$itinfo['ant_accuracy_p'].$xhtml->getBR();
	    			    }
	    			    if (!empty($itinfo['ant_dexterity_p'])){
	    				    $parst.=$lang->antdexterity.': '.$itinfo['ant_dexterity_p'].$xhtml->getBR();
	    			    }
	    			    if (!empty($itinfo['ant_blocking_p'])){
	    				    $parst.=$lang->antblocking.': '.$itinfo['ant_blocking_p'].$xhtml->getBR();
	    			    }
	    			    if (!empty($itinfo['bron_p'])){
	    				    $parst.=$lang->bron.': '.$itinfo['bron_p'].$xhtml->getBR();
	    			    }
	    			    if (!empty($itinfo['dig_p'])){
	    			    	$parst.=$lang->resfoundhaz.': +'.$itinfo['dig_p'].' &#37;'.$xhtml->getBR();
	    			    }
	    			    $parst.=$lang->level.':'.(USER_LVL<$itinfo['lvl']?$xhtml->getSpan($itinfo['lvl'],'color_red'):$itinfo['lvl']).'';
	    			    if (USER_LVL<$itinfo['lvl']) $parst.=$xhtml->getBlock($lang->forputonmin.' '.$itinfo['lvl'].' '.$lang->level,'color_red');
	    			    $middlec.=$xhtml->getBlock($parst,'baypars');
	    			    $middlec.=$xhtml->getBlock('','divhr');
	    			    $middlec.=$xhtml->getBlock($lang->lifetime.':'.$gClass->retMinSec($itinfo['broktime']).'','baypars');
	    			    $middlec.=$xhtml->getBlock($lang->price.': '.$itinfo['price'].' '.$lang->gold,'baypars');
	    			    $middlec.=$xhtml->getBlock($lang->inshop.':'.$itinfo['csum'].' '.$lang->csumer,'baypars');
	    			    $middlec.=$xhtml->getLink('index.php?shop&amp;c='.$c.'&amp;item='.$item.'&amp;bay',$lang->buy);
	    		}
	    	}
	    	else{
	    		if (USER_LVL<=2) $selected=0;
	    		if (USER_LVL>2 && USER_LVL<=5) $selected=1;
	    		if (USER_LVL>5 && USER_LVL<=7) $selected=2;
	    		if (USER_LVL>7 && USER_LVL<=9) $selected=3;
	    		if (USER_LVL>9 && USER_LVL<=11) $selected=4;
	    		if (isset($_POST['flvl'])){
	    			$qlvl=intval($_POST['flvl']);
	    			$qlvl=($qlvl>=0?$qlvl:$selected);
	    		}
	    		else 
	    		    $qlvl=$selected;
	    		switch ($qlvl){
	    			case 0;
	    			$qfiltr='lvl=0 OR lvl=1 OR lvl=2';
	    			break;
	    			case 1;
	    			$qfiltr='lvl=3 OR lvl=4 OR lvl=5';
	    			break;
	    			case 2;
	    			$qfiltr='lvl=6 OR lvl=7';
	    			break;
	    			case 3;
	    			$qfiltr='lvl=8 OR lvl=9';
	    			break;
	    			case 4;
	    			$qfiltr='lvl=10 OR lvl=11';
	    			break;
	    		}
	    	
	            $db->Query("SELECT * FROM rpg_items WHERE ittype='".$c."' AND  csum>0 AND (".$qfiltr.") ORDER BY lvl ASC");
	            if ($db->getAffectedRows()>0)
	            while($row=$db->FetchAssoc()){
	    	         $middlec.=$xhtml->getLink('index.php?shop&amp;c='.$c.'&amp;item='.$row['itid'].'',$row['itname']).' ('.$row['price'].' '.$lang->gold.') lvl:'.(USER_LVL<$row['lvl']?$xhtml->getSpan($row['lvl'],'color_red'):$row['lvl']).$xhtml->getBR();
	            }
	            else $middlec.=$lang->itemtypenotgound;
	            $s.=$lang->searchbylvl.':'.$xhtml->getBR().$xhtml->startForm('index.php?shop&amp;c='.$c.'','post');
	    		$lvls=array(0=>'0-2',1=>'3-5',2=>'6-7',3=>'8-9',4=>'10-11');
	    		$s.=$lang->level.': '.$xhtml->getSelect('flvl',$lvls).$xhtml->getInput('f','OK','submit');
	    		$s.=$xhtml->endForm();
	    		$middlec.=$xhtml->getBlock($s,'b');
	        }
	    }
	
	    $downc.=$xhtml->getLink('index.php?shop',$lang->inshop).$xhtml->getBR();
	}
	else {
	      $middlec.=$xhtml->getLink('index.php?shop&amp;c=1',$lang->weapons).$xhtml->getBR();
	      $middlec.=$xhtml->getLink('index.php?shop&amp;c=2',$lang->helmets).$xhtml->getBR();
	      $middlec.=$xhtml->getLink('index.php?shop&amp;c=3',$lang->armors).$xhtml->getBR();
	      $middlec.=$xhtml->getLink('index.php?shop&amp;c=4',$lang->pants).$xhtml->getBR();
	      $middlec.=$xhtml->getLink('index.php?shop&amp;c=5',$lang->shoes).$xhtml->getBR();
	      $middlec.=$xhtml->getLink('index.php?shop&amp;c=6',$lang->gloves).$xhtml->getBR();
	      $middlec.=$xhtml->getLink('index.php?shop&amp;c=7',$lang->rings).$xhtml->getBR();
	      $middlec.=$xhtml->getLink('index.php?shop&amp;c=8',$lang->amulets).$xhtml->getBR();
	      $middlec.='---'.$xhtml->getBR();
	      $middlec.=$xhtml->getLink('index.php?shop&amp;c=23',$lang->equipment).$xhtml->getBR();
	      $middlec.='---'.$xhtml->getBR();
	      $middlec.=$xhtml->getLink('index.php?shop&amp;sell',$lang->sellitems);
	}
	}
	$downc.=$xhtml->getLink('index.php',$lang->main);
	$xhtml->createPage($title,$topc,$middlec,$downc);
}
function rase_pars($which,$getQuery='',$m){
	$human_pars = array('strength'=>'3','accuracy'=>'3','dexterity'=>'3','blocking'=>'3','intelect'=>'0','life_points'=>'6','life'=>'30','mana_points'=>'0','mana'=>'0','bron'=>'0','img'=>'1','money'=>'2');
	$elf_pars = array('strength'=>'2','accuracy'=>'6','dexterity'=>'0','blocking'=>'2','intelect'=>'0','life_points'=>'8','life'=>'40','mana_points'=>'0','mana'=>'0','bron'=>'0','img'=>'2','money'=>'2');
	$ork_pars = array('strength'=>'4','accuracy'=>'0','dexterity'=>'6','blocking'=>'2','intelect'=>'0','life_points'=>'6','life'=>'30','mana_points'=>'0','mana'=>'0','bron'=>'0','img'=>'3','money'=>'2');	
	$gnom_pars = array('strength'=>'3','accuracy'=>'1','dexterity'=>'3','blocking'=>'2','intelect'=>'0','life_points'=>'6','life'=>'30','mana_points'=>'0','mana'=>'0','bron'=>'1','img'=>'4','money'=>'2');
	$hobit_pars = array('strength'=>'2','accuracy'=>'2','dexterity'=>'1','blocking'=>'6','intelect'=>'0','life_points'=>'7','life'=>'35','mana_points'=>'0','mana'=>'0','bron'=>'0','img'=>'5','money'=>'2');
	/*
	$gnom_pars = array('4','3','2','5','2','5','2','2');
	$ork_pars = array('3','4','2','5','2','4','4','2');
	$elf_pars = array('3','2','5','4','2','2','3','7');
	*/
	$pars=array(1=>$human_pars,2=>$elf_pars,3=>$ork_pars,4=>$gnom_pars,5=>$hobit_pars);
	if ($getQuery==1){
		$all=count($pars[$which]);
		$c=0;
		foreach ($pars[$which] as $key=>$value){
			$c++;
			if ($key=='img'){
				if ($m==2)
				$query_str.=' '.$key.'="'.$value.'2.gif"';
				else
					$query_str.=' '.$key.'="'.$value.'.gif"';
			} 
			else{
			    $query_str.=' '.$key.'='.$value.'';
			}
			if ($c!=$all) $query_str.=',';
		}
		$res=$query_str;
	}
	else{
		$res=$pars[$which];
	}
	return $res;
}
function registration(){
	global $db,$xhtml,$lang,$gClass,$ip,$ua;
	$title=$lang->registration;
	$topc.=$xhtml->getSpan($lang->regTopAlert,'color_white');
	if (!empty($_POST['okreg'])){
		$login=$_POST['login'];
		$pass=$_POST['pass'];
		$sex=$_POST['sex'];
		$rasa=intval($_POST['rasa']);
		$err='';
		$err=(preg_match('/[^0-9a-zA-Z\-\_]+/',$login)?$lang->ireg_login:$err);
		$err=(preg_match('/[^0-9a-zA-Z\-\_]+/',$pass)?$lang->ireg_pass:$err);
		$err=((strlen($pass)<4)?$lang->small_pass:$err);
		$err=((strlen($pass)>12)?$lang->big_pass:$err);
		$err=((strlen($login)<4)?$lang->small_login:$err);
		$err=((strlen($login)>12)?$lang->big_login:$err);
		$err=(empty($login)?$lang->empty_login:$err);
		$err=(empty($pass)?$lang->empty_pass:$err);
		$err=(($rasa>5 || $rasa<1)?$lang->err_in_rasa_select:$err);
		$err=(($sex<1 || $sex>2)?$lang->$sex_error:$err);
		if (empty($err)){
			$db->Query("SELECT id FROM rpg_users where login='".$login."'");
			if ($db->getAffectedRows()>0) $err=$lang->reg_userexists;
		}
		if (!empty($err)) $middlec.=$xhtml->getBlock($xhtml->getSpan($lang->fiqs_error,'color_red').$xhtml->getBR().'&nbsp;&nbsp;'.$err,'alert');
		else {
			if (empty($_COOKIE['Multy'])){
    		    setcookie('Multy',$row['id']);
    		    $multy=$row['id'];
    	    }
    	    else{
    		    $multy=intval($_COOKIE['Multy']);
    	    }
			$db->Query("INSERT INTO rpg_users set login='".$login."', password='".$pass."', sex='".$sex."', rasa='".$rasa."', place=14, lang='".$_SESSION['lang']."', reg_date=NOW(), last_update='".time()."', ua='".$_SERVER['HTTP_USER_AGENT']."', cook='".$multy."', uip='".$_SERVER['REMOTE_ADDR']."', ".rase_pars($rasa,1,$sex)."");
			if ($db->getAffectedRows()>0){
				$_SESSION['user_id']=$db->lastInsertID();
				$_SESSION['user_name']=$login;
				$_SESSION['ua']=$ua;
            	$_SESSION['ip']=$ip;
				if (!empty($_SESSION['refu'])){
				$ref=intval($_SESSION['refu']);
				if ($ref>0){
					$db->Query("INSERT INTO users_ref SET uid='".$ref."', ref='".$_SESSION['user_id']."', regdate=NOW()");
				}
			    }
				
				setcookie('LegendaLogin',$login);
				setcookie('LegendaPass',$pass);
				$topc=$lang->reg_finished.$xhtml->getBR();
				$middlec.=$lang->your_name_is.$xhtml->getSpan($login,'color_red').$xhtml->getBR();
				$middlec.=$lang->your_pass_is.$xhtml->getSpan($pass,'color_red').$xhtml->getBR();
				$middlec.=$lang->alert_after_reg.$xhtml->getBR();
				$db->Query("INSERT INTO users_items SET usid='".$_SESSION['user_id']."', itname='Newbie Relife', lvl='0', ittype='21', rest_life='500', acttime='".(time()+(3600*24))."', broktime='".(time()+(3600*24))."', act='21'");
				$middlec.=$xhtml->getLink('index.php?main',$lang->enter_in_game);
				$xhtml->createPage($title,$topc,$middlec,$downc);
			}
		}
	}
	$middlec.=$xhtml->startForm('index.php?reg');
	$middlec.=$xhtml->getBlock(
	$lang->name.$xhtml->getBR().$xhtml->getInput('login',$_POST['login'],'text','input_100').$xhtml->getBR()
	.$lang->password.$xhtml->getBR().$xhtml->getInput('pass','','password','input_100').$xhtml->getBR()
	.$lang->sex.$xhtml->getBR().$xhtml->getSelect('sex',array('1'=>$lang->sex1,'2'=>$lang->sex2)).$xhtml->getBR()
	.$lang->rasa.$xhtml->getBR().$xhtml->getSelect('rasa',$gClass->rases()).$xhtml->getBR()
	.$xhtml->getInput('okreg',$lang->registration,'submit','submit_100')
	);
	$middlec.=$xhtml->endForm();
	$downc.=$xhtml->getLink('index.php',$lang->main);
	$xhtml->createPage($title,$topc,$middlec,$downc);
}
function authorize($login,$pass){
	global $db,$lang,$xhtml,$gClass,$ip,$ua;
    $login=preg_replace("/[^a-z A-Z0-9]/", "", $login);
    $passw=preg_replace("/[^a-z A-Z0-9]/", "", $pass);
    $db->Query("SELECT id,login,bann,theme FROM rpg_users where login='".$login."' and password='".$pass."'");
    if ($row=$db->FetchArray()){
    	   if ($row['bann']>time()){
    	   	   $db->Query("SELECT * FROM rpg_banns WHERE uid='".$row['id']."' ORDER BY id DESC LIMIT 1");
    	   	   $r=$db->FetchAssoc();
    	   	   if ($r['superbann']>0){
    	   	   	   setcookie('BN',$row['bann']);
    	   	   }
    	   	   if ($r['chatoff']==0) 
    	   	       DEFINE('ENTER_ERROR',$lang->userbloked.$xhtml->getBR().$lang->reason.':'.$r['description'].$xhtml->getBR().$lang->bloktimeout.':'.$gClass->retMinSec(($row['bann']-time())));
    	   }
    	   else{
        if (empty($_COOKIE['Multy'])){
    		setcookie('Multy',$row['id']);
    		$multy=$row['id'];
    	}
    	else{
    		$multy=intval($_COOKIE['Multy']);
    	}
    	setcookie('LegendaLogin',$login);
		setcookie('LegendaPass',$pass);
    	$_SESSION['user_id']=$row['id'];
    	$_SESSION['user_name']=$row['login'];
    	$_SESSION['theme']=$row['theme'];
    	$_SESSION['ua']=$ua;
    	$_SESSION['ip']=$ip;
    	$db->Query("UPDATE rpg_users SET ua='".$_SERVER['HTTP_USER_AGENT']."', cook='".$multy."', bann=0 where id='".$_SESSION['user_id']."'");
    	   }
    }
    else{
    	define('ENTER_ERROR',$lang->enter_error);
    }
}
function cookieBANN(){
	global $db,$lang,$xhtml,$gClass;
	$xhtml->createPage('BANN',$topc,'TQVEN GADEVT BANNI',$downc);
	
}
function BEC($str){
	return base64_encode($str);
}
function BDC($str){
	return base64_decode($str);
}
function getHeroeStats($uid,$view=''){
	global $db,$xhtml,$gClass,$lang,$LVL_EXP;
	if ($view>0){
	    $pstats=$gClass->countStats($uid,',clanid,animal,inf,bann');
	    $statsRate=$gClass->countStatsRate($pstats);
	    $lifeS=$pstats['life'].'/'.($pstats['life_points']*5);

	}
	else{
		$statsRate=$gClass->countOwnStatsRate();
		$lifeS=NOW_LIFE.'/'.(LIFE_POINTS*5);
	}
	$db->Query("SELECT * FROM rpg_users where id='".$uid."'");
	if ($row=$db->FetchAssoc()){
		if (empty($view)){
		    $un_st=($row['unused_points']>0?$row['unused_points']:0);
		    $stat_array=array('strength'=>'as','dexterity'=>'ad','accuracy'=>'ac','blocking'=>'bk','intelect'=>'int','life_points'=>'al','mana_points'=>'am');
		    foreach ($stat_array as $key=>$value){
			    if (isset($_GET[$value])){
				    if ($un_st>0){
					    $db->Query("UPDATE rpg_users set ".$key."=".$key."+1, unused_points=unused_points-1 where id='".$uid."'");
			     	}
			        redirect('index.php?person');
			        exit;
			    }
		   }
		}
		
		$topc.=$gClass->getLifeManaLine();
		$middlec.=$xhtml->getBlock($row['login'].' ['.$gClass->rases($row['rasa']).']','b');
		$middlec.=$xhtml->getBlock($xhtml->getImage('images/p/'.$row['img']),'z');
		$middlec.=$xhtml->getBlock($lang->level.': '.(empty($view)?USER_LVL:$row['level']),'z');
		$middlec.=$xhtml->getBlock($lang->rate.': ['.$xhtml->getImage('icon/reit.png','R').$xhtml->getSpan($statsRate,'color_green').']','z');
		$middlec.=$xhtml->getBlock($lang->life.': ['.$xhtml->getImage('icon/h.png','L').$xhtml->getSpan($lifeS,'color_red').']','z');
		if (empty($view)){
		    $expgold.='Exp: '.$row['exp'].' ('.$LVL_EXP[$row['level']+1].') '.$xhtml->getImage('icon/exp.png').$xhtml->getBR();
		    $expgold.=$lang->gold.': '.$row['money'].' '.$xhtml->getImage('icon/g.png').$xhtml->getBR();
		    $expgold.=$lang->medal.': '.$row['medals'].' '.$xhtml->getImage('icon/medal.png');
		    $middlec.=$xhtml->getBlock($expgold,'z');
		}
		$wnlos.=$lang->win.': '.$row['wins'].$xhtml->getBR();
		$wnlos.=$lang->lose.': '.$row['loses'].$xhtml->getBR();
		$middlec.=$xhtml->getBlock($wnlos,'z');
		if (empty($view)){
		$middlec.=''.$xhtml->getBR();
		if ($un_st>0) $middlec.=$lang->freestats.':'.$un_st.$xhtml->getBR().'-------'.$xhtml->getBR();
		$statsource.=$lang->strength.':'.($view>0?$pstats['strength']:STRENGTH).' '.($un_st>0?$xhtml->getLink('index.php?person&amp;as','+'):'').$xhtml->getBR();
		$statsource.=$lang->dexterity.':'.($view>0?$pstats['dexterity']:DEXTERITY).' '.($un_st>0?$xhtml->getLink('index.php?person&amp;ad','+'):'').$xhtml->getBR();
		$statsource.=$lang->blocking.':'.($view>0?$pstats['blocking']:BLOCKING).''.($un_st>0?$xhtml->getLink('index.php?person&amp;bk','+'):'').$xhtml->getBR();
		$statsource.=$lang->accuracy.':'.($view>0?$pstats['accuracy']:ACCURACY).''.($un_st>0?$xhtml->getLink('index.php?person&amp;ac','+'):'').$xhtml->getBR();
		$statsource.=$lang->intelect.':'.($view>0?$pstats['intelect']:INTELECT).$xhtml->getBR();
		$statsource.=$lang->lifepoints.':'.($view>0?$pstats['life_points']:LIFE_POINTS).' '.($un_st>0?$xhtml->getLink('index.php?person&amp;al','+'):'');
		$middlec.=$xhtml->getBlock($statsource,'z');
		//$middlec.='Inteleqti:'.$pstats['intelect'].' '.($un_st>0?$xhtml->getLink('index.php?person&int','+'):'').$xhtml->getBR();
		
		//$middlec.='Mana:'.$pstats['mana_points'].' '.($un_st>0?$xhtml->getLink('index.php?person&am','+'):'').$xhtml->getBR();
		$middlec.=''.$xhtml->getBR();
		$antsource.=$lang->antaccuracy.': '.($view>0?$pstats['ant_accuracy']:ANT_ACCURACY).'%'.$xhtml->getBR();
		$antsource.=$lang->antdexterity.': '.($view>0?$pstats['ant_dexterity']:ANT_DEXTERITY).'%'.$xhtml->getBR();
		$antsource.=$lang->antblocking.': '.($view>0?$pstats['ant_blocking']:ANT_BLOCKING).'%'.$xhtml->getBR();
		$antsource.=$lang->bron.':'.($view>0?$pstats['bron']:BRON).$xhtml->getBR();
		$middlec.=$xhtml->getBlock($antsource,'z');
		}
		
		if ($view>0){
			if ($pstats['clanid']>0){
				$db->Query("SELECT * FROM rpg_clans WHERE id='".$pstats['clanid']."'");
				$cr=$db->FetchAssoc();
				$middlec.=$xhtml->getBlock($lang->clan.':'.$cr['clanname'].(!empty($cr['clanico'])?'<img src="image.php?img='.CLANICONS.$cr['clanico'].'&w=24&h=24" alt=""/>':'').($uid==$cr['creator']?'('.$lang->commander.')':''),'zvklist');
			}
			if ($pstats['animal']>0){
				$db->Query("SELECT * FROM rpg_usersanimals WHERE aid='".$pstats['animal']."'");
				$anrow=$db->FetchAssoc();
				$f.=$lang->obienanimal.':'.$xhtml->getBR();
				$f.=$xhtml->getSpan($anrow['aname'].'('.$anrow['mainname'].')'.' ['.$anrow['alevel'].']','color_blue');
				$middlec.=$xhtml->getBlock($f,'zvklist');
			}
		}
		if ($view>0 && !empty($pstats['inf'])){
			$middlec.=$xhtml->getBlock($pstats['inf'],'b');
		}
		$middlec.='-- '.$lang->profession.'--'.$xhtml->getBR();
		$profsource.=$lang->hunter.':'.($view>0?$pstats['huntexp']:HUNT_EXP).$xhtml->getBR();
		$profsource.=$lang->miner.':'.($view>0?$pstats['pitexp']:PIT_EXP).$xhtml->getBR();
		$middlec.=$xhtml->getBlock($profsource,'z');
		if ($pstats['bann']>time()){
			$db->Query("SELECT * FROM rpg_banns WHERE uid='".$row['id']."' ORDER BY id DESC LIMIT 1");
    	   	$r=$db->FetchAssoc();
	    	$middlec.=$xhtml->getSpan($lang->userbloked.$xhtml->getBR().$lang->reason.':'.$r['description'].$xhtml->getBR().$lang->bloktimeout.':'.$gClass->retMinSec(($pstats['bann']-time())).'','color_red').$xhtml->getBR();
	    }
	    if ($view==0 && ANIMAL>0)
	    $middlec.=$xhtml->getBlock($xhtml->getLink('index.php?myanimal',$lang->myunimal),'zvklist');
		$middlec.=$xhtml->getBlock($xhtml->getLink('index.php?dressing'.(!empty($view)?'&amp;u='.$uid.'':''),$lang->dressing),'b');
		$db->Query("SELECT * FROM users_items WHERE usid='".$uid."' AND acttime>'".time()."' AND act=21");
		if ($db->getAffectedRows()>0){
			$middlec.=$xhtml->getBlock('*** '.$lang->reefects.' ***','z');
			
		    while ($rowel=$db->FetchAssoc()){
		    	
		    	$middlec.=$xhtml->getBlock($xhtml->getSpan($rowel['itname'],'color_blue').$xhtml->getBR().$lang->lifetimeshort.': '.$gClass->retMinSec(($rowel['acttime']-time())).'','z');
		    }
		}
		if ($_SESSION['user_id']==1)
		{
			if (!empty($row['cook']) && $uid!=1){
		    $q=$db->Query("SELECT * FROM rpg_users WHERE cook='".$row['cook']."' AND id!='".$row['id']."'");
		    $multi=$db->getAffectedRows();
		    
		    if ($multi>0){
		    	$middlec.=$xhtml->getBlock('MULTI:'.$multi.'','zvklist');
		    }
	    	}
			if ($multi>0){
				while ($mulrow=$db->FetchAssoc()){
					$middlec.=$xhtml->getLink('index.php?person&amp;u='.$mulrow['id'].'',$mulrow['login']).$xhtml->getBR();
				}
			}
			if ($uid!=1){
			    if (isset($_GET['bann']) && !empty($_POST['okbann'])){
				    $bntime=time()+(doubleval($_POST['sumds'])*86400);
				    $db->Query("UPDATE rpg_users SET bann='".$bntime."' WHERE id='".$row['id']."'");
				    $db->Query("INSERT INTO rpg_banns SET uid='".$row['id']."', description='".$_POST['bnreason']."', superbann='".$_POST['super']."', blockerid='".$_SESSION['user_id']."', actime=NOW()");
				    $db->Query("DELETE FROM  rpg_chat WHERE uid1='".$row['id']."'");
			    }
			    $middlec.=$xhtml->startForm('index.php?person&amp;bann'.(!empty($view)?'&amp;u='.$uid.'':''),'post');
        	    $middlec.='Dgeebi:'.$xhtml->getInput('sumds','','text','input_3').$xhtml->getBR().'Mizezi:'.$xhtml->getInput('bnreason','','text','input_4').$xhtml->getBR().$xhtml->getCheckBox('super','1');
            	$middlec.=$xhtml->getInput('okbann','bann','submit');
            	$middlec.=$xhtml->endForm();
            }
		    
		}
	}
	if (!empty($view) && !empty($_SESSION['location'])){
		$downc.='Ukan:'.$xhtml->getLink($_SESSION['location'][0],$_SESSION['location'][1]).$xhtml->getBR();
	}
	$downc.=$xhtml->getLink('index.php',$lang->main);
	$xhtml->createPage($xhtml->myhero,$topc,$middlec,$downc);
	exit;
}
//	iaragebi-1
// tavi 2
// zedatani 3
//sharvlebi 4
//fexsacmeli 5
// xeltatmani 6
// bechedi 7
//amuleti 8
function myAnimal(){
	global $xhtml,$db,$gClass,$lang,$ANIMAL_EXP;
	$title=$lang->myanimal;
	$topc.=$gClass->getLifeManaLine();
	
	$db->Query("SELECT * FROM rpg_usersanimals WHERE aid='".ANIMAL."'");
	$row=$db->FetchAssoc();
	$gClass->updateAnimalLevel($row['exp'],$row['alevel'],$row['type']);
	$middlec.=$xhtml->getBlock($row['aname'].'('.$row['mainname'].')','zvklist').$xhtml->getImage('images/a/'.$row['type'].'.jpg',$row['mainname']).$xhtml->getBR();
	$f.=$lang->strength.':'.$row['strength'].$xhtml->getBR();
    if ($row['accuracy']>0) $f.=$lang->accuracy.':'.$row['accuracy'].$xhtml->getBR();
	if ($row['dexterity']>0) $f.=$lang->dexterity.':'.$row['dexterity'].$xhtml->getBR();
	if ($row['blocking']>0) $f.=$lang->blocking.':'.$row['blocking'].$xhtml->getBR();
	if ($row['ant_accuracy']>0) $f.=$lang->antaccuracy.':'.$row['ant_accuracy'].$xhtml->getBR();
	if ($row['ant_dexterity']>0) $f.=$lang->antdexterity.':'.$row['ant_dexterity'].$xhtml->getBR();
	if ($row['ant_blocking']>0) $f.=$lang->antblocking.':'.$row['ant_blocking'].$xhtml->getBR();
	$f.=$lang->bron.':'.$row['bron'].$xhtml->getBR();
	$middlec.=$lang->level.':'.$row['alevel'].$xhtml->getBR();
	$middlec.='Exp:'.$row['exp'].' ('.$ANIMAL_EXP[$row['alevel']+1].')'.$xhtml->getBR();
	$middlec.=$lang->mealinfigth.':'.($row['alevel']*$row['fmeal']).$xhtml->getBR();
	$middlec.=$lang->eatinglvl.':'.$row['replete'].$xhtml->getBR();
	$middlec.=$xhtml->getBlock($f,'zvklist');
	$downc.=$xhtml->getLink('index.php',$lang->main);
	$xhtml->createPage($lang->myhero,$topc,$middlec,$downc);
}
function gamePost(){
	global $xhtml,$db,$lang;
	$title.=$lang->myphost;
	$topc=$lang->myphost;
	if (isset($_GET['send'])){
		if (MONEY>=1) {
		if (!empty($_POST['rnik'])){
			if (($_SESSION['lastposts']+60)>time()){
				$middlec.=$lang->postspam;
			}
			else{
			$rnik=htmlspecialchars(stripslashes($_POST['rnik']));
			$msg=htmlspecialchars(stripslashes($_POST['smsg']));
			$db->Query("SELECT id FROM rpg_users WHERE login='".$rnik."'");
			if ($row=$db->FetchAssoc()){
				$db->Query("UPDATE rpg_users SET money=money-1 WHERE id='".USER_ID."'");
				$db->Query("INSERT INTO rpg_postbag SET sid='".USER_ID."', slogin='".USER_NAME."', reid='".$row['id']."', msg='".$msg."', readit=1, sdate=NOW()");
				$lins=$db->lastInsertID();
				$db->Query("UPDATE rpg_users SET postid='".$lins."' WHERE id='".$row['id']."'");
				$_SESSION['lastposts']=time();
				$middlec.=$lang->phostsent;
			}
			else
				$middlec.=$lang->usernotfound;
			}
		}
		}
		else $middlec.=$lang->hnotmoneyfphost;
		$middlec.=$xhtml->startForm('index.php?postman&amp;send','post');
		$middlec.=$lang->rephostnik.':'.$xhtml->getBR();
		$middlec.=$xhtml->getInput('rnik','','text').$xhtml->getBR();
		$middlec.=$lang->letter.':'.$xhtml->getBR();
		$middlec.=$xhtml->getTextarea('smsg','','5','15').$xhtml->getBR();
		$middlec.=$xhtml->getInput('sb',$lang->send,'submit');
		$middlec.=$xhtml->endForm();
		$middlec.=$lang->phostsendprice;
		$downc.=$xhtml->getLink('index.php','Mtavari gverdi');
	    $xhtml->createPage($title,$topc,$middlec,$downc);
	}
	if (NEWPOST>0){
		$db->Query("UPDATE rpg_users SET postid=0 WHERE id='".USER_ID."'");
	}
	if (isset($_GET['view'])){
		$view=intval($_GET['view']);
		$db->Query("SELECT * FROM rpg_postbag WHERE id='".$view."' AND reid='".USER_ID."'");
		if ($row=$db->FetchAssoc()){
		    $db->Query("UPDATE rpg_postbag SET readit=0 WHERE id='".$view."'");
		    $sd=explode(' ',$row['sdate']);
		    $dm=explode('-',$sd[0]);
		    $hm=explode(':',$sd[1]);
		    $gt=$dm[1].'-'.$dm[2].' '.$hm[1].':'.$hm[2];
		    $middlec.=$gt.' '.($row['sid']>0?$xhtml->getLink('index.php?person&amp;u='.$row['sid'].'',$row['slogin']):$row['slogin']);
		    $middlec.=$xhtml->getBlock('','divhr');
		    $middlec.=$row['msg'];
		    $middlec.=$xhtml->getBlock('','divhr');
		}
		$downc.=$xhtml->getLink('index.php',$lang->main);
	    $xhtml->createPage($title,$topc,$middlec,$downc);
	}

	$mypost=0;
	$nread=0;
	$db->Query("SELECT * FROM rpg_postbag WHERE reid='".USER_ID."'  ORDER BY id desc limit 20");
	while ($row=$db->FetchAssoc()){
		if ($row['reid']==USER_ID){
			$mypost++;
			if ($row['readit']==1){
				$nread++;
			}
		}
		$sd=explode(' ',$row['sdate']);
		$dm=explode('-',$sd[0]);
		$hm=explode(':',$sd[1]);
		$gt=$dm[1].'-'.$dm[2].' '.$hm[1].':'.$hm[2];
		$remsgsinf.=$xhtml->getLink('index.php?postman&amp;remsgs&amp;view='.$row['id'].'','('.$gt.') '.$row['slogin'].' '.($row['readit']==1?'(new)':'').'').$xhtml->getBR();
		
	}
	if (isset($_GET['remsgs'])){
		$middlec.=$remsgsinf;
		$downc.=$xhtml->getLink('index.php',$lang->main);
	    $xhtml->createPage($lang->myhero,$topc,$middlec,$downc);
	}
	$middlec.=$xhtml->getLink('index.php?postman&amp;remsgs',$lang->resletters.'('.$nread.'/'.$mypost.')').$xhtml->getBR();
	$middlec.=$xhtml->getLink('index.php?postman&amp;send',$lang->sendletter);
	$downc.=$xhtml->getLink('index.php',$lang->main);
	$xhtml->createPage($title,$topc,$middlec,$downc);
}
function dressing($uid,$view=''){
	global $db,$xhtml,$gClass,$lang;
	$topc.=$gClass->getLifeManaLine();
	$h=0;
	$db->Query("SELECT login FROM rpg_users WHERE id='".$uid."'");
	$r=$db->FetchAssoc();
	$uname=$r['login'];
	if (empty($view)){
	if (isset($_GET['remove'])){
		$remove=intval($_GET['remove']);
		$db->Query("SELECT * FROM users_items WHERE usid='".$uid."' AND utid='".$remove."' AND (ittype<20 || ittype=23)");
		if ($db->getAffectedRows()>0){
			$db->Query("UPDATE users_items SET act=0 WHERE usid='".$uid."' AND utid='".$remove."'");
		}
	}
	}
	$db->Query("SELECT * FROM users_items WHERE usid='".$uid."'");
	while ($row=$db->FetchAssoc()){
		
		if ($row['broktime']<time()){
			$breakitem[]=array('id'=>$row['utid'],'name'=>$row['itname'],'lvl'=>$row['lvl'],'img'=>$row['img'],'modit'=>$row['modit'],'type'=>$row['ittype'],'brktime'=>$row['broktime']);
		}
		else{
		
		    $arr=array('id'=>$row['utid'],'name'=>$row['itname'],'lvl'=>$row['lvl'],'img'=>$row['img'],'modit'=>$row['modit'],'brktime'=>$row['broktime']);
		}
		switch($row['act']){
			case 1;
			case 23;
			if (!empty($hand1) && $h==1) $hand2=$arr;
			else{
			$hand1=$arr;
			$h++;
			}
			break;
			case 2;
			$head=$arr;
			break;
			case 3;
			$body=$arr;
			break;
			case 4;
			$qveda=$arr;
			break;
			case 5;
			$foot=$arr;
			break;
			case 6;
			$hands=$arr;
			break;
			case 7;
			if (empty($ri))
			    $ring1=$arr;
			if ($ri==1) $ring2=$arr;
			if ($ri==2) $ring3=$arr;
			if ($ri==3) $ring4=$arr;
			$ri++;
			break;
			case 8;
			$amulet=$arr;
			break;
		}
		unset($arr);
	}


		if (!empty($breakitem)){
			
			$gClass->removeBreakItems($uid,$breakitem);
			
		}


		$middlec.=$lang->dressing.':'.$xhtml->getLink('index.php?person&amp;u='.$uid.'',$uname).$xhtml->getBR().'----'.$xhtml->getBR();
		$middlec.=$lang->head.':'.(!empty($head)?$xhtml->getLink('index.php?items&amp;id='.$head['id'].'',$head['name'].'['.$head['lvl'].']'.(!empty($head['modit'])?'(Mod:'.$head['modit'].')':'').'').' '.(empty($view)?'['.$xhtml->getLink('index.php?dressing&amp;remove='.$head['id'].'',$lang->undress).']':''):'').$xhtml->getBR();
		$middlec.=$lang->amulet.':'.(!empty($amulet)?$xhtml->getLink('index.php?items&amp;id='.$amulet['id'].'',$amulet['name'].'['.$amulet['lvl'].']'.(!empty($amulet['modit'])?'(Mod:'.$amulet['modit'].')':'').'').' '.(empty($view)?'['.$xhtml->getLink('index.php?dressing&amp;remove='.$amulet['id'].'',$lang->undress).']':''):'').$xhtml->getBR();
		$middlec.=$lang->armor.':'.(!empty($body)?$xhtml->getLink('index.php?items&amp;id='.$body['id'].'',$body['name'].'['.$body['lvl'].']'.(!empty($body['modit'])?'(Mod:'.$body['modit'].')':'').'').' '.(empty($view)?'['.$xhtml->getLink('index.php?dressing&amp;remove='.$body['id'].'',$lang->undress).']':''):'').$xhtml->getBR();
		$middlec.=$lang->righthend.':'.(!empty($hand1)?$xhtml->getLink('index.php?items&amp;id='.$hand1['id'].'',$hand1['name'].'['.$hand1['lvl'].']'.(!empty($hand1['modit'])?'(Mod:'.$hand1['modit'].')':'').'').' '.(empty($view)?'['.$xhtml->getLink('index.php?dressing&amp;remove='.$hand1['id'].'',$lang->undress).']':''):'').$xhtml->getBR();
		$middlec.=$lang->lefthend.':'.(!empty($hand2)?$xhtml->getLink('index.php?items&amp;id='.$hand2['id'].'',$hand2['name'].'['.$hand2['lvl'].']'.(!empty($hand2['modit'])?'(Mod:'.$hand2['modit'].')':'').'').' '.(empty($view)?'['.$xhtml->getLink('index.php?dressing&amp;remove='.$hand2['id'].'',$lang->undress).']':''):'').$xhtml->getBR();
		$middlec.=$lang->gloves.':'.(!empty($hands)?$xhtml->getLink('index.php?items&amp;id='.$hands['id'].'',$hands['name'].'['.$hands['lvl'].']'.(!empty($hands['modit'])?'(Mod:'.$hands['modit'].')':'').'').' '.(empty($view)?'['.$xhtml->getLink('index.php?dressing&amp;remove='.$hands['id'].'',$lang->undress).']':''):'').$xhtml->getBR();
		$middlec.=$lang->pant.':'.(!empty($qveda)?$xhtml->getLink('index.php?items&amp;id='.$qveda['id'].'',$qveda['name'].'['.$qveda['lvl'].']'.(!empty($qveda['modit'])?'(Mod:'.$qveda['modit'].')':'').'').' '.(empty($view)?'['.$xhtml->getLink('index.php?dressing&amp;remove='.$qveda['id'].'',$lang->undress).']':''):'').$xhtml->getBR();
		$middlec.=$lang->shoes.':'.(!empty($foot)?$xhtml->getLink('index.php?items&amp;id='.$foot['id'].'',$foot['name'].'['.$foot['lvl'].']'.(!empty($foot['modit'])?'(Mod:'.$foot['modit'].')':'').'').' '.(empty($view)?'['.$xhtml->getLink('index.php?dressing&amp;remove='.$foot['id'].'',$lang->undress).']':''):'').$xhtml->getBR();
		$middlec.=$lang->ring.' 1:'.(!empty($ring1)?$xhtml->getLink('index.php?items&amp;id='.$ring1['id'].'',$ring1['name'].'['.$ring1['lvl'].']'.(!empty($ring1['modit'])?'(Mod:'.$ring1['modit'].')':'').'').' '.(empty($view)?'['.$xhtml->getLink('index.php?dressing&amp;remove='.$ring1['id'].'',$lang->undress).']':''):'').$xhtml->getBR();
		$middlec.=$lang->ring.' 2:'.(!empty($ring2)?$xhtml->getLink('index.php?items&amp;id='.$ring2['id'].'',$ring2['name'].'['.$ring2['lvl'].']'.(!empty($ring2['modit'])?'(Mod:'.$ring2['modit'].')':'').'').' '.(empty($view)?'['.$xhtml->getLink('index.php?dressing&amp;remove='.$ring2['id'].'',$lang->undress).']':''):'').$xhtml->getBR();
		$middlec.=$lang->ring.' 3:'.(!empty($ring3)?$xhtml->getLink('index.php?items&amp;id='.$ring3['id'].'',$ring3['name'].'['.$ring3['lvl'].']'.(!empty($ring3['modit'])?'(Mod:'.$ring3['modit'].')':'').'').' '.(empty($view)?'['.$xhtml->getLink('index.php?dressing&amp;remove='.$ring3['id'].'',$lang->undress).']':''):'').$xhtml->getBR();
		$middlec.=$lang->ring.' 4:'.(!empty($ring4)?$xhtml->getLink('index.php?items&amp;id='.$ring4['id'].'',$ring4['name'].'['.$ring4['lvl'].']'.(!empty($ring4['modit'])?'(Mod:'.$ring4['modit'].')':'').'').' '.(empty($view)?'['.$xhtml->getLink('index.php?dressing&amp;remove='.$ring4['id'].'',$lang->undress).']':''):'').$xhtml->getBR();
		$middlec.=$gClass->downMenu();
		if (!empty($view) && !empty($_SESSION['location'])){
		$downc.='Ukan:'.$xhtml->getLink($_SESSION['location'][0],$_SESSION['location'][1]).$xhtml->getBR();
	    }
	    $downc.=$xhtml->getLink('index.php',$lang->main);
		$xhtml->createPage((empty($view)?$lang->myhero:$lang->dressing.' '.$uname),$topc,$middlec,$downc);
	    exit;
}
function startBotBattle($bid){
	global $db,$xhtml;
	$bid=intval($bid);
	$rbid=$bid;
	if (!empty($_SESSION['lookbots'])){
	if (in_array($bid,$_SESSION['lookbots']) AND USER_ACT==0){
		$db->Query("SELECT * FROM rpg_realbots WHERE bid='".$bid."'");
		$rbot=$db->FetchAssoc();
		/*
		$gp=(USER_LVL+1)*(1+(USER_LVL/20));
		if (USER_LVL<8)
		    $gpexp=USER_LVL+1.4;
		else $gpexp=USER_LVL+1;
		*/
		//$gp=(11+USER_LVL)/10;
		$gp=1*(1+USER_LVL/30);
		$coef=rand(1,5);
		$cfdp=(10+$coef)/10;
		if ($rbot['level']<(USER_LVL-1)){
		    $dp=($rbot['level']+1)/(USER_LVL+1);
		}
		else{
			$dp=$cfdp;
		}
		
		$bot['strength']=round($rbot['strength']*$gp);
		$bot['accuracy']=round($rbot['accuracy']*$gp);
		$bot['dexterity']=round($rbot['dexterity']*$gp);
		$bot['blocking']=round($rbot['blocking']*$gp);
		$bot['ant_accuracy']=round($rbot['ant_accuracy']*$gp);
		$bot['ant_dexterity']=round($rbot['ant_dexterity']*$gp);
		$bot['ant_blocking']=round($rbot['ant_blocking']*$gp);
		$bot['life']=round($rbot['life']*$gp);
		$bot['mana']=round($rbot['mana']*$gp);
		$bot['bron']=round($rbot['bron']*$gp);
		$bot['intelect']=round($rbot['intelect']*$gp);
		$bot['get_exp']=round(($rbot['get_exp']*$dp),2);
		$bot['get_drop']=round(($rbot['get_drop']*$dp),2);
		/*
		print_r($bot);
		exit;
		*/
		if ($rbot['agres']>0){
			$n=rand(1,3);
		}
		else $n=1;
		while ($n>0){
		    $db->Query("INSERT INTO rpg_bots SET rbid='".$rbot['bid']."', name='".$rbot['name']."', level='".USER_LVL."', place='".$rbot['place']."',
			strength='".$bot['strength']."', accuracy='".$bot['accuracy']."', dexterity='".$bot['dexterity']."', blocking='".$bot['blocking']."',
			ant_accuracy='".$bot['ant_accuracy']."', ant_dexterity='".$bot['ant_dexterity']."', ant_blocking='".$bot['ant_blocking']."',
			life='".$bot['life']."', rlife='".$bot['life']."', intelect='".$bot['intelect']."', mana='".$bot['mana']."', bron='".$bot['bron']."', get_exp='".$bot['get_exp']."', get_drop='".$bot['get_drop']."', act='1'");
			$bid=$db->lastInsertID();
		    if ($w!=1){
		        $fbid;
		        $db->Query("INSERT INTO rpg_botbattles SET userID='".USER_ID."', botID='".$bid."', sdate=NOW(), btime='".BOT_BATTLE_TIME."', type='".$rbid."', status=1");
		        $insID=$db->lastInsertID();
		    }
		    $db->Query("INSERT INTO rpg_botbattlesaction SET bid='".$insID."', botID='".$bid."', sdate=NOW(), status=1");
		    $w=1;
		    $n--;
		}
		$db->Query("INSERT INTO rpg_botbattlesaction SET bid='".$insID."', userID='".USER_ID."', login='".USER_NAME."', lvl='".USER_LVL."', sdate=NOW(), acttime='".time()."', status=1");
		$db->Query("UPDATE rpg_users SET act='B_".$insID."' where id='".USER_ID."'");
		$db->Query("UPDATE rpg_bots SET act='".$insID."' where bid='".$bid."'");
		unset($_SESSION['lookbots']);
		$xhtml->redirect('index.php');
	}
	}
	$xhtml->redirect('index.php');
}
function startInstantBattle($users,$type){
	global $db,$lang;
	$n=0;
	$lvls=0;
	foreach ($users as $value){
		if ($lvls<$value['lvl']){
		    $lvls=$value['lvl'];
		}
	}
	$coef=$lvls;
	if ($type==1001){
		$bots[0]=array('name'=>$lang->rat,'strength'=>35,'accuracy'=>25,'dexterity'=>25,'blocking'=>25,'ant_accuracy'=>30,'ant_dexterity'=>30,'ant_blocking'=>30,'life'=>250,'rlife'=>250,'bron'=>5,'get_exp'=>450,'get_drop'=>3);
		$bots[1]=array('name'=>$lang->rat,'strength'=>35,'accuracy'=>25,'dexterity'=>25,'blocking'=>25,'ant_accuracy'=>30,'ant_dexterity'=>30,'ant_blocking'=>30,'life'=>250,'rlife'=>250,'bron'=>5,'get_exp'=>450,'get_drop'=>3);
		$bots[2]=array('name'=>$lang->rat,'strength'=>35,'accuracy'=>25,'dexterity'=>25,'blocking'=>25,'ant_accuracy'=>30,'ant_dexterity'=>30,'ant_blocking'=>30,'life'=>250,'rlife'=>250,'bron'=>5,'get_exp'=>450,'get_drop'=>3);
		$bots[3]=array('name'=>$lang->rat,'strength'=>35,'accuracy'=>25,'dexterity'=>25,'blocking'=>25,'ant_accuracy'=>30,'ant_dexterity'=>30,'ant_blocking'=>30,'life'=>250,'rlife'=>250,'bron'=>5,'get_exp'=>450,'get_drop'=>3);
		$bots[4]=array('name'=>$lang->rat,'strength'=>35,'accuracy'=>25,'dexterity'=>25,'blocking'=>25,'ant_accuracy'=>30,'ant_dexterity'=>30,'ant_blocking'=>30,'life'=>250,'rlife'=>250,'bron'=>5,'get_exp'=>450,'get_drop'=>3);
		$bots[5]=array('name'=>$lang->ghost,'strength'=>45,'accuracy'=>35,'dexterity'=>35,'blocking'=>35,'ant_accuracy'=>40,'ant_dexterity'=>40,'ant_blocking'=>40,'life'=>500,'rlife'=>500,'bron'=>10,'get_exp'=>1500,'get_drop'=>7);
	}
	if ($type==1002){
		$bots[0]=array('name'=>$lang->skeleton,'strength'=>70,'accuracy'=>50,'dexterity'=>50,'blocking'=>50,'ant_accuracy'=>90,'ant_dexterity'=>90,'ant_blocking'=>90,'life'=>600,'rlife'=>600,'bron'=>30,'get_exp'=>900,'get_drop'=>6);
		$bots[1]=array('name'=>$lang->skeleton,'strength'=>70,'accuracy'=>50,'dexterity'=>50,'blocking'=>50,'ant_accuracy'=>90,'ant_dexterity'=>90,'ant_blocking'=>90,'life'=>600,'rlife'=>600,'bron'=>30,'get_exp'=>900,'get_drop'=>6);
		$bots[2]=array('name'=>$lang->skeleton,'strength'=>70,'accuracy'=>50,'dexterity'=>50,'blocking'=>50,'ant_accuracy'=>90,'ant_dexterity'=>90,'ant_blocking'=>90,'life'=>600,'rlife'=>600,'bron'=>30,'get_exp'=>900,'get_drop'=>6);
		$bots[3]=array('name'=>$lang->skeleton,'strength'=>70,'accuracy'=>50,'dexterity'=>50,'blocking'=>50,'ant_accuracy'=>90,'ant_dexterity'=>90,'ant_blocking'=>90,'life'=>600,'rlife'=>600,'bron'=>30,'get_exp'=>900,'get_drop'=>6);
		$bots[4]=array('name'=>$lang->skeleton,'strength'=>70,'accuracy'=>50,'dexterity'=>50,'blocking'=>50,'ant_accuracy'=>90,'ant_dexterity'=>90,'ant_blocking'=>90,'life'=>600,'rlife'=>600,'bron'=>30,'get_exp'=>900,'get_drop'=>6);
		$bots[5]=array('name'=>$lang->skeleton,'strength'=>70,'accuracy'=>50,'dexterity'=>50,'blocking'=>50,'ant_accuracy'=>90,'ant_dexterity'=>90,'ant_blocking'=>90,'life'=>600,'rlife'=>600,'bron'=>30,'get_exp'=>900,'get_drop'=>6);
		$bots[6]=array('name'=>$lang->skeleton,'strength'=>70,'accuracy'=>50,'dexterity'=>50,'blocking'=>50,'ant_accuracy'=>90,'ant_dexterity'=>90,'ant_blocking'=>90,'life'=>600,'rlife'=>600,'bron'=>30,'get_exp'=>900,'get_drop'=>6);
		$bots[7]=array('name'=>$lang->skeleton,'strength'=>70,'accuracy'=>50,'dexterity'=>50,'blocking'=>50,'ant_accuracy'=>90,'ant_dexterity'=>90,'ant_blocking'=>90,'life'=>600,'rlife'=>600,'bron'=>30,'get_exp'=>900,'get_drop'=>6);
		$bots[8]=array('name'=>$lang->skeleton,'strength'=>70,'accuracy'=>50,'dexterity'=>50,'blocking'=>50,'ant_accuracy'=>90,'ant_dexterity'=>90,'ant_blocking'=>90,'life'=>600,'rlife'=>600,'bron'=>30,'get_exp'=>900,'get_drop'=>6);
		$bots[9]=array('name'=>$lang->skeleton,'strength'=>70,'accuracy'=>50,'dexterity'=>50,'blocking'=>50,'ant_accuracy'=>90,'ant_dexterity'=>90,'ant_blocking'=>90,'life'=>600,'rlife'=>600,'bron'=>30,'get_exp'=>900,'get_drop'=>6);
		$bots[10]=array('name'=>$lang->knigthskeleton,'strength'=>90,'accuracy'=>60,'dexterity'=>60,'blocking'=>60,'ant_accuracy'=>100,'ant_dexterity'=>100,'ant_blocking'=>100,'life'=>1500,'rlife'=>1500,'bron'=>50,'get_exp'=>2250,'get_drop'=>15);
	}
	if ($type==1003){
		$bots[0]=array('name'=>$lang->varg,'strength'=>85,'accuracy'=>70,'dexterity'=>70,'blocking'=>70,'ant_accuracy'=>150,'ant_dexterity'=>150,'ant_blocking'=>150,'life'=>1500,'rlife'=>1500,'bron'=>60,'get_exp'=>1800,'get_drop'=>12);
		$bots[1]=array('name'=>$lang->varg,'strength'=>85,'accuracy'=>70,'dexterity'=>70,'blocking'=>70,'ant_accuracy'=>150,'ant_dexterity'=>150,'ant_blocking'=>150,'life'=>1500,'rlife'=>1500,'bron'=>60,'get_exp'=>1800,'get_drop'=>12);
		$bots[2]=array('name'=>$lang->varg,'strength'=>85,'accuracy'=>70,'dexterity'=>70,'blocking'=>70,'ant_accuracy'=>150,'ant_dexterity'=>150,'ant_blocking'=>150,'life'=>1500,'rlife'=>1500,'bron'=>60,'get_exp'=>1800,'get_drop'=>12);
		$bots[3]=array('name'=>$lang->varg,'strength'=>85,'accuracy'=>70,'dexterity'=>70,'blocking'=>70,'ant_accuracy'=>150,'ant_dexterity'=>150,'ant_blocking'=>150,'life'=>1500,'rlife'=>1500,'bron'=>60,'get_exp'=>1800,'get_drop'=>12);
		$bots[4]=array('name'=>$lang->varg,'strength'=>85,'accuracy'=>70,'dexterity'=>70,'blocking'=>70,'ant_accuracy'=>150,'ant_dexterity'=>150,'ant_blocking'=>150,'life'=>1500,'rlife'=>1500,'bron'=>60,'get_exp'=>1800,'get_drop'=>12);
		$bots[5]=array('name'=>$lang->varg,'strength'=>85,'accuracy'=>70,'dexterity'=>70,'blocking'=>70,'ant_accuracy'=>150,'ant_dexterity'=>150,'ant_blocking'=>150,'life'=>1500,'rlife'=>1500,'bron'=>60,'get_exp'=>1800,'get_drop'=>12);
		$bots[6]=array('name'=>$lang->varg,'strength'=>85,'accuracy'=>70,'dexterity'=>70,'blocking'=>70,'ant_accuracy'=>150,'ant_dexterity'=>150,'ant_blocking'=>150,'life'=>1500,'rlife'=>1500,'bron'=>60,'get_exp'=>1800,'get_drop'=>12);
		$bots[7]=array('name'=>$lang->varg,'strength'=>85,'accuracy'=>70,'dexterity'=>70,'blocking'=>70,'ant_accuracy'=>150,'ant_dexterity'=>150,'ant_blocking'=>150,'life'=>1500,'rlife'=>1500,'bron'=>60,'get_exp'=>1800,'get_drop'=>12);
		$bots[8]=array('name'=>$lang->varg,'strength'=>85,'accuracy'=>70,'dexterity'=>70,'blocking'=>70,'ant_accuracy'=>150,'ant_dexterity'=>150,'ant_blocking'=>150,'life'=>1500,'rlife'=>1500,'bron'=>60,'get_exp'=>1800,'get_drop'=>12);
		$bots[9]=array('name'=>$lang->varg,'strength'=>85,'accuracy'=>70,'dexterity'=>70,'blocking'=>70,'ant_accuracy'=>150,'ant_dexterity'=>150,'ant_blocking'=>150,'life'=>1500,'rlife'=>1500,'bron'=>60,'get_exp'=>1800,'get_drop'=>12);
		$bots[10]=array('name'=>$lang->bossvarg,'strength'=>110,'accuracy'=>90,'dexterity'=>90,'blocking'=>90,'ant_accuracy'=>210,'ant_dexterity'=>210,'ant_blocking'=>210,'life'=>3000,'rlife'=>3000,'bron'=>80,'get_exp'=>3500,'get_drop'=>30);
	}
	if ($type==1004){
	    $bots[0]=array('name'=>$lang->whitedragon,'strength'=>150,'accuracy'=>100,'dexterity'=>100,'blocking'=>100,'ant_accuracy'=>210,'ant_dexterity'=>210,'ant_blocking'=>210,'life'=>5000,'rlife'=>5000,'bron'=>90,'get_exp'=>10000,'get_drop'=>60);
        $bots[1]=array('name'=>$lang->whitedragon,'strength'=>150,'accuracy'=>100,'dexterity'=>100,'blocking'=>100,'ant_accuracy'=>210,'ant_dexterity'=>210,'ant_blocking'=>210,'life'=>5000,'rlife'=>5000,'bron'=>90,'get_exp'=>10000,'get_drop'=>60);
	    $bots[2]=array('name'=>$lang->whitedragon,'strength'=>150,'accuracy'=>100,'dexterity'=>100,'blocking'=>100,'ant_accuracy'=>210,'ant_dexterity'=>210,'ant_blocking'=>210,'life'=>5000,'rlife'=>5000,'bron'=>90,'get_exp'=>10000,'get_drop'=>60);
	    $bots[3]=array('name'=>$lang->reddragon,'strength'=>200,'accuracy'=>120,'dexterity'=>120,'blocking'=>120,'ant_accuracy'=>230,'ant_dexterity'=>230,'ant_blocking'=>230,'life'=>10000,'rlife'=>10000,'bron'=>110,'get_exp'=>22000,'get_drop'=>150);
	}
	if ($type==1005){
	    $bots[0]=array('name'=>$lang->firewolf,'strength'=>250,'accuracy'=>250,'dexterity'=>0,'blocking'=>0,'ant_accuracy'=>100,'ant_dexterity'=>300,'ant_blocking'=>300,'life'=>5000,'rlife'=>5000,'bron'=>100,'get_exp'=>20000,'get_drop'=>120);
        $bots[1]=array('name'=>$lang->firewolf,'strength'=>300,'accuracy'=>0,'dexterity'=>250,'blocking'=>0,'ant_accuracy'=>300,'ant_dexterity'=>100,'ant_blocking'=>300,'life'=>6000,'rlife'=>6000,'bron'=>120,'get_exp'=>20000,'get_drop'=>120);
	    $bots[2]=array('name'=>$lang->firewolf,'strength'=>320,'accuracy'=>0,'dexterity'=>0,'blocking'=>250,'ant_accuracy'=>300,'ant_dexterity'=>300,'ant_blocking'=>100,'life'=>7000,'rlife'=>7000,'bron'=>150,'get_exp'=>20000,'get_drop'=>120);
	    $bots[3]=array('name'=>$lang->firewolfboss,'strength'=>400,'accuracy'=>250,'dexterity'=>250,'blocking'=>250,'ant_accuracy'=>250,'ant_dexterity'=>250,'ant_blocking'=>250,'life'=>10000,'rlife'=>10000,'bron'=>170,'get_exp'=>50000,'get_drop'=>300);
	}
	foreach ($bots as $key=>$value){
		foreach ($value as $k=>$v){
			if ($k!='name' && $k!='get_exp' && $k!='get_drop'){
				$v=round(($v*1.2));
			}
		    $q.="".$k."='".$v."', ";
		}
		$db->Query("INSERT INTO rpg_bots SET ".$q." act=1");
		unset($q);
		$bid=$db->lastInsertID();
		if ($w!=1){
		    $fbid;
		    $db->Query("INSERT INTO rpg_botbattles SET userID='".USER_ID."', botID='".$bid."', sdate=NOW(), btime='".BOT_BATTLE_TIME."', status=1, type='".$type."'");
		    $insID=$db->lastInsertID();
		}
		$db->Query("INSERT INTO rpg_botbattlesaction SET bid='".$insID."', botID='".$bid."', sdate=NOW(), status=1");
		$w=1;
	}
	foreach ($users as $value){
	         $db->Query("INSERT INTO rpg_botbattlesaction SET bid='".$insID."', userID='".$value['id']."', login='".$value['login']."', lvl='".$value['lvl']."', sdate=NOW(), acttime='".time()."', status=1");
	         $db->Query("UPDATE rpg_users SET wbattle=0, last_inst='".time()."', act='B_".$insID."' where id='".$value['id']."'");
	}
	$db->Query("UPDATE rpg_bots SET act='".$insID."' where bid='".$bid."'");
}
function duelBattle($bid){
	//status
	//0 sheqmnili zaiavka
	//1-mimdinare brdozla
	//2-brdzola dasrulda timeoutis gamo
	//3-brdzola ar daicyo mocinaagmdegis ar yofnis gamo
	//4-brdzola dasrulda chveulebriv
	global $db,$xhtml,$ses,$gClass,$lang;
	$topc.=$gClass->getLifeManaLine();
	$db->Query("SELECT * FROM rpg_userduelbattles WHERE id='".$bid."' AND (user1='".USER_ID."' OR user2='".USER_ID."') AND status=1");
	if ($db->getAffectedRows()>0){
		$statusRow=$db->FetchAssoc();

		if (($statusRow['acttime']+$statusRow['btime'])<time()){
			if ($statusRow['hit1']>0 && $statusRow['hit2']==0){
				$winner=1;
			}
			if ($statusRow['hit2']>0 && $statusRow['hit1']==0){
				$winner=2;
			}
			if ($statusRow['hit2']==0 && $statusRow['hit2']==0){
				$winner=3;
			}
			/*
			$db->Query("UPDATE rpg_userduelbattles SET status=2, winer='".$winner."' WHERE id='".$bid."'");
			$db->Query("UPDATE rpg_users set act=0, viewb='D_".$bid."', last_update='".time()."' WHERE id='".$statusRow['user1']."' OR id='".$statusRow['user2']."'");
			$xhtml->redirect('index.php?arena','1');
			*/
		}

		if (USER_ID==$statusRow['user1']) {
		    $myP=1;
		    $opP=2;
		}
		else {
			$myP=2;
			$opP=1;
		}
		if (isset($_POST['drink']) && intval($_POST['el'])>0){
            	$utid=intval($_POST['el']);
            	$db->Query("SELECT * FROM users_items WHERE utid='".$utid."' AND ittype=20 AND usid='".$_SESSION['user_id']."'");
            	if ($elrow=$db->FetchAssoc()){
            		$gClass->addLifeMana(USER_ID,$elrow['rest_life'],$elrow['rest_mana']);
            		if ($elrow['rest_life']>0) {
            		   $log=' '.$lang->relife;
            		   $point=$elrow['rest_life'];
            		}
            		if ($elrow['rest_mana']>0) {
            		    $log=' Sheivso mana';
            		    $point=$elrow['rest_mana'];
            		}
            		$db->Query("DELETE FROM users_items WHERE utid='".$utid."' AND usid='".USER_ID."'");
            		$db->Query("INSERT INTO rpg_duelbattleslogs SET bid='".$bid."', user".$myP."='".USER_ID."', description='".$log."', repoint='".$point."', acttime=NOW()");
            		redirect('index.php',1);
            	}
        }
		$db->Query("SELECT * FROM rpg_users WHERE id='".$statusRow['user'.$opP.'']."'");
		while ($users=$db->FetchAssoc()){
			if ($statusRow['user'.$opP.'']==$users['id']){
				$user[$opP]['ID']=$statusRow['user'.$opP.''];
				$user[$opP]['Login']=$users['login'];
				$user[$opP]['Life']=$users['life'];
				$user[$opP]['hitp']=$statusRow['hit'.$opP.''];
				//$user[$opP]['rate']=$gClass->countStatsRate($users);
			}
			//if ($statusRow['user'.$myP.'']==$users['id']){
				$user[$myP]['ID']=USER_ID;
				$user[$myP]['Login']=USER_NAME;
				$user[$myP]['Life']=NOW_LIFE;
				$user[$myP]['hitp']=$statusRow['hit'.$myP.''];
				//$user[$myP]['rate']=$gClass->countOwnStatsRate();
			//}
		}
		if (!empty($_POST['action']) && (($_SESSION['hitact']+3)<time())){
	    	$_SESSION['hitact']=time();
			$fuser[$myP]['ID']=$user[$myP]['ID'];
			$fuser[$opP]['ID']=$user[$opP]['ID'];
			$gClass->doFight($bid,$fuser,$myP,$opP);
			$xhtml->redirect('index.php?arena','1');
		}
		$middlec.=$xhtml->getBlock('TimeOut| '.$gClass->retMinSec(($statusRow['acttime']+$statusRow['btime']-time())),'timeOut');
		$middlec.=$user[1]['Login'].'['.$user[1]['Life'].'] VS '.$user[2]['Login'].'['.$user[2]['Life'].']'.$xhtml->getBR();
		if ($user[$myP]['hitp']==0){
		    //$db->Query("SELECT * FROM rpg_duelbattlesaction WHERE bid='".$bid."'");
		    $fc.=$xhtml->startForm('index.php?'.$ses.'');
		    $fc.=$xhtml->getInput('action',$lang->hit,'submit');
		    $db->Query("SELECT * FROM users_items WHERE ittype=20 AND usid='".$_SESSION['user_id']."' GROUP BY itid");
		    if ($db->getAffectedRows()>0){
		        while ($marr=$db->FetchAssoc()){
		        	   $mgarr[$marr['utid']]=$marr['itname'];
		        }
		        $fc.=$xhtml->getBR().$lang->elexirs.$xhtml->getBR().$xhtml->getSelect('el',$mgarr).$xhtml->getBR();
		        $fc.=$xhtml->getInput('drink',$lang->drink,'submit');
		    }
		    $fc.=$xhtml->endForm();
		    $middlec.=$xhtml->getBlock($fc,'fightact');
		}
		else $middlec.=$ang->waitingoponent.$xhtml->getBR();
		$middlec.=$xhtml->getBlock($gClass->showDuelBattleLog($bid),'acz');
	}
	$xhtml->createPage($title,$topc,$middlec,$downc);
}
function groupBattle($id){
	global $xhtml,$db,$gClass,$lang;
	$topc.=$gClass->getLifeManaLine();
	$db->Query("SELECT * FROM rpg_groupbattle WHERE id='".$id."' AND status=1");
	if ($db->getAffectedRows()==1){
		$db->Query("SELECT * FROM rpg_gbusers WHERE gid='".$id."'");
		while ($row=$db->FetchAssoc()){
			if ($row['team']==1) {
			    if ($row['userID']>0) {
			        $team1[$row['userID']]=array('login'=>$row['login'],'lvl'=>$row['lvl'],'status'=>$row['status']);
			        if ($row['status']==0) $actteam1[$row['userID']]=array('login'=>$row['login'],'lvl'=>$row['lvl']);
			    }
			    if ($row['userBot']>0) {
			        $bots1[$row['userBot']]=array('login'=>$row['login'],'lvl'=>$row['lvl'],'status'=>$row['status']);
			        if ($row['status']==0) {
			        	$actbots1[$row['userBot']]=array('login'=>$row['login'],'lvl'=>$row['lvl'],'status'=>$row['status'],'lasthit'=>$row['lasthit']);
			        }
			    }
			    if ($row['userID']==USER_ID) { $myteam=1; $opteam=2; $mystatus=$row['status'];}
			}
			if ($row['team']==2) {
			    if ($row['userID']>0) {
			        $team2[$row['userID']]=array('login'=>$row['login'],'lvl'=>$row['lvl'],'status'=>$row['status']);
			        if ($row['status']==0) $actteam2[$row['userID']]=array('login'=>$row['login'],'lvl'=>$row['lvl']);
			    }
   			    if ($row['userBot']>0) {
   			        $bots2[$row['userBot']]=array('login'=>$row['login'],'lvl'=>$row['lvl'],'status'=>$row['status']);
   			        if ($row['status']==0) {
			        	$actbots2[$row['userBot']]=array('login'=>$row['login'],'lvl'=>$row['lvl'],'status'=>$row['status'],'lasthit'=>$row['lasthit']);
			        }
   			    }
   			    if ($row['userID']==USER_ID) { $myteam=2; $opteam=1; $mystatus=$row['status'];}
			}
		}
		if (empty($actteam1) && empty($actbots1)) $alld1=1;
		if (empty($actteam2) && empty($actbots2)) $alld2=1;
		if ($alld1==1 && empty($alld2)){
			$gClass->groupBattleEnd($id,2);
			exit;
		}
		if ($alld2==1 && empty($alld1)){
			$gClass->groupBattleEnd($id,1);
			exit;
		}
		if ($alld1==1 && $alld2==1){
			$gClass->groupBattleEnd($id,3);
			exit;
		}

		foreach ($team1 as $key=>$value){
			if ($value['status']==1) {
				$tus1.=$xhtml->getSpan($value['login'].'['.$value['lvl'].']');
			}
			else{
				$tus1.=$xhtml->getLink('index.php?person&amp;u='.$key.'',$value['login'].'['.$value['lvl'].']',$cstyle).' ';
			}
		}
		if (!empty($bots1)){
		foreach ($bots1 as $key=>$value){
			if ($value['status']==1) {
				$tus1.=$xhtml->getSpan($value['login'].'['.$value['lvl'].']');
			}
			else{
				$tus1.=$xhtml->getSpan($value['login'].'['.$value['lvl'].']','color_red');
				//$tus1.=$xhtml->getLink('index.php?person&amp;u='.$key.'',$value['login'].'['.$value['lvl'].']',$cstyle).' ';
			}
		}
		}
		foreach ($team2 as $key=>$value){
			if ($value['status']==1) {
				$tus2.=$xhtml->getSpan($value['login'].'['.$value['lvl'].']');
			}
			else{
				$tus2.=$xhtml->getLink('index.php?person&amp;u='.$key.'',$value['login'].'['.$value['lvl'].']',$cstyle).' ';
			}
		}
		if (!empty($bots2)){
		foreach ($bots2 as $key=>$value){
			if ($value['status']==1) {
				$tus2.=$xhtml->getSpan($value['login'].'['.$value['lvl'].']');
			}
			else{
				$tus2.=$xhtml->getSpan($value['login'].'['.$value['lvl'].']','color_red');
				//$tus2.=$xhtml->getLink('index.php?person&amp;u='.$key.'',$value['login'].'['.$value['lvl'].']',$cstyle).' ';
			}
		}
		}
		if(rand(1,2)==1){
		if (!empty($actbots1)){
			$bot1=array_rand($actbots1);
			if ($actbots1[$bot1]['lasthit']<(time()-rand(2,6))){
				if (!empty($actteam2)){
				      $opus=array_rand($actteam2);
				      $gClass->doFightWithBot($id,$bot1,$opus,0);
			        }
			        else{
				      if (!empty($actbots2)){
					      $bot2=array_rand($actbots2);
					      $gClass->doFightWithBot($id,$bot1,0,$bot2);
				      }
					}
			}
			unset($bot1);
			unset($bot2);
			unset($opus);
		}
		}
	//	print_r($actbots1);
	else{
		if (!empty($actbots2)){
			$bot2=array_rand($actbots2);
			if ($actbots2[$bot2]['lasthit']<(time()-rand(2,6))){
					if (!empty($actteam1)){
			        	$opus=array_rand($actteam1);
				        $gClass->doFightWithBot($id,$bot2,$opus,0);
			        }
			        else{
				if (!empty($actbots1)){
					$bot1=array_rand($actbots1);
					$gClass->doFightWithBot($id,$bot2,0,$bot1);
				}
					}
				
			}
		}
			unset($bot1);
			unset($bot2);
			unset($opus);
	}
		$middlec.=$xhtml->getBlock($tus1.' vs '.$tus2,'b');
		if ($mystatus==0){
			if (isset($_GET['callanimal'])){
				if (ANIMAL>0){
            		$db->Query("SELECT * FROM rpg_usersanimals WHERE usid='".USER_ID."' AND replete>=(alevel*fmeal) AND life>0 AND act='0'");
            		if ($db->getAffectedRows()==1){
            			$brow=$db->FetchAssoc();
            			$brate=$gClass->countStatsRate($brow);
            			$db->Query("INSERT INTO rpg_gbusers SET gid='".$id."',  userBot='".$brow['aid']."', login='".$brow['aname']."(".$brow['mainname'].")', lvl='".$brow['alevel']."', team='".$myteam."', status='0', rate='".$brate."', lasthit='".time()."'");
            			//$bins=$db->lastInsertID();
            			$db->Query("UPDATE rpg_usersanimals SET act='".ACT_TYPE."_".USER_ACT."' WHERE aid='".$brow['aid']."'");
            			$db->Query("INSERT INTO rpg_groupbattleslogs SET bid='".$id."', user1='".USER_ID."', description='".USER_NAME." ".$lang->helped." ".$brow['aname']."(".$brow['mainname'].")', acttime=NOW()");
            			$xhtml->redirect('index.php');
            		}
            		else{
            			$middlec.=$lang->animalfigthalert.$xhtml->getBR();
            		}
            	}
			}
			if (isset($_POST['drink']) && intval($_POST['el'])>0){
            	$utid=intval($_POST['el']);
            	$db->Query("SELECT * FROM users_items WHERE utid='".$utid."' AND ittype=20 AND usid='".$_SESSION['user_id']."'");
            	if ($elrow=$db->FetchAssoc()){
            		$gClass->addLifeMana(USER_ID,$elrow['rest_life'],$elrow['rest_mana']);
            		if ($elrow['rest_life']>0) {
            		   $log=' '.$lang->relife;
            		   $point=$elrow['rest_life'];
            		}
            		if ($elrow['rest_mana']>0) {
            		    $log=' Sheivso mana';
            		    $point=$elrow['rest_mana'];
            		}
            		$db->Query("DELETE FROM users_items WHERE utid='".$utid."' AND usid='".USER_ID."'");
            		$db->Query("INSERT INTO rpg_groupbattleslogs SET bid='".$id."', user1='".USER_ID."', player1='".USER_NAME."', description='".$log."', repoint='".$point."', acttime=NOW()");
            		redirect('index.php',1);
            	}
        }
		if ($myteam==1) $getop=$actteam2; 
		else $getop=$actteam1; 
        if (!empty($getop)){
		$opID=array_rand($getop);
		$_SESSION['opID']=$opID;
		$db->Query("SELECT life FROM rpg_users WHERE id='".$opID."'");
		$rl=$db->FetchAssoc();
		$middlec.=$lang->oponent.':'.$getop[$opID]['login'].'['.$getop[$opID]['lvl'].']('.$rl['life'].')';
		}
		$fc.=$xhtml->startForm('index.php?'.$ses.'');
		$fc.=$xhtml->getInput('action',$lang->hit,'submit');
		$db->Query("SELECT * FROM users_items WHERE ittype=20 AND usid='".$_SESSION['user_id']."' GROUP BY itid");
		if ($db->getAffectedRows()>0){
		    while ($marr=$db->FetchAssoc()){
		           $mgarr[$marr['utid']]=$marr['itname'];
		    }
		    $fc.=$xhtml->getBR().$lang->elexirs.':'.$xhtml->getBR().$xhtml->getSelect('el',$mgarr).$xhtml->getBR();
		    $fc.=$xhtml->getInput('drink',$lang->drink,'submit').$xhtml->getBR();
	    }
	    if (ANIMAL>0) {
	    	$fc.=$xhtml->getLink('index.php?callanimal',$lang->animalhelp).$xhtml->getBR();
	    }
		$fc.=$xhtml->endForm();
		$middlec.=$xhtml->getBlock($fc,'fightact');
	    if (!empty($_POST['action']) && (($_SESSION['hitact']+5)<time())){
	    	if (!empty($getop)){
	    	    $_SESSION['hitact']=time();
		        $fuser[1]['ID']=USER_ID;
		        $fuser[2]['ID']=$_SESSION['opID'];
		        unset($_SESSION['opID']);
		        $gClass->doFightGroup($id,$fuser,1,2);
		    }
		    $xhtml->redirect('index.php?garena','1');
	    }
		
		}
		else{
			$middlec.=$xhtml->getLink('index.php?garena&amp;'.rand(100,999).'',$xhtml->getImage('icon/re.png',$lang->refresh)).$xhtml->getBR();
			$middlec.=$xhtml->getBlock($lang->ukilledwendbattle,'a');
		}
		$middlec.=$xhtml->getBlock($gClass->showGroupBattleLog($id),'acz');
		
	}
	$xhtml->createPage($title,$topc,$middlec,$downc);
}
function groupArena(){
	global $db,$xhtml,$gClass,$lang;
	$title.=$lang->chaosfigths;
	$top.=$lang->chaosfigths;
	$middlec.=$xhtml->getLink('index.php?garena&amp;'.rand(100,999).'',$xhtml->getImage('icon/re.png',$lang->refresh)).$xhtml->getBR();
	$topc.=$gClass->getLifeManaLine();
	if (WAIT_BATTLE==0){
		if (isset($_GET['acd'])){
			$acd=intval($_GET['acd']);
			if (intval($acd)>0) $gClass->joinToGroupBattle($acd);
			$xhtml->redirect('index.php?garena','1');
		}
		if (isset($_POST['crgb'])){
			$minlvl=(USER_LVL<1?0:(USER_LVL-1));
			$maxlvl=USER_LVL+1;
			$hc=intval($_POST['hc']);
			if ($hc<180) $hc=180;
			if ($hc>600) $hc=600;
			$db->Query("INSERT INTO rpg_groupbattle SET minlvl='".$minlvl."', maxlvl='".$maxlvl."', endtime='".(time()+$hc)."'");
			$insid=$db->lastInsertID();
			$pstats=$gClass->countStats(USER_ID);
	        $statsRate=$gClass->countStatsRate($pstats);
			$db->Query("INSERT INTO rpg_gbusers SET gid='".$insid."', userID='".USER_ID."', login='".USER_NAME."', lvl='".USER_LVL."', rate='".$statsRate."'");
			$db->Query("UPDATE rpg_users SET wbattle='G_".$insid."' WHERE id='".USER_ID."'");
            $db->Query("INSERT INTO rpg_chat SET uid1='".USER_ID."', sender='".USER_NAME."', msg='".$lang->icreatgroupbat." [lvl:".$minlvl."-".$maxlvl."][".($hc/60)." min]', lang='".$_SESSION['lang']."', sdate=NOW()");
			$xhtml->redirect('index.php?garena',1);
		}
		$middlec.=$xhtml->getBlock($lang->creatfigth,'b');
	    $middlec.=$xhtml->startForm('index.php?garena','post');
	    $middlec.=$xhtml->getBlock($lang->starttime.':'.$xhtml->getBR().$xhtml->getSelect('hc',array(180=>'3 '.$lang->inminuts,300=>'5 '.$lang->inminuts,600=>'10 '.$lang->inminuts)).$xhtml->getBR()
	   .$xhtml->getInput('crgb',$lang->creat,'submit')).$xhtml->endForm();
	}
	else{
		$db->Query("SELECT * FROM rpg_groupbattle WHERE id='".WAIT_BATTLE."' AND  endtime<'".time()."' AND status=0");
		if ($db->getAffectedRows()>0){
			$db->Query("SELECT * FROM rpg_gbusers WHERE gid='".WAIT_BATTLE."' order by rate desc");
		    $n=$db->getAffectedRows();
		    if ($n>=4){
		    	while ($r=$db->FetchAssoc()){
		    	    mysql_query("UPDATE rpg_users SET wbattle=0, act='G_".WAIT_BATTLE."' WHERE id='".$r['userID']."'");
		    	    $usrs[]=$r['rate'];
		    	    $usrsids[]=$r['userID'];
		    	}
		    	
		    	$n=count($usrs);
	$s=$n;
	$t=1;
	if (!is_integer(($n/2))){
		$s=$n-1;
		$last=1;
	}
	while ($s!=0){
		if ($t==1)
			$team1[]=$usrsids[($s-1)];
		if ($t==2)
			$team2[]=$usrsids[($s-1)];
		if ($d==1){
			$t--;
			if ($t==0){
				$d=0;
				$t++;
			}
		}
		else{
   		$t++;
   		}
   		
  		if ($t==3) {
  		    $t=2;
  		    $d=1;
  		}
		$s--;
	}
if ($last==1) $team1[].=$usrsids[($n-1)];
		    	
		    	foreach ($team1 as $value){
		    		$db->Query("UPDATE rpg_gbusers SET team='1' WHERE gid='".WAIT_BATTLE."' AND userID='".$value."'");
		    	}
		    	
		    	foreach ($team2 as $value){
		    		$db->Query("UPDATE rpg_gbusers SET team='2' WHERE gid='".WAIT_BATTLE."' AND userID='".$value."'");
		    	}
		    	$db->Query("UPDATE rpg_groupbattle SET status=1 WHERE id='".WAIT_BATTLE."'");
		    	$xhtml->redirect('index.php',1);
		    	
		    }
		    else{
		    	while ($r=$db->FetchAssoc()){
		    	    mysql_query("UPDATE rpg_users SET wbattle=0 WHERE id='".$r['userID']."'");
		    	}
		    	$db->Query("UPDATE rpg_groupbattle SET status=3 WHERE id='".WAIT_BATTLE."'");
		    	$xhtml->redirect('index.php?garena');
		    }
		}
		
	}





	$middlec.=$xhtml->getBlock($lang->recalls,'b');
		$db->Query("SELECT * FROM rpg_groupbattle WHERE endtime>'".time()."' AND status=0");
		if ($db->getAffectedRows()>0){
		while ($row=$db->FetchAssoc()){
			$zvk.='[lvl:'.$row['minlvl'].'-'.$row['maxlvl'].'] ['.$gClass->retMinSec(($row['endtime']-time())).']';
			$query=mysql_query("SELECT * FROM rpg_gbusers WHERE gid='".$row['id']."'");
			while($res=mysql_fetch_assoc($query)){
				$zvk.=$xhtml->getLink('index.php?person&amp;u='.$res['userID'].'',$res['login'].' ['.$res['lvl'].']').' ';
			}
			$zvk.=(USER_ID!=$row['userID'] && (USER_ID!=$row['userID']) && (WAIT_BATTLE==0) && ($row['minlvl']<=USER_LVL && $row['maxlvl']>=USER_LVL)?':'.$xhtml->getLink('index.php?garena&amp;acd='.$row['id'].'',$lang->accept):'').'';
			$middlec.=$xhtml->getBlock($zvk,'zvklist');
			$zvk='';
		}
		}
		else{
			$middlec.=$lang->noactrecalls;
		}
	$middlec.=$gClass->placeChat('index.php?garena');
	$downc.=$xhtml->getLink('index.php',$lang->main);
	$xhtml->createPage($title,$topc,$middlec,$downc);
}
function instantGroup(){
	global $xhtml,$db,$gClass,$lang;
	$topc=$gClass->getLifeManaLine();
	if (USER_LVL>=3){
	    $instant[1001]=$lang->instname1.' [3-5] lvl';
	    if (USER_LVL>=5){
	        $instant[1002]=$lang->instname2.' [5-7] lvl';
	        if (USER_LVL>=7){
	            $instant[1003]=$lang->instname3.' [7-9] lvl';
	            if (USER_LVL>=9){
	                $instant[1004]=$lang->instname4.' [9-11] lvl';
	                if (USER_LVL>=11){
	                	$instant[1005]=$lang->instname5.' [11-13] lvl';
	                }
             	}
	        }
	    }
	}
	$instext[1]=array('name'=>$lang->instname1,'lvl'=>3);
	$instext[2]=array('name'=>$lang->instname2,'lvl'=>5);
	$instext[3]=array('name'=>$lang->instname3,'lvl'=>7);
	$instext[4]=array('name'=>$lang->instname4,'lvl'=>9);
	$instext[5]=array('name'=>$lang->instname4,'lvl'=>11);
	

	$middlec.=$xhtml->getLink('index.php?instant&amp;'.rand(100,999).'',$xhtml->getImage('icon/re.png',$lang->refresh)).$xhtml->getBR();
	if (WAIT_BATTLE==0){
		if (LAST_INST<(time()-60*60*3)){
		if (isset($_GET['acd'])){
			$acd=intval($_GET['acd']);
			if (intval($acd)>0) {
			        $gClass->joinToInstant($acd);
			        $xhtml->redirect('index.php?instant','1');
			}
			
		}
		$insttype=intval($_POST['inst']);
		if (isset($_POST['crdrf'])){
			$minlvl=3;
			$maxlvl=8;
			$insttype=intval($_POST['inst']);
			$maxuser=intval($_POST['us']);
			if ($insttype>1005 ||$insttype<1001) $insttype=1004;
			$db->Query("INSERT INTO rpg_instantbattle SET minlvl='".(USER_LVL-1)."', maxlvl='".(USER_LVL+1)."', endtime='".(time()+600)."', instype='".$insttype."', usersum='".$maxuser."'");
			$insid=$db->lastInsertID();
			$db->Query("INSERT INTO rpg_instantusers SET insid='".$insid."', userID='".USER_ID."', login='".USER_NAME."', lvl='".USER_LVL."'");
			$db->Query("UPDATE rpg_users SET wbattle='INST_".$insid."' WHERE id='".USER_ID."'");
            $db->Query("INSERT INTO rpg_chat SET uid1='".USER_ID."', sender='".USER_NAME."', msg='".$lang->icreatadventbat." [".$instant[$insttype]."]. [".$maxuser."]', lang='".$_SESSION['lang']."', sdate=NOW()");
			$xhtml->redirect('index.php?instant',1);
			}
		
		$userssum=array(1=>1,2=>2,3=>3,4=>4,5=>5,6=>6,7=>7,8=>8,9=>9,10=>10);
		$middlec.=$xhtml->getBlock($lang->creatgroup,'b');
	    $middlec.=$xhtml->getBlock($xhtml->startForm('index.php?instant','post').$lang->choesadventure.$xhtml->getBR().$xhtml->getSelect('inst',$instant).$xhtml->getBR().
	    $lang->gamersum.':'.$xhtml->getSelect('us',$userssum).$xhtml->getBR().
	    $xhtml->getInput('crdrf',$lang->creat,'submit')).$xhtml->endForm();
	    }
	    else{
	    	$middlec.=$lang->cnotadventurealert.$xhtml->getBR();
	    	$middlec.=$lang->nextadventuretime.':'.$gClass->retMinSec((LAST_INST+60*60*3-time()));
	    }
	}
	else{
		$db->Query("SELECT * FROM rpg_instantbattle WHERE id='".WAIT_BATTLE."' AND status=0");
		if ($db->getAffectedRows()>0){
			$rins=$db->FetchAssoc();
			$db->Query("SELECT * FROM rpg_instantusers WHERE insid='".WAIT_BATTLE."'");
		    $n=$db->getAffectedRows();
		    if ($n==$rins['usersum']){
		    	while ($r=$db->FetchAssoc()){
		    		$users[]=array('id'=>$r['userID'],'login'=>$r['login'],'lvl'=>$r['lvl']);;
		    	}
		    	startInstantBattle($users,$rins['instype']);
		    	$db->Query("UPDATE rpg_instantbattle SET status=1 WHERE id='".WAIT_BATTLE."'");
		    	$xhtml->redirect('index.php?instant',1);
		    	
		    }
		    else{
		    	if ($rins['endtime']<time()){
		    	while ($r=$db->FetchAssoc()){
		    	    mysql_query("UPDATE rpg_users SET wbattle=0 WHERE id='".$r['userID']."'");
		    	}
		    	$db->Query("UPDATE rpg_instantbattle SET status=3 WHERE id='".WAIT_BATTLE."'");
		    	$xhtml->redirect('index.php?instant');
		    	}
		    }
		}
		
	}
		$middlec.=$xhtml->getBlock($lang->recalls,'b');
		$db->Query("SELECT * FROM rpg_instantbattle WHERE endtime>'".time()."' AND status=0");
		if ($db->getAffectedRows()>0){
		while ($row=$db->FetchAssoc()){
			$zvk.=$xhtml->getSpan($instext[($row['instype']-1000)]['name'],'color_red').' [lvl:'.$row['minlvl'].'-'.$row['maxlvl'].'] ['.$gClass->retMinSec(($row['endtime']-time())).']';
			$query=mysql_query("SELECT * FROM rpg_instantusers WHERE insid='".$row['id']."'");
			while($res=mysql_fetch_assoc($query)){
				$zvk.=$xhtml->getLink('index.php?person&amp;u='.$res['userID'].'',$res['login'].' ['.$res['lvl'].']').' ';
			}
			$zvk.=(((USER_ID!=$row['userID']) && (WAIT_BATTLE==0) && ($row['minlvl']<=USER_LVL) && ($row['maxlvl']>=USER_LVL))?':'.$xhtml->getLink('index.php?instant&amp;acd='.$row['id'].'',$lang->accept):'').'';
			$middlec.=$xhtml->getBlock($zvk,'zvklist');
			$zvk='';
		}
		}
		else{
			$middlec.=$lang->noactrecalls;
		}
		$middlec.=$xhtml->getBlock($lang->adventureinfo1.
		$xhtml->getBR().$lang->adventureinfo2.
		$xhtml->getBR().$lang->adventureinfo3.
		$xhtml->getBR().$lang->adventureinfo4.
		$xhtml->getBR().$lang->adventureinfo5
		,'b');
		$middlec.=$gClass->placeChat('index.php?instant');
	$downc.=$xhtml->getLink('index.php',$lang->main);
	$xhtml->createPage($title,$topc,$middlec,$downc);
}
function arena(){
	global $db,$xhtml,$gClass,$ses,$lang;
	$topc.=$gClass->getLifeManaLine();
	$middlec.=$xhtml->getLink('index.php?arena&amp;'.rand(100,999).'',$xhtml->getImage('icon/re.png',$lang->refresh)).$xhtml->getBR();
	if (WAIT_BATTLE==0){
		if (isset($_GET['gbatt']) && $_GET['gbatt']==$_SESSION['lastsearch'] && USER_LVL<=2){
			$middlec.=$gClass->startAtacDuel(USER_ID,$_GET['gbatt']);
		}
		
		
		if (USER_LVL<=2 && isset($_GET['search'])){
			$offset_result = mysql_query("SELECT FLOOR(RAND() * COUNT(*)) AS `offset` FROM `rpg_users` WHERE level<='".USER_LVL."' AND life>=15 AND act=0 AND id!='".USER_ID."' AND place<=1");
            $offset_row = mysql_fetch_object( $offset_result ); 
            $offset = $offset_row->offset;
            $db->Query("SELECT id,login,level FROM rpg_users WHERE level<='".USER_LVL."' AND life>=15 AND act=0 AND id!='".USER_ID."' AND place<=1 LIMIT $offset, 1 " );
			$row=$db->FetchAssoc();
            
			$_SESSION['lastsearch']=$row['id'];
			$middlec.=$lang->searched.':'.$xhtml->getSpan($row['login'],'color_red').'['.$row['level'].']'.$xhtml->getLink('index.php?arena&amp;gbatt='.$row['id'].'',$lang->attack);
		}
		
		
		if (isset($_GET['acd'])){
			$acd=intval($_GET['acd']);
			if (intval($acd)>0) $gClass->joinToDuel($acd);
			$xhtml->redirect('index.php?arena','1');
		}
		
	if (isset($_GET['creat'])){
		$minlvl=(USER_LVL>2?(USER_LVL-2):0);
		$maxlvl=USER_LVL+2;
		$pltime=time()+180;
		$db->Query("INSERT INTO rpg_userduelbattles SET user1='".USER_ID."', minlvl='".$minlvl."', maxlvl='".$maxlvl."', ctime='".time()."', endtime='".$pltime."', btime='".DUEL_WAIT_TIME."'");
		$db->Query("UPDATE rpg_users SET wbattle='D_".$db->lastInsertID()."' WHERE id='".USER_ID."'");
        $db->Query("INSERT INTO rpg_chat SET uid1='".USER_ID."', sender='".USER_NAME."', msg='".$lang->icreatduel." [lvl:".$minlvl."-".$maxlvl."]', lang='".$_SESSION['lang']."', sdate=NOW()");
		$xhtml->redirect('index.php?arena','1');
	}
	    $middlec.=$xhtml->getBlock($lang->creatfigth,'alert');
	    
	    if (USER_LVL<=2){
   	        $middlec.=$xhtml->getLink('index.php?arena&amp;search',$lang->oponentsearch).$xhtml->getBR().'---'.$xhtml->getBR();
   	    }
   	    
	    $middlec.=$xhtml->getLink('index.php?arena&amp;creat',$lang->creatfigth).$xhtml->getBR();
	}
	else {
		$gClass->updateDuels(WAIT_BATTLE);
		if (isset($_GET['yd'])){
			if (intval($_GET['yd'])>0){
				$gClass->acteptDuel(intval($_GET['yd']));
				$xhtml->redirect('index.php?arena','1');
			}
		}
		if (isset($_GET['nd'])){
			if (intval($_GET['nd'])){
			    $gClass->declineDuel(intval($_GET['nd']));
			    $xhtml->redirect('index.php?arena',1);
			}
		}
		$db->Query("SELECT * FROM rpg_userduelbattles WHERE id='".WAIT_BATTLE."'");
		$wb=$db->FetchAssoc();
		if ($wb['user1']==USER_ID){
			$middlec.=$xhtml->getBlock($lang->waitingoponent,'alert');
			if ($wb['user2']>0){
				$user2=$gClass->getUserInfo($wb['user2'],array('login','level'));
				$middlec.=$xhtml->getBlock($lang->yourfigthaccept.': '.$xhtml->getLink('index.php?person&u='.$wb['user2'].'',$user2['login']) .'['.$user2['level'].']'.$xhtml->getBR().$xhtml->getLink('index.php?arena&yd='.$wb['id'].'',$lang->accept).'|'.$xhtml->getLink('index.php?arena&nd='.$wb['id'].'',$lang->refus),'acz');
			}
			
		}
		if ($wb['user2']==USER_ID){
			$middlec.=$xhtml->getBlock($lang->waitingoponent,'alert');
		}
		
	}
	
	$db->Query("SELECT b.*,u.login FROM rpg_userduelbattles as b LEFT join rpg_users AS u ON b.user1=u.id WHERE b.status=0 AND b.endtime>'".time()."'");
	$middlec.=$lang->recalls.':';
	while ($row=$db->FetchAssoc()){
		$zvk.=$xhtml->getBlock($xhtml->getLink('index.php?person&amp;u='.$row['user1'].'',$row['login'].' ['.($row['maxlvl']-2).']').' [lvl:'.$row['minlvl'].'-'.$row['maxlvl'].'] ['.$gClass->retMinSec(($row['endtime']-time())).'] '.(USER_ID!=$row['user1'] && (USER_ID!=$row['user2']) && (WAIT_BATTLE==0) && ($row['minlvl']<=USER_LVL && $row['maxlvl']>=USER_LVL)?$xhtml->getLink('index.php?arena&amp;acd='.$row['id'].'',$lang->accept):''),'zvklist');
	}
	$middlec.=$xhtml->getBlock($zvk,'');
	$middlec.=$gClass->placeChat('index.php?arena');
	$downc.=$xhtml->getLink('index.php?arena',$lang->figtharena).$xhtml->getBR();
	$downc.=$xhtml->getLink('index.php',$lang->main);
	$_SESSION['location']=array('index.php?arena',$lang->arena);
	$xhtml->createPage($lang->figtharena,$topc,$middlec,$downc);
}
function botBattle($bid){
	global $db,$xhtml,$ses,$gClass,$lang;
	$title='Brdzola';
	//$middlec.=$xhtml->getLink('index.php?'.rand(100,999).'',$xhtml->getImage('icons/re.gif','Ganaxleba')).$xhtml->getBR();
	$db->Query("SELECT b.btime,b.type,act.* FROM rpg_botbattlesaction as act LEFT JOIN rpg_botbattles as b ON b.id=act.bid WHERE act.bid='".$bid."' and act.userID='".USER_ID."'");
	if ($db->getAffectedRows()>0){
		$r=$db->FetchAssoc();
		if ($r['type']>0) $instant=$r['type'];
		else $instant=0;
		//$middlec.=$xhtml->getLink('index.php','Ganaxleba').$xhtml->getBR();
		if ($r['status']==1) {
		    $hd.='TimeOut| '.$gClass->retMinSec(($r['acttime']+$r['btime']-time()));
		    $middlec.=$xhtml->getBlock($hd,'timeOut');
		}
		$middlec.=$gClass->botBattlePlayers($bid).$xhtml->getBR();
	    //$userStats=$gClass->countStats(USER_ID);
        $timeoff=$r['acttime']+$r['btime'];
        if ($timeoff<time()){
        	if ($r['status']>0){
        	    $db->Query("UPDATE rpg_botbattlesaction SET status=0 where bid='".$bid."' AND  userID='".USER_ID."'");
        	    $db->Query("SELECT status FROM rpg_botbattlesaction WHERE bid='".$bid."' AND userID>0 AND status=1");
        	    if ($db->getAffectedRows()==0){
        	    	$gClass->botBattleEnd($bid,2);
        	    	exit;
        	    	$r['status']=0;
        	    }
        	}
        }
        $db->Query("SELECT userBot FROM rpg_botbattlesaction WHERE bid='".$bid."' AND status=1 AND userBot>0 and acttime<".(time()-3)." limit 1");
        	if ($db->getAffectedRows()>0){
        		$botID=$db->FetchAssoc();
	        	$db->Query("SELECT botID FROM rpg_botbattlesaction WHERE bid='".$bid."' AND userID=0 AND status=1");
                $br=$db->FetchAssoc();
                $botStats=$gClass->botStats($br['botID']);
                $db->Query("SELECT * FROM rpg_usersanimals WHERE aid='".$botID['userBot']."'");
                $userBot=$db->FetchAssoc();
                $res=$gClass->doBotFight($bid,$userBot,$botStats,1);
                if ($res['user']==0){
			    	$db->Query("UPDATE rpg_botbattlesaction SET status=0 where bid='".$bid."' AND  userBot='".$botID['userBot']."'");
			    	//$gClass->botBattleEnd($bid,2);
			    	$db->Query("SELECT bid FROM rpg_botbattlesaction WHERE bid='".$bid."' AND (userID>0 OR userBot>0) AND status=1");
			    	if ($db->getAffectedRows()==0){
			    		$gClass->botBattleEnd($bid,2);
			    		exit;
			    	}
			    }
			    
			    if ($res['bot']==0){
			    	$db->Query("UPDATE rpg_botbattlesaction SET status=0 where bid='".$bid."' AND  botID='".$br['botID']."'");
			    	$db->Query("SELECT bid FROM rpg_botbattlesaction WHERE bid='".$bid."' AND botID>0 AND status=1");
			    	if ($db->getAffectedRows()==0){
			    		$gClass->botBattleEnd($bid,1,$instant);
			    		exit;
			    	}
			    }
			    
        	}
        if ($r['status']==1){
        	$db->Query("SELECT botID FROM rpg_botbattlesaction WHERE bid='".$bid."' AND userID=0 AND status=1");
        	$botNumb=$db->getAffectedRows();
        	$botRand=rand(0,$botNumb-1);
            //$db->Query("SELECT botID FROM rpg_botbattlesaction WHERE bid='".$bid."' AND userID=0 AND status=1 LIMIT ".$botRand.",".($botRand+1)."");
            $br=$db->FetchAssoc();
            $botStats=$gClass->botStats($br['botID']);
            if (isset($_GET['callanimal'])){
            	if (ANIMAL>0){
            		$db->Query("SELECT * FROM rpg_usersanimals WHERE usid='".USER_ID."' AND replete>=(alevel*fmeal) AND life>0 AND act='0'");
            		if ($db->getAffectedRows()==1){
            			$brow=$db->FetchAssoc();
            			$db->Query("INSERT INTO rpg_botbattlesaction SET bid='".$bid."',  userBot='".$brow['aid']."', login='".$brow['aname']."(".$brow['mainname'].")', lvl='".$brow['alevel']."', acttime='".time()."', status='1'");
            			$bins=$db->lastInsertID();
            			$db->Query("UPDATE rpg_usersanimals SET act='".ACT_TYPE."_".USER_ACT."' WHERE aid='".$brow['aid']."'");
            			$xhtml->redirect('index.php');
            		}
            		else{
            			$middlec.=$lang->animalfigthalert;
            		}
            	}
            }
            if (isset($_POST['drink']) && intval($_POST['el'])>0){
            	$utid=intval($_POST['el']);
            	$db->Query("SELECT * FROM users_items WHERE utid='".$utid."' AND ittype=20 AND usid='".$_SESSION['user_id']."'");
            	if ($elrow=$db->FetchAssoc()){
            		$gClass->addLifeMana(USER_ID,$elrow['rest_life'],$elrow['rest_mana']);
            		if ($elrow['rest_life']>0) {
            		   $log=' '.$lang->relife;
            		   $point=$elrow['rest_life'];
            		}
            		if ($elrow['rest_mana']>0) {
            		    $log=' Sheivso mana';
            		    $point=$elrow['rest_mana'];
            		}
            		$db->Query("DELETE FROM users_items WHERE utid='".$utid."' AND usid='".USER_ID."'");
            		$db->Query("INSERT INTO rpg_botbattleslogs SET bid='".$bid."', userID='".USER_ID."', player1='".USER_NAME."', description='".$log."', repoint='".$point."', acttime=NOW()");
            		redirect('index.php',1);
            	}
            }
            $userStats['id']=USER_ID;
            $userStats['name']=USER_NAME;
            $userStats['strength']=STRENGTH;
            $userStats['dexterity']=DEXTERITY;
		    $userStats['accuracy']=ACCURACY;
		    $userStats['blocking']=BLOCKING;
		    $userStats['ant_dexterity']=ANT_DEXTERITY;
		    $userStats['ant_accuracy']=ANT_ACCURACY;
		    $userStats['ant_blocking']=ANT_BLOCKING;
		    $userStats['bron']=BRON;
		    $userStats['life']=NOW_LIFE;
		    if (isset($_POST['action']) && (($_SESSION['hitact']+2)<time())){
		    	$_SESSION['hitact']=time();
		    	/*
		    	$actions=$gClass->actionRendomizer($userStats,$botStats);
			    $userAtac=$gClass->pointRendomizer(STRENGTH);
			    $uhit=$userAtac;
			    $botAtac=$gClass->pointRendomizer($botStats['strength']);
			    $bhit=$botAtac;
			    $userBron=$gClass->pointRendomizer(BRON,1);
			    $botBron=$gClass->pointRendomizer($botStats['bron'],1);
			    if ($actions['blk1']==1){
		       	    $botAtac=0;
		       	    $userSBlock=1;
		        }
		        if ($actions['blk2']==1){
		       	    $userAtac=0;
		       	    $botSBlock=1;
		        }
			    if ($actions['dex1']==1){
			    	if ($botSBlock==0){
			    	    $userAtac=($uhit*2);
			    	}
			    	else $userAtac=$uhit;
			    	$useract=2;
			    }
			    if ($actions['dex2']==1){
			    	if ($userSBlock==0){
			    	    $botAtac=($bhit*2);
			    	}
			    	else{
			    		$botAtac=$bhit;
			    	}
			    	$botact=2;
			    }
			    if ($actions['acc1']==1){
			    	$botAtac=0;
			    	$useract+=1;
			    }
			    if ($actions['acc2']==1){
			    	$userAtac=0;
			    	$botact+=1;
			    }
			    
			    $userhit=$userAtac-$botBron;
			    if ($userhit<0) $userhit=0;
			    $bothit=$botAtac-$userBron;
			    if ($bothit<0) $bothit=0;
   			    $getExp=($botStats['get_exp']>0?$gClass->getBotExp($userhit,$botStats['life'],$botStats['rlife'],$botStats['get_exp']):0);
			    $getDrop=($botStats['get_drop']>0?$gClass->getBotDrop($botStats['get_exp'],$getExp,$botStats['get_drop']):0);
			    $userStats['life']=$userStats['life']-$bothit;
			    $userStats['life']=($userStats['life']<0?0:$userStats['life']);
			    $botStats['life']=$botStats['life']-$userhit;
			    $botStats['life']=($botStats['life']<0?0:$botStats['life']);
			    $db->Query("UPDATE rpg_users SET life='".$userStats['life']."' WHERE id='".USER_ID."'");
			    $db->Query("UPDATE rpg_bots SET life='".$botStats['life']."' WHERE bid='".$botStats['bid']."'");
			    $db->Query("INSERT INTO rpg_botbattleslogs SET bid='".$bid."', userID='".USER_ID."', botID='".$botStats['bid']."', userhit='".$userhit."', bothit='".$bothit."', userblock='".$userSBlock."', botblock='".$botSBlock."', useract='".$useract."', botact='".$botact."', acttime=NOW()");
			    $db->Query("UPDATE rpg_botbattlesaction SET acttime='".time()."', userHits=userHits+".$userhit.", userExp=userExp+".$getExp.", userDrop=userDrop+".$getDrop." WHERE bid='".$bid."' AND userID='".USER_ID."'");
			   */
			    $fres=$gClass->doBotFight($bid,$userStats,$botStats);
			    //$botStats=$gClass->botStats($br['botID']);
		        //$userStats=$gClass->countStats(USER_ID);
		        if ($fres['user']==0){
			    	$db->Query("UPDATE rpg_botbattlesaction SET status=0 where bid='".$bid."' AND  userID='".USER_ID."'");
			    	//$gClass->botBattleEnd($bid,2);
			    	$db->Query("SELECT bid FROM rpg_botbattlesaction WHERE bid='".$bid."' AND (userID>0 OR userBot>0) AND status=1");
			    	if ($db->getAffectedRows()==0){
			    		$gClass->botBattleEnd($bid,2);
			    		exit;
			    	}
			    	else{
			    		$middlec.=$lang->ukilledwendbattle;
			    	}
			    }
			    if ($fres['bot']==0){
			    	$db->Query("UPDATE rpg_botbattlesaction SET status=0 where bid='".$bid."' AND  botID='".$botStats['bid']."'");
			    	$db->Query("SELECT bid FROM rpg_botbattlesaction WHERE bid='".$bid."' AND botID>0 AND status=1");
			    	if ($db->getAffectedRows()==0){
			    		
			    		$gClass->botBattleEnd($bid,1,$instant);
			    		exit;
			    	}
			    }
			    $xhtml->redirect('index.php');
		    }
		    if ($userStats['life']>0 && $botStats['life']>0){
		        $middlec.=$xhtml->startForm('index.php?b='.$bid.'&amp;'.$ses.'');
		        
		        $f.=$lang->oponent.':'.$gClass->languageReplace($botStats['name'],$_SESSION['lang']).' ('.$botStats['life'].')'.$xhtml->getBR();
		        $f.=$xhtml->getInput('action',$lang->hit,'submit');
		        $db->Query("SELECT * FROM users_items WHERE ittype=20 AND usid='".$_SESSION['user_id']."' GROUP BY itid");
		        if ($db->getAffectedRows()>0){
		        	while ($marr=$db->FetchAssoc()){
		        		$mgarr[$marr['utid']]=$marr['itname'];
		        	}
		        	$f.=$xhtml->getBR().$lang->elexirs.':'.$xhtml->getBR().$xhtml->getSelect('el',$mgarr).$xhtml->getBR();
		        	$f.=$xhtml->getInput('drink','Daleva','submit');
		        }
		        if (ANIMAL>0)
        	        $f.=$xhtml->getBR().$xhtml->getLink('index.php?callanimal',$lang->animalhelp).$xhtml->getBR();
		        $middlec.=$xhtml->getBlock($f,'fightact');
		        $middlec.=$xhtml->endForm();
		    }
		    
		    $middlec.=$gClass->showBotBattleLog($bid);
		}
		else {
			    $db->Query("UPDATE rpg_botbattlesaction SET status=0 where bid='".$bid."' AND  userID='".USER_ID."'");
        	    $db->Query("SELECT status FROM rpg_botbattlesaction WHERE bid='".$bid."' AND (userID>0 OR userBot>0) AND status=1");
        	    if ($db->getAffectedRows()==0){
        	    	$gClass->botBattleEnd($bid,2);
        	    	exit;
        	    }

			$middlec.=$xhtml->getBlock($lang->ukilledwendbattle.$xhtml->getBR().$xhtml->getLink('index.php?'.rand(100,999).'',$xhtml->getImage('icon/re.png',$lang->refresh)),'b');
			$middlec.=$gClass->showBotBattleLog($bid);
		}
	}
	else $middlec.=$lang->monstrinfigth;
	$topc=$gClass->getLifeManaLine();
	$downc='';
	//$downc.=$xhtml->getLink('index.php','Mtavari gverdi');
	$xhtml->createPage($title,$topc,$middlec,$downc);
}

function game_main(){
	global $db,$lang,$gClass,$xhtml;
	$topc.=$gClass->getLifeManaLine();
	$db->Query("SELECT u.place,p.pname_".LANG.",p.id,p.parent FROM rpg_users as u LEFT JOIN rpg_place as p ON u.place=p.id where u.id='".$_SESSION['user_id']."'");
	$row=$db->FetchArray();
	$title=$row['pname_'.LANG.''];
	//$middlec.=$row['pname_'.LANG.''].$xhtml->getBR();
	if ($row['id']!=1 && $row['id']!=0){
        $middlec.=place($row['id']);
        $middlec.=$gClass->placeChat();
    }
    else {
    	if (isset($_SESSION['PIT'])) UNSET($_SESSION['PIT']);
    }
	if ($row['id']==0 || $row['id']==1){
	    $middlec.=$xhtml->getBlock($lang->buildings,'alert');
		$builds.=$xhtml->getLink('index.php?arena',$lang->duels).$xhtml->getBR();
		if (USER_LVL>2){
		$builds.=$xhtml->getLink('index.php?garena',$lang->chaosfigths).$xhtml->getBR();
		}
		elseif(USER_LVL<=2){
		$builds.=$lang->chaosfigths.'[2lvl]'.$xhtml->getBR();
		}
	    $builds.=$xhtml->getLink('index.php?shop',$lang->shop).$xhtml->getBR();
	    $builds.=$xhtml->getLink('index.php?magichouse',$lang->magichouse).$xhtml->getBR();
	    if (USER_LVL>2) {
	        $builds.=$xhtml->getLink('index.php?forgehous',$lang->forge).$xhtml->getBR();
	        $builds.=$xhtml->getLink('index.php?zooshop',$lang->zooshop).$xhtml->getBR();
	        $builds.=$xhtml->getLink('index.php?clans',$lang->clans).$xhtml->getBR();
	    }
	    
	    $middlec.=$xhtml->getBlock($builds);
		$middlec.=$xhtml->getBlock($lang->adventures,'alert');
	    $h.=$xhtml->getLink('index.php?pl=2',$lang->hunterforest).$xhtml->getBR();
	    $h.=$xhtml->getLink('index.php?pl=13',$lang->orsemane).$xhtml->getBR();
	    $h.=$xhtml->getLink('index.php?unkin',$lang->magicland).$xhtml->getBR();
	    if (USER_LVL>2) $h.=$xhtml->getLink('index.php?instant',$lang->adventurefigths).$xhtml->getBR();
        
	    $h.=$xhtml->getLink('index.php?pl=14',$lang->citydoyan).$xhtml->getBR();
	    $middlec.=$xhtml->getBlock($h);
	   //$middlec.=$xhtml->getBLock('Qalaqshia','alert');
  	   //$middlec.=$xhtml->getLink('index.php?chat',$lang->warriorshall).$xhtml->getBR().'----'.$xhtml->getBR();
	}	
	$middlec.=$gClass->downMenu();
	$middlec.=$xhtml->getLink('index.php?onlusers','Online:'.$gClass->getOnline().'');

	$xhtml->createPage($title,$topc,$middlec,$downc);
}  
function forgeHous(){
	global $xhtml,$db,$gClass,$lang;
	$topc.=$gClass->getLifeManaLine();
	$litarray=array(1=>$lang->iron,2=>$lang->bronze,3=>$lang->plumbum,4=>$lang->silver,5=>$lang->copper,6=>$lang->aluminium,7=>$lang->platinum);
	$title=$lang->forge;
	if (isset($_GET['forger'])){
		$forger=intval($_GET['forger']);
		if ($forger>0){
			$db->Query("SELECT prof.*,u.id as userid,u.login,u.level,u.last_visit FROM rpg_profes as prof LEFT JOIN rpg_users as u on u.id=prof.uid WHERE prof.id='".$forger."' AND prof.profid=1");
			$row=$db->FetchAssoc();
			if ($row['last_visit']<time()-600){
				redirect('index.php?forgehous&forg');
			}
			$middlec.=$lang->forger.':'.$row['login'].''.$xhtml->getBR();
			$middlec.=$lang->experience.':'.$row['profexp'].''.$xhtml->getBR();
			$db->Query("SELECT * FROM users_resours WHERE uid='".$row['userid']."'");
			$r=$db->FetchAssoc();
			$resarr=array(1=>$lang->strength.' +10 '.($r['type1']<20?'[!]':''),2=>$lang->dexterity.' +10 '.($r['type2']<20?'[!]':''),3=>$lang->blocking.' +10 '.($r['type3']<20?'[!]':''),4=>$lang->accuracy.' +10 '.($r['type4']<20?'[!]':''), 5=>$lang->antaccuracy.' +20 '.($r['type5']<20?'[!]':''), 6=>$lang->antdexterity.' +20 '.($r['type6']<20?'[!]':''), 7=>$lang->antblocking.' +20 '.($r['type7']<20?'[!]':''));
			if (isset($_POST['forgit'])){
				$rt=intval($_POST['rt']);
				$it=intval($_POST['it']);
				switch ($rt){
					case 1;
					$q="strength_p=strength_p+10, modit='S'";
					$t=' 10 '.$lang->strength;
					break;
					case 2;
					$q="dexterity_p=dexterity_p+10, modit='D'";
					$t=' 10 '.$lang->dexterity;
					break;
					case 3;
					$q="blocking_p=blocking_p+10, modit='B'";
					$t=' 10 '.$lang->blocking;
					break;
					case 4;
					$q="accuracy_p=accuracy_p+10, modit='A'";
					$t=' 10 '.$lang->accuracy;
					break;
					case 5;
					$q="ant_accuracy_p=ant_accuracy_p+20, modit='AA'";
					$t=' 20 '.$lang->antaccuracy;
					break;
					case 6;
					$q="ant_dexterity_p=ant_dexterity_p+20, modit='AD'";
					$t=' 20 '.$lang->antdexterity;
					break;
					case 7;
					$q="ant_blocking_p=ant_blocking_p+20, modit='AB'";
					$t=' 20 '.$lang->antblocking;
					break;
				}
				if ($r['type'.$rt.'']<20){
					$error=$lang->forgerhnotres;
				}
				if (MONEY<$row['wprice'] && $row['userid']!=USER_ID){
					$error=$lang->uhnotenougthmoney;
				}
				if (!empty($error)){
					$middlec.=$xhtml->getBlock($error,'b');
				}
				else{
				$db->Query("SELECT * FROM users_items WHERE utid='".$it."' AND usid='".USER_ID."' AND modit is null");
				if ($db->getAffectedRows()>0){
					$rit=$db->FetchAssoc();
					if ($row['userid']!=USER_ID){
					    $db->Query("UPDATE rpg_users SET money=money-".$row['wprice']." WHERE id='".USER_ID."'");
					    $db->Query("UPDATE rpg_users SET money=money+".$row['wprice']." WHERE id='".$row['userid']."'");
					}
					$db->Query("UPDATE users_resours SET type".$rt."=type".$rt."-20 WHERE uid='".$row['userid']."'");
					$db->Query("UPDATE rpg_profes SET profexp=profexp+1 WHERE id='".$row['id']."'");
					$db->Query("UPDATE users_items SET ".$q." WHERE utid='".$it."' AND usid='".USER_ID."'");
					$gClass->doActionLogs(USER_ID,$row['userid'],USER_NAME,'gamocheda type'.$rt.'');
					$middlec.=$xhtml->getBlock($lang->uadd.''.$t.''.$lang->fitem.':'.$rit['itname'].'','b');
				}
				}
			}
			$db->Query("SELECT utid,itname,lvl FROM users_items WHERE usid='".USER_ID."' AND ittype<=8 and modit is null");
			while ($rs=$db->FetchAssoc()){
				$itarr[$rs['utid']]=$rs['itname'].'['.$rs['lvl'].' lvl]';
			}
			$middlec.=$lang->serviceprice.':'.$row['wprice'].' '.$lang->gold;
			if (!empty($itarr)){
			$middlec.=$xhtml->startForm('index.php?forgehous&amp;forger='.$forger.'','post');
			$middlec.=$lang->choesitem.':'.$xhtml->getBR();
			$middlec.=$xhtml->getSelect('it',$itarr).$xhtml->getBR();
			$middlec.=$lang->add.':'.$xhtml->getBR();
			$middlec.=$xhtml->getSelect('rt',$resarr).$xhtml->getBR();
			$middlec.=$xhtml->getInput('forgit',$lang->add,'submit');
			$middlec.=$xhtml->endForm();
			}
			else{
				$middlec.=$xhtml->getBlock($lang->uhnotitemforadd,'b');
			}
			$downc.=$xhtml->getLink('index.php?forgehous&forg',$lang->personalforges).$xhtml->getBR();
			$downc.=$xhtml->getLink('index.php',$lang->main);
	        $xhtml->createPage($title,$topc,$middlec,$downc);
		}
	}
	if (isset($_GET['repitems'])){
		if (!empty($_POST['it'])){
			$itm=intval($_POST['it']);
			$db->Query("SELECT utid,itname,lvl,price,img,broktime FROM users_items WHERE utid='".$itm."' and usid='".USER_ID."' AND ittype<=8 AND nore='0'");
			if ($db->getAffectedRows()>0){
				$itinfo=$db->FetchAssoc();
				if (!empty($_POST['rt'])){
					$rt=intval($_POST['rt']);
					if ($rt>0){
						$recost=($itinfo['price']/100)*$rt;
						if (MONEY>=$recost){
							$db->Query("UPDATE rpg_users SET money=money-".$recost." WHERE id='".USER_ID."'");
							$db->Query("UPDATE users_items SET broktime=broktime+".(60*60*24*$rt)." WHERE utid='".$itm."' AND usid='".USER_ID."'");
							$middlec.=$lang->itemliferedura.' '.$rt.' '.$lang->forday.''.$xhtml->getBR();
						}
					}
				}
				
				$middlec.=$xhtml->getBlock($itinfo['itname'],'baypars').$xhtml->getBlock('','divhr');
	    		$middlec.=$xhtml->getImage(ITEMS_PATH.$itinfo['img'],$itinfo['itname'],'','','item_block');
	    		$middlec.=$xhtml->getBlock($lang->lifetime.': '.$gClass->retMinSec($itinfo['broktime']-time()),'parblock').$xhtml->getBlock('','divhr');
	    		for ($i=1; $i<=30; $i++){
	    			$dayarr[$i]=$i.' '.$lang->day.' ['.(($itinfo['price']/100)*$i).' Oqro]';
	    		}
	    		$middlec.=$xhtml->startForm('index.php?forgehous&amp;repitems','post');
	    		$middlec.='Sheketeba:'.$xhtml->getBR();
	    		$middlec.=$xhtml->getSelect('rt',$dayarr).$xhtml->getBR();
	    		$middlec.=$xhtml->getInput('it',$itm,'hidden');
	    		$middlec.=$xhtml->getInput('repit','OK','submit');
	    		$downc.=$xhtml->getLink('index.php',$lang->main);
	    		$xhtml->createPage($title,$topc,$middlec,$downc);
			}
		}
		$db->Query("SELECT utid,itname,lvl,broktime FROM users_items WHERE usid='".USER_ID."' AND ittype<=8 AND nore='0'");
		while ($rs=$db->FetchAssoc()){
		       $itarr[$rs['utid']]=$rs['itname'].'['.$rs['lvl'].' lvl] ('.$gClass->retMinSec($rs['broktime']-time()).')';
		      
	    }
	    $middlec.=$xhtml->startForm('index.php?forgehous&amp;repitems','post');
	    $middlec.=$lang->choesitem.$xhtml->getBR();
	    $middlec.=$xhtml->getSelect('it',$itarr).$xhtml->getBR();
	    $middlec.=$xhtml->getInput('repit','OK','submit');
		$middlec.=$xhtml->endForm();
		$downc.=$xhtml->getLink('index.php',$lang->main);
		$xhtml->createPage($title,$topc,$middlec,$downc);
	}
	if (isset($_GET['forg'])){
		$middlec.=$xhtml->getBlock($lang->forgers,'b');
		$db->Query("SELECT prof.*,u.login,u.level FROM rpg_profes as prof LEFT JOIN rpg_users as u on prof.uid=u.id WHERE prof.profid='1' AND last_visit>='".(time()-600)."' ORDER BY prof.profexp DESC");
		while ($row=$db->FetchAssoc()){
			$middlec.=$xhtml->getBlock($xhtml->getLink('index.php?person&u='.$row['uid'].'',$row['login'].'['.$row['level'].']').$lang->experience.':'.$row['profexp'].', '.$lang->price.':'.$row['wprice'].$xhtml->getBR().$xhtml->getLink('index.php?forgehous&forger='.$row['id'].'',$lang->enterinforg),'zvklist');
		}
		$downc.=$xhtml->getLink('index.php?forgehous',$lang->forge).$xhtml->getBR();
		$downc.=$xhtml->getLink('index.php',$lang->main);
	    $xhtml->createPage($title,$topc,$middlec,$downc);
	}
	if (isset($_GET['lic'])){
		$db->Query("SELECT * FROM rpg_profes WHERE uid='".USER_ID."' AND profid='1'");
		if ($db->getAffectedRows()>0){
			$rp=$db->FetchAssoc();
			if (!empty($_POST['ch'])){
				$fprice=intval($_POST['prc']);
				$db->Query("UPDATE rpg_profes SET wprice='".$fprice."' WHERE id='".$rp['id']."'");
			}
			$middlec.=$lang->henterpricefforg.$xhtml->getBR();
			$middlec.=$lang->foryouforgprice;
			$middlec.=$xhtml->startForm('index.php?forgehous&lic');
			$pricearr=array('25'=>'25 '.$lang->gold,'30'=>'30 '.$lang->gold,'35'=>'35 '.$lang->gold,'40'=>'40 '.$lang->gold,'45'=>'45 '.$lang->gold,'50'=>'50 '.$lang->gold);
			$middlec.=$xhtml->getSelect('prc',$pricearr,$rp['wprice']).$xhtml->getBR();
			$middlec.=$xhtml->getInput('ch',$lang->change,'submit');
		    $isprof=1;
		}
		if (isset($_GET['buy'])){
		if ($isprof==1){
			$ans=$lang->uhforglicence;
		}
		else{
			if (MONEY<500){
				$ans=$lang->uhnotenougthmoney;
			}
			else{
				$db->Query("UPDATE rpg_users SET money=money-2000 WHERE id='".USER_ID."'");
				$db->Query("INSERT INTO rpg_profes SET uid='".USER_ID."', profid=1, wprice=40");
				$ans=$lang->ugetforglicence;
			}
		}
		}
		if (empty($isprof)){
			$middlec.=$xhtml->getBlock($ans,'b');
		    $middlec.=$lang->forglicenceprice.' 2000 '.$lang->gold;
		    $middlec.=$xhtml->getBR();
		    $middlec.=$lang->forgerinfo1;
		    $middlec.=$xhtml->getBR();
		    $middlec.=$xhtml->forgerinfo2;
		    $middlec.=$xhtml->getBR();
		    $middlec.=$xhtml->getLink('index.php?forgehous&amp;lic&amp;buy',$lang->buylicence.' (2000 '.$lang->gold.')');
		    
		}
		$downc.=$xhtml->getLink('index.php',$lang->main);
	    $xhtml->createPage($title,$topc,$middlec,$downc);
	}
	$db->Query("SELECT * FROM users_resours WHERE uid='".USER_ID."'");
	if ($db->getAffectedRows()>0){
	    $row=$db->FetchAssoc();
	}
	else{
		$row['type1']=0;
		$row['type2']=0;
		$row['type3']=0;
		$row['type4']=0;
		$row['type5']=0;
		$row['type6']=0;
		$row['type7']=0;
	}
	if (isset($_POST['oksell'])){
		$res=intval($_POST['res']);
		$csum=intval($_POST['sumres']);
		if ($csum>0 && $res>0 && $res<8){
		if ($csum>$row['type'.$res.''] || $row['type'.$res.'']==0){
			$middlec.=$xhtml->getBlock($lang->uhnotenougth.' '.$litarray[$res].'','zvklist');
		}
		else{
			$row['type'.$res.'']-=$csum;
			$db->Query("UPDATE users_resours SET type".$res."=type".$res."-".$csum." WHERE uid='".USER_ID."'");
			$db->Query("UPDATE rpg_users SET money=money+".$csum." WHERE id='".USER_ID."'");
			$db->Query("UPDATE rpg_forgeres SET type".$res."=type".$res."+".$csum."");
			$middlec.=$xhtml->getBlock($lang->usell.' '.$csum.' '.$litarray[$res].', '.$lang->get.' '.$csum.' '.$lang->gold,'zvklist');
		}
		}
	}

	$middlec.=$lang->ressellcours.': 1 '.$lang->resours.' = 1 '.$lang->gold;
	$resArr=array(1=>$lang->iron.': '.$row['type1'],2=>$lang->bronze.': '.$row['type2'],3=>$lang->plumbum.': '.$row['type3'],4=>$lang->silver.': '.$row['type4'],5=>$lang->copper.': '.$row['type5'],6=>$lang->aluminium.': '.$row['type6'],7=>$lang->platinum.': '.$row['type7']);
	$middlec.=$xhtml->startForm('index.php?forgehous','post');
	$middlec.=$xhtml->getSelect('res',$resArr).$xhtml->getBR();
	$middlec.=$lang->sum.':'.$xhtml->getBR().$xhtml->getInput('sumres','','text','input_3').$xhtml->getBR();
	$middlec.=$xhtml->getInput('oksell',$lang->sell,'submit');
	$middlec.=$xhtml->endForm();
	$middlec.=$xhtml->getBlock($lang->inforgeres,'b');
	$db->Query("SELECT * FROM rpg_forgeres");
	$r=$db->FetchAssoc();
	$middlec.=$xhtml->getBlock($lang->iron.':'.$r['type1'],'zvklist');
	$middlec.=$xhtml->getBlock($lang->bronze.':'.$r['type2'],'zvklist');
	$middlec.=$xhtml->getBlock($lang->plumbum.':'.$r['type3'],'zvklist');
	$middlec.=$xhtml->getBlock($lang->silver.':'.$r['type4'],'zvklist');
	
	$middlec.=$xhtml->getBlock($lang->copper.':'.$r['type5'],'zvklist');
	$middlec.=$xhtml->getBlock($lang->aluminium.':'.$r['type6'],'zvklist');
	$middlec.=$xhtml->getBlock($lang->platinum.':'.$r['type7'],'zvklist');
	
	$middlec.=$xhtml->getBlock($lang->resoursbuy.' 1 '.$lang->resours.' = 2 '.$lang->gold,'b');
	
	if (isset($_POST['okbuy'])){
		$rtype=intval($_POST['bres']);
		$nr=intval($_POST['bsumres']);
		if ($rtype<=7 && $rtype>0) {
		if ($r['type'.$rtype.'']>=$nr){
			
			
			if ($nr>0 && MONEY>=($nr*2)){
				$sm=$nr*2;
				
				$db->Query("UPDATE rpg_users set money=money-'".$sm."' WHERE id='".USER_ID."'");
				$db->Query("UPDATE rpg_forgeres SET type".$rtype."=type".$rtype."-".$nr." WHERE id=1");
				$db->Query("UPDATE users_resours SET type".$rtype."=type".$rtype."+".$nr." WHERE uid='".USER_ID."'");
				$ans=$lang->youhavebuy.':'.$nr.' '.$litarray[$rtype].' '.$lang->payed.' '.$sm.' '.$lang->gold.'';
				
			}
			else{
				$ans=$lang->uhnotenougthmoney;
			}
			
		}
		else
		$ans=$lang->inforgnores;
		}
		$middlec.=$xhtml->getBlock($ans,'zvklist');
	}
	
	$bresArr=array(1=>$lang->iron.': '.$r['type1'],2=>$lang->bronze.': '.$r['type2'],3=>$lang->plumbum.': '.$r['type3'],4=>$lang->silver.': '.$r['type4'],5=>$lang->copper.': '.$r['type5'],6=>$lang->aluminium.': '.$r['type6'],7=>$lang->platinum.': '.$r['type7']);
	$middlec.=$xhtml->startForm('index.php?forgehous','post');
	$middlec.=$xhtml->getSelect('bres',$bresArr).$xhtml->getBR();
	$middlec.=$lang->sum.':'.$xhtml->getBR().$xhtml->getInput('bsumres','','text','input_3').$xhtml->getBR();
	$middlec.=$xhtml->getInput('okbuy',$lang->buy,'submit');
	$middlec.=$xhtml->endForm();
	$middlec.=$xhtml->getLink('index.php?forgehous&amp;repitems',$lang->repitems).$xhtml->getBR();
	$middlec.=$xhtml->getLink('index.php?forgehous&amp;forg',$lang->personalforges).$xhtml->getBR();
	$middlec.=$xhtml->getLink('index.php?forgehous&amp;lic',$lang->forgerlicence).$xhtml->getBR();
	$downc.=$xhtml->getLink('index.php',$lang->main);
	$xhtml->createPage($title,$topc,$middlec,$downc);
}
function zooShop(){
	global $xhtml,$db,$lang,$gClass,$ANIMAL_EXP;
	$topc.=$gClass->getLifeManaLine();
	$title=$lang->zooshop;
	$animals[1]=array('type'=>1,'fmeal'=>2,'mainname'=>$lang->wolf,'alevel'=>1,'strength'=>20,'accuracy'=>0,'dexterity'=>20,'blocking'=>0,'ant_accuracy'=>20,'ant_dexterity'=>5,'ant_blocking'=>10,'life'=>200,'rlife'=>'200','bron'=>5,'price'=>500);
	$animals[2]=array('type'=>2,'fmeal'=>2,'mainname'=>$lang->tiger,'alevel'=>1,'strength'=>20,'accuracy'=>20,'dexterity'=>0,'blocking'=>0,'ant_accuracy'=>5,'ant_dexterity'=>10,'ant_blocking'=>20,'life'=>200,'rlife'=>'200','bron'=>5,'price'=>500);
	$animals[3]=array('type'=>3,'fmeal'=>2,'mainname'=>$lang->bear,'alevel'=>1,'strength'=>20,'accuracy'=>0,'dexterity'=>0,'blocking'=>20,'ant_accuracy'=>20,'ant_dexterity'=>10,'ant_blocking'=>5,'life'=>200,'rlife'=>'200','bron'=>5,'price'=>500);
	$animals[4]=array('type'=>4,'fmeal'=>5,'mainname'=>$lang->dragon,'alevel'=>1,'strength'=>30,'accuracy'=>15,'dexterity'=>15,'blocking'=>15,'ant_accuracy'=>10,'ant_dexterity'=>10,'ant_blocking'=>10,'life'=>300,'rlife'=>'300','bron'=>10,'price'=>2000);
	if (ANIMAL==0){
		if (isset($_GET['bay'])){
			$bay=intval($_GET['bay']);
			if ($bay>0 && $bay<=4){
				if($animals[$bay]['price']<=MONEY){
					$db->Query("INSERT INTO rpg_usersanimals SET usid='".USER_ID."', type='".$bay."', fmeal='".$animals[$bay]['fmeal']."', alevel='".$animals[$bay]['alevel']."',
 	               mainname='".$animals[$bay]['mainname']."', strength='".$animals[$bay]['strength']."', accuracy='".$animals[$bay]['accuracy']."', dexterity='".$animals[$bay]['dexterity']."',
 	               blocking='".$animals[$bay]['blocking']."', ant_accuracy='".$animals[$bay]['ant_accuracy']."', ant_dexterity='".$animals[$bay]['ant_dexterity']."', ant_blocking='".$animals[$bay]['ant_blocking']."',
 	               life='".$animals[$bay]['life']."', rlife='".$animals[$bay]['rlife']."', bron='".$animals[$bay]['bron']."'");
 	                $insid=$db->lastInsertID();
					$db->Query("UPDATE rpg_users SET money=money-".$animals[$bay]['price'].", animal='".$insid."' WHERE id='".USER_ID."'");
					$xhtml->redirect('index.php?zooshop');
				}
			}
		}
		$middlec.=$xhtml->getBlock($lang->buyanimalinfo,'b');
		foreach ($animals as $value){
			$lang=new lang();
			$f.=$xhtml->getBlock($value['mainname'],'zvklist').$xhtml->getImage('images/a/'.$value['type'].'.jpg',$value['mainname']).$xhtml->getBR();
			$f.=$lang->strength.':'.$value['strength'].$xhtml->getBR();
			if ($value['accuracy']>0) $f.=$lang->accuracy.':'.$value['accuracy'].$xhtml->getBR();
			if ($value['dexterity']>0) $f.=$lang->dexterity.':'.$value['dexterity'].$xhtml->getBR();
			if ($value['blocking']>0) $f.=$lang->blocking.':'.$value['blocking'].$xhtml->getBR();
			if ($value['ant_accuracy']>0) $f.=$lang->antaccuracy.':'.$value['ant_accuracy'].$xhtml->getBR();
			if ($value['ant_dexterity']>0) $f.=$lang->antdexterity.':'.$value['ant_dexterity'].$xhtml->getBR();
			if ($value['ant_blocking']>0) $f.=$lang->antblocking.':'.$value['ant_blocking'].$xhtml->getBR();
			$f.=$lang->bron.':'.$value['bron'].$xhtml->getBR();
			$f.=$lang->feeding.':'.$value['fmeal'].' '.$lang->ration.$xhtml->getBR();
			$f.=$lang->price.':'.$value['price'].' '.$lang->price.$xhtml->getBR();
			$f.=(MONEY>$value['price']?$xhtml->getLink('index.php?zooshop&bay='.$value['type'].'',$lang->buy):$lang->uhnotenougthmoney);
		}
		$middlec.=$xhtml->getBlock($f,'b');
	}
	else{
		$db->Query("SELECT * FROM rpg_usersanimals WHERE aid='".ANIMAL."'");
		$row=$db->FetchAssoc();

		if (!empty($row['act'])){
			$db->Query("UPDATE rpg_usersanimals SET life=rlife, act='0' WHERE aid='".ANIMAL."'");
		}
		$middlec.=$xhtml->getBlock($row['mainname'],'zvklist').$xhtml->getImage('images/a/'.$row['type'].'.jpg',$row['mainname']).$xhtml->getBR();
		if (empty($row['aname'])){
			if (!empty($_POST['aname'])){
				$err=(preg_match('/[^0-9a-zA-Z]+/',$_POST['aname'])?$lang->ireg_login:$err);
				$err=(strlen($_POST['aname'])>12?$lang->big_login:$err);
				if (empty($err)){
					$db->Query("UPDATE rpg_usersanimals SET aname='".$_POST['aname']."' WHERE aid='".ANIMAL."'");
					$xhtml->redirect('index.php?zooshop');
				}
				else{
					$middlec.=$xhtml->getBlock($err,'b');
				}
			}
			$f.=$lang->animallogininfo;
			$f.=$xhtml->startForm('index.php?zooshop','post');
			$f.=$xhtml->getInput('aname','','text').$xhtml->getBR();
			$f.=$xhtml->getInput('sname',$lang->save,'submit');
			$f.=$xhtml->endForm();
			$middlec.=$f;
		}
		else{
			if (isset($_GET['banish'])){
				if (isset($_GET['yes'])){
					$db->Query("DELETE FROM rpg_usersanimals WHERE aid='".ANIMAL."'");
					$db->Query("UPDATE rpg_users SET animal=0 WHERE id='".USER_ID."'");
					$xhtml->redirect('index.php?zooshop');
				}
				$middlec.=$xhtml->getBlock($xhtml->getSpan($lang->warning.':','color_red').' '.$lang->animaldeletealert,'zvklist');
				$middlec.=$xhtml->getLink('index.php?zooshop',$lang->no).'|'.$xhtml->getLink('index.php?zooshop&amp;banish&amp;yes',$lang->yes).$xhtml->getBlock('','divhr');
			}
			$gClass->updateAnimalLevel($row['exp'],$row['alevel'],$row['type']);
			if (!empty($_POST['okeat'])){
				$eat=intval($_POST['eat']);
				if ($eat>0){
					switch ($eat){
						case 10;
						$money=5;
						break;
						case 30;
						$money=10;
						break;
						case 50;
						$money=20;
						break;
						case 100;
						$money=35;
						break;
						case 200;
						$money=50;
						break;
						default:
							$money=$eat;
						break;
						
					}
					if (MONEY>=$money){
						$db->Query("UPDATE rpg_users SET money=money-'".$money."' WHERE id='".USER_ID."'");
						$db->Query("UPDATE rpg_usersanimals SET replete=replete+".$eat." WHERE aid='".ANIMAL."'");
						$xhtml->redirect('index.php?zooshop');
					}
				}
			}
			$middlec.=$lang->name.':'.$row['aname'].$xhtml->getBR();
			$middlec.='Exp:'.$row['exp'].' ('.$ANIMAL_EXP[$row['alevel']+1].')'.$xhtml->getBR();
			$middlec.=$lang->mealinfigth.':'.($row['alevel']*$row['fmeal']).$xhtml->getBR();
			$middlec.=$lang->eatinglvl.':'.$row['replete'].$xhtml->getBR();
			if ($row['fmeal']>$row['replete']){
				$middlec.=$lang->animalcnotfbmeal;
			}
			$middlec.=$xhtml->startForm('index.php?zooshop','post');
			$selarr=array('10'=>'10 '.$lang->ration.' (5 '.$lang->gold.')','30'=>'30 '.$lang->ration.' (10 '.$lang->gold.')','50'=>'50 '.$lang->ration.' (20 '.$lang->gold.')','100'=>'100 '.$lang->ration.' (35 '.$lang->gold.')','200'=>'200 '.$lang->ration.' (50 '.$lang->gold.')');
			$middlec.=$xhtml->getSelect('eat',$selarr).$xhtml->getBR();
			$middlec.=$xhtml->getinput('okeat',$lang->feeding,'submit');
			$middlec.=$xhtml->endForm();
			$middlec.=$xhtml->getBlock($xhtml->getLink('index.php?zooshop&banish',$lang->animaldelete),'zvklist');
		}
	}
	$downc.=$xhtml->getLink('index.php',$lang->main);
	$xhtml->createPage($title,$topc,$middlec,$downc);
}
function magicHouse(){
	global $db,$lang,$gClass,$xhtml;
	

	
	if ((USER_PLACE!=0 && USER_PLACE!=1)){
		redirect('index.php');
		exit;
	}
	
	$topc.=$gClass->getLifeManaLine();
	$title=$lang->magichouse;
	//$middlec.=$xhtml->getBlock('Aq shegidzliat sheiswavlot sabrdzolo magiebi','alert');
	if (isset($_GET['item'])){
		$item=intval($_GET['item']);
		$db->Query("SELECT * FROM rpg_items WHERE itid='".$item."' and csum>0");
		if ($db->getAffectedRows()>0){
			$itinfo=$db->FetchAssoc();
	    	if (isset($_GET['bay'])){
	    		if (((USER_LVL+1)*5)<=$gClass->countBagItems()){
	    			$_SESSION['answer']=$lang->uhnotbagplace;
	    		}
	    		else{
	    		$_SESSION['answer']=$gClass->bayItem($itinfo,$_SESSION['user_id']);
	    		}
	    		redirect('index.php?magichouse&c='.$c.'&item='.$item.'&wb',1);
	    	}
	    	if (isset($_GET['wb'])) {
	    	    $middlec.=$_SESSION['answer'];
	    	    unset($_SESSION['answer']);
	    	}
			$middlec.=$xhtml->getBlock($itinfo['itname'],'baypars').$xhtml->getBlock('','divhr');
	    			    $middlec.=$xhtml->getImage(ITEMS_PATH.$itinfo['img'],$itinfo['itname'],'','','item_block');
	    			    $middlec.=$xhtml->getBlock($lang->parametrs,'parblock').$xhtml->getBlock('','divhr');
	    			    if (!empty($itinfo['strength_p'])){
	    				    $parst.=$lang->strength.': '.$itinfo['strength_p'].$xhtml->getBR();
	    			    }
	    			    if (!empty($itinfo['accuracy_p'])){
	    				    $parst.=$lang->accuracy.': '.$itinfo['accuracy_p'].$xhtml->getBR();
	    			    }
	    			    if (!empty($itinfo['dexterity_p'])){
	    				    $parst.=$lang->dexterity.': '.$itinfo['dexterity_p'].$xhtml->getBR();
	    			    }
	    			    if (!empty($itinfo['life_p'])){
	    				    $parst.=$lang->life.': '.$itinfo['life_p'].$xhtml->getBR();
	    			    }
	    			    if (!empty($itinfo['mana_p'])){
	    				    $parst.='Mana: '.$itinfo['mana_p'].$xhtml->getBR();
	    			    }
	    			    if (!empty($itinfo['ant_accuracy_p'])){
	    				    $parst.=$lang->antaccuracy.': '.$itinfo['ant_accuracy_p'].$xhtml->getBR();
	    			    }
	    			    if (!empty($itinfo['ant_dexterity_p'])){
	    				    $parst.=$lang->antdexterity.': '.$itinfo['ant_dexterity_p'].$xhtml->getBR();
	    			    }
	    			    if (!empty($itinfo['ant_blocking_p'])){
	    				    $parst.=$lang->antblocking.': '.$itinfo['ant_blocking_p'].$xhtml->getBR();
	    			    }
	    			    if (!empty($itinfo['bron_p'])){
	    				    $parst.=$lang->bron.': '.$itinfo['bron_p'].$xhtml->getBR();
	    			    }
	    			    if (!empty($itinfo['description'])){
	    			    	$parst.=$gClass->languageReplace($itinfo['description'],$_SESSION['lang']).$xhtml->getBR();
	    			    }
	    			    if (!empty($itinfo['acttime'])){
	    			    	$parst.=$lang->actiontime.':'.$gClass->retMinSec($itinfo['acttime']);
	    			    }
	    			    
	    			    $middlec.=$xhtml->getBlock($parst,'baypars');
	    			    $middlec.=$xhtml->getBlock('','divhr');
	    			    $middlec.=$xhtml->getBlock($lang->price.': '.$itinfo['price'].' '.$lang->gold,'baypars');
	    			    $middlec.=$xhtml->getBlock($lang->inshop.':'.$itinfo['csum'].' '.$lang->csumer,'baypars');
	    			    $middlec.=$xhtml->getLink('index.php?magichouse&amp;c='.$c.'&amp;item='.$item.'&amp;bay',$lang->buy);
	    			    
	    			    $downc.=$xhtml->getLink('index.php?magichouse',$lang->magichouse).$xhtml->getBR();
		}
		else{
			$middlec.=$lang->itemnotfound;
		}
	}
	else{
	   $db->Query("SELECT * FROM rpg_items WHERE (ittype=20 OR ittype=21 OR ittype=22) and csum>0");
	   while ($row=$db->FetchAssoc()){
	          $middlec.=$xhtml->getLink('index.php?magichouse&amp;c='.$c.'&amp;item='.$row['itid'].'',$row['itname']).' ('.$row['price'].' '.$lang->gold.') lvl:'.$row['lvl'].$xhtml->getBR();
	          if (!empty($row['description'])) $middlec.=$gClass->languageReplace($row['description'],$_SESSION['lang']).$xhtml->getBR();
	   }
	}
	$downc.=$xhtml->getLink('index.php',$lang->main);
	$xhtml->createPage($title,$topc,$middlec,$downc);
}
		function gameChat(){
			global $xhtml,$db,$gClass;
			$topc.=$gClass->getLifeManaLine();
			$title=$lang->warriorshall;
			$middlec.=$xhtml->getLink('index.php?chat&amp;'.rand(100,999).'',$xhtml->getImage('icon/re.png',$lang->refresh)).$xhtml->getBR();
            $middlec.=$gClass->chatSendForm();
            $middlec.=$gClass->getChatLetters();
			$downc.=$xhtml->getLink('index.php',$lang->main);
			$_SESSION['location']=array('index.php?chat',$lang->warriorshall);
	        $xhtml->createPage($title,$topc,$middlec,$downc);
		}
function usersOnline($id=''){
	global $xhtml,$db,$gClass;
	$topc.=$gClass->getLifeManaLine();
	$title='Online';
	$db->Query("SELECT id,login,level FROM rpg_users WHERE last_visit>'".(time()-600)."' ".($id>1?"AND place='".$id."'":'')." ORDER BY last_visit DESC");
	unset($_SESSION['location']);
	while ($row=$db->FetchAssoc()){
		   $source.=$xhtml->getLink('index.php?person&amp;u='.$row['id'].'',$row['login'].'['.$row['level'].']').$xhtml->getBR();
	}
	$middlec=$xhtml->getBlock($source,'onus');
    $downc.=$xhtml->getLink('index.php',$lang->main);
    $_SESSION['location']=array('index.php?chat',$lang->warriorshall);
    $xhtml->createPage($title,$topc,$middlec,$downc);
	return $source;
}
function ClansHouse(){
	global $db,$xhtml,$gClass,$lang;
	$topc.=$gClass->getLifeManaLine();
	$title=$lang->clans;
	if (isset($_GET['list'])){
		$db->Query("SELECT * FROM rpg_clans ORDER BY rating DESC");
		while ($row=$db->FetchAssoc()){
			$middlec.=$xhtml->getBlock($xhtml->getLink('index.php?clans&amp;id='.$row['id'],$row['clanname']).(!empty($row['clanico'])?'<img src="image.php?img='.CLANICONS.$row['clanico'].'&w=24&h=24" alt=""/>':'').'['.$row['rating'].']','b');
		}
		$downc=$xhtml->getLink('index.php?clans',$lang->clans).$xhtml->getBR();
		$downc.=$xhtml->getLink('index.php',$lang->main);
		$xhtml->createPage($title,$topc,$middlec,$downc);
	}
	if (isset($_GET['id'])){
		$id=intval($_GET['id']);
		if ($id>0){

			$db->Query("SELECT * FROM rpg_clans WHERE id='".$id."'");
			if ($db->getAffectedRows()>0){
				$r=$db->FetchAssoc();
				$db->Query("SELECT us.id,us.login,us.level,cs.requser FROM rpg_clans as c  LEFT JOIN rpg_clanusers as cs on c.id=cs.cid LEFT JOIN rpg_users AS us ON us.id=cs.uid WHERE c.id='".$r['id']."' ORDER BY us.level DESC");
		        while ($row=$db->FetchAssoc()){
		        	if ($row['requser']==0){
			           $clanusers.=$xhtml->getBlock($row['login'].'['.$row['level'].']'.($row['id']==$r['creator']?'(Metauri)':''),'b');
			        }
			        elseif($row['id']==USER_ID) $isin=1;
		        }
			if (CLANID==0 && isset($_GET['join'])){
				$db->Query("SELECT * FROM rpg_clanusers WHERE cid='".$id."'");
				$rn=$db->getAffectedRows();
				if ($rn>=$r['maxusers']){
					$middlec.=$lang->clanadoptclose;
				}
				elseif($isin==1){
					$middlec.=$lang->clanUrequestsent;
				}
				else{
					$db->Query("DELETE FROM rpg_clanusers WHERE uid='".USER_ID."' AND requser=1");
					$db->Query("INSERT INTO rpg_clanusers SET cid='".$id."', uid='".USER_ID."', requser=1");
					$middlec.=$lang->clanUrequestsent;
				}
			}
			$middlec.=$xhtml->getBlock($lang->clan.':'.$r['clanname'].(!empty($r['clanico'])?'<img src="image.php?img='.CLANICONS.$r['clanico'].'&w=24&h=24" alt=""/>':''),'b');
			if (CLANID==0){
				if ($isin==1) $middlec.=$lang->clanUrequestsent;
				else
			    $middlec.=$xhtml->getLink('index.php?clans&amp;id='.$id.'&join',$lang->jointoclan);
			    $middlec.=$xhtml->getBlock($lang->jointoclaninfo,'zvklist');
			}
		    if (!empty($r['claninfo'])){
		        $middlec.=$xhtml->getBlock(''.$lang->Istoria.':'.$xhtml->getBR().$r['claninfo'],'zvklist');
		    }
		    $middlec.=$xhtml->getBlock($lang->maxclanmember.':'.$r['maxusers'],'zvklist');
		    $ctime=explode(' ',$r['cdate']);
		    $middlec.=$xhtml->getBlock($lang->created.':'.$ctime[0],'zvklist');
		    $middlec.=$xhtml->getBlock($lang->clanmembers.':','zvklist');
		    $middlec.=$clanusers;

		    if ($r['creator']==USER_ID){
			   $middlec.=$xhtml->getLink('index.php?clans&chinfo',$lang->changeclaninfo).$xhtml->getBR();
			    $middlec.='-----'.$xhtml->getBR();
		    }
		   	$downc=$xhtml->getLink('index.php?clans&list',$lang->cityclans).$xhtml->getBR();
			$downc.=$xhtml->getLink('index.php','Mtavari');
		    $xhtml->createPage($title,$topc,$middlec,$downc);
		    }
		}
	}
	if (CLANID==0){
	    if (isset($_GET['creat'])){
		    if (USER_LVL>=9){
		    	if (!empty($_POST['cname'])){
		    		if (preg_match('/[^0-9a-zA-Z\-\_]+/',$_POST['cname'])) $error=$lang->iregclanname;
		    		if (strlen($_POST['cname'])>12) $error=$lang->clannameislong;
		    		if (strlen($_POST['cname'])<4) $error=$lang->clannameisshort;
		    		if (MONEY<5000) $error=$lang->Uhnotmoneyfclan;
		    		if (empty($error)){
		    			$cname=$_POST['cname'];
		    			$db->Query("UPDATE rpg_users SET money=money-5000 WHERE id='".USER_ID."'");
		    			$db->Query("INSERT INTO rpg_clans SET creator='".USER_ID."', clanname='".$cname."', maxusers=5, cdate=NOW()");
		    			$insid=$db->lastInsertID();
		    			$db->Query("UPDATE rpg_users SET clanid='".$insid."' WHERE id='".USER_ID."'");
		    			$db->Query("INSERT INTO rpg_clanusers SET cid='".$insid."', uid='".USER_ID."'");
		    			$xhtml->redirect('index.php?clans');
		    		}
		    		else
		    			$middlec.=$error;
		    	}
		    	
			    $middlec.=$xhtml->getBlock($lang->clanregprice,'onus');
			    $middlec.=$xhtml->getBlock($lang->clanregUctchngnm,'onus');
			    $middlec.=$xhtml->getBlock($lang->clanregrulename,'onus');
			    $middlec.=$xhtml->getBlock($lang->clanregnamenocenz,'onus');
			    $middlec.=$xhtml->startForm('index.php?clans&amp;creat');
			    $middlec.=$xhtml->enterclanname.':'.$xhtml->getBR();
			    $middlec.=$xhtml->getInput('cname','','text').$xhtml->getBR();
			    $middlec.=$xhtml->getInput('ok',$lang->clanregistration,'submit').$xhtml->getBR();
			    $midldec.=$xhtml->endForm();
		    }
		    else{
			    $middlec.=$lang->clanreglvllimit;
		    }
		    $downc=$xhtml->getLink('index.php?clans',$lang->clans).$xhtml->getBR();
			$downc.=$xhtml->getLink('index.php',$lang->main);
		    $xhtml->createPage($title,$topc,$middlec,$downc);
	    }
	    
	}
	else{
		
		$db->Query("SELECT * FROM rpg_clans WHERE id='".CLANID."'");
		$r=$db->FetchAssoc();
		if ((isset($_GET['left']) && ($r['creator']==USER_ID)) || isset($_GET['leftclan'])){
			$left=intval($_GET['left']);
			if ($left>0){
			    $db->Query("SELECT * FROM rpg_clanusers WHERE uid='".$left."' AND cid='".CLANID."' AND requser=0");
			    if ($db->getAffectedRows()>0){
			    	$db->Query("DELETE FROM rpg_clanusers WHERE uid='".$left."' AND cid='".CLANID."'");
			    	$db->Query("UPDATE rpg_users SET clanid=0 WHERE id='".$left."'");
			    	$db->Query("INSERT INTO rpg_postbag SET slogin='System', reid='".$left."', msg='".$lang->Ucickedfromclan."', readit=1, sdate=NOW()");
			    }
			}
			elseif(isset($_GET['leftclan'])){
				$db->Query("SELECT * FROM rpg_clanusers WHERE uid='".USER_ID."' AND cid='".CLANID."' AND requser=0");
			    if ($db->getAffectedRows()>0){
			    	$db->Query("DELETE FROM rpg_clanusers WHERE uid='".USER_ID."' AND cid='".CLANID."'");
			    	$db->Query("UPDATE rpg_users SET clanid=0 WHERE id='".USER_ID."'");
			    	$db->Query("INSERT INTO rpg_postbag SET slogin='System', reid='".$r['creator']."', msg='".$lang->Uclanleft.": ".USER_NAME."', readit=1, sdate=NOW()");
			    }
			}
			$xhtml->redirect('index.php?clans');
			
		}
		$db->Query("SELECT us.id,us.login,us.level,cs.requser FROM rpg_clans as c  LEFT JOIN rpg_clanusers as cs on c.id=cs.cid LEFT JOIN rpg_users AS us ON us.id=cs.uid WHERE c.id='".$r['id']."' ORDER BY us.level DESC");
		$cusum=0;
		while ($row=$db->FetchAssoc()){
			if ($row['requser']==0){
			    $clanusers.=$xhtml->getBlock($row['login'].'['.$row['level'].']'.($row['id']==$r['creator']?'(Metauri)':''.($r['creator']==USER_ID?$xhtml->getLink('index.php?clans&left='.$row['id'].'','Gashveba'):'').''),'zvklist');
			    $cusum++;
			}
			else
				$pandingusers.=$xhtml->getBlock($xhtml->getLink('index.php?person&amp;u='.$row['id'].'',$row['login'].'['.$row['level'].']').':::'.$xhtml->getLink('index.php?clans&accus='.$row['id'].'','Migeba').'|'.$xhtml->getLink('index.php?clans&decl='.$row['id'].'','Uaryofa'),'b');
		}
		if (USER_ID==$r['creator'] && isset($_GET['accus'])){
			if ($cusum>=$r['maxusers']){
				$middlec.=$lang->Ucantaddclanus;
				
			}
			$accus=intval($_GET['accus']);
			if ($accus>0){
			    $db->Query("SELECT * FROM rpg_clanusers WHERE cid='".CLANID."' AND uid='".$accus."' AND requser=1");
			    if ($db->getAffectedRows()>0){
			    	$db->Query("SELECT * FROM rpg_users WHERE id='".$accus."' AND money>=200");
			    	if ($db->getAffectedRows()>0){
			    	    $db->Query("UPDATE rpg_clanusers SET requser=0 WHERE cid='".CLANID."' AND uid='".$accus."'");
			    	    $db->Query("UPDATE rpg_users SET clanid='".CLANID."', money=money-200 WHERE id='".$accus."'");
			    	    $xhtml->redirect('index.php?clans');
			    	}
			    	else{
			    		$middlec.=$lang->cantjoinclanmoney;
			    	}
			    }
			}
		}
		if (USER_ID==$r['creator'] && isset($_GET['decl'])){
			$decl=intval($_GET['decl']);
			if ($decl>0){
			    $db->Query("SELECT * FROM rpg_clanusers WHERE cid='".CLANID."' AND uid='".$decl."' AND requser=1");
			    if ($db->getAffectedRows()>0){
			    	$db->Query("DELETE FROM rpg_clanusers WHERE cid='".CLANID."' AND uid='".$decl."'");
			    	$xhtml->redirect('index.php?clans');
			    }
			}
		}

		if (isset($_GET['chinfo']) && USER_ID==$r['creator']){
			if (!empty($_POST['ok'])){
				if (!empty($_FILES['ico']['name']) && empty($r['clanico'])){
					$res=$gClass->save_file($_FILES['ico']['size'],$_FILES['ico']['type'],$_FILES['ico']['name'],$_FILES['ico']['tmp_name'],CLANICONS,$r['id']);
					$middlec.='<img src="'.$_FILES['ico']['type'].'">';
					if (!empty($res['error'])){
						$middlec.=$res['error'];
					}
					else{
						$upfile=$r['id'].'.'.$res['save']['ex'];
					}
				}
				$inf=mysql_escape_string($_POST['info']);
				$inf=htmlspecialchars($_POST['info']);
				if (empty($res['error'])){
				$db->Query("UPDATE rpg_clans SET claninfo='".$inf."'".(!empty($upfile)?", clanico='".$upfile."'":"")." WHERE id='".$r['id']."'");
				$xhtml->redirect('index.php?clans&chinfo');
				}
			}
			$middlec.=$xhtml->startForm('index.php?clans&amp;chinfo','post',1);
			if (empty($r['clanico'])){
				$middlec.=$lang->clanicon.$xhtml->getBR();
				$middlec.=$lang->Ucntchngclanicon.$xhtml->getBR();
				$middlec.=$xhtml->getInput('ico','','file').$xhtml->getBR();
			}
			$middlec.=$lang->clanhistory.' (Max:255 Symbol)'.$xhtml->getBR();
			$middlec.=$xhtml->getTextarea('info',$r['claninfo'],5,10).$xhtml->getBR();
			$middlec.=$xhtml->getInput('ok','OK','submit');
			$midldec.=$xhtml->endForm();
			$downc=$xhtml->getLink('index.php?clans',$lang->clans).$xhtml->getBR();
			$downc.=$xhtml->getLink('index.php',$lang->main);
			$xhtml->createPage($title,$topc,$middlec,$downc);
		}
		$middlec.=$xhtml->getBlock($lang->clan.':'.$r['clanname'].(!empty($r['clanico'])?'<img src="image.php?img='.CLANICONS.$r['clanico'].'&w=24&h=24" alt=""/>':''),'b');
		if (!empty($r['claninfo'])){
		    $middlec.=$xhtml->getBlock($lang->history.':'.$xhtml->getBR().$r['claninfo'],'zvklist');
		}
		$middlec.=$xhtml->getBlock($lang->maxclanuser.':'.$r['maxusers'],'zvklist');
		$ctime=explode(' ',$r['cdate']);
		$middlec.=$xhtml->getBlock($lang->clancreated.':'.$ctime[0],'zvklist');
		$middlec.=$xhtml->getBlock($lang->clanbudjet.': '.$r['budjet'].' '.$xhtml->getImage('icon/g.png'),'zvklist');

		$middlec.=$xhtml->getBlock($lang->clanmembers.':','zvklist');
		$middlec.=$clanusers;
		if ($r['creator']!=USER_ID){
			$middlec.=$xhtml->getBlock($xhtml->getLink('index.php?clans&leftclan',$lang->leftclan),'zvklist');
		}
		if (!empty($pandingusers)){
		    $middlec.=$xhtml->getBlock($lang->clanwishies,'zvklist');
		    $middlec.=$pandingusers;
		}
		if ($r['creator']==USER_ID){
			$middlec.=$xhtml->getBlock($xhtml->getLink('index.php?clans&chinfo',$lang->chngclaninfo),'zvklist');
		}
		$middlec.='-----'.$xhtml->getBR();
	}
	
	if (CLANID==0) $middlec.=$xhtml->getLink('index.php?clans&creat',$lang->clanregistration).$xhtml->getBR();
	$middlec.=$xhtml->getLink('index.php?clans&list',$lang->cityclans).$xhtml->getBR();
	$xhtml->createPage($title,$topc,$middlec,$downc);
}
function userCabinet(){
	global $xhtml,$db,$lang;
	$title.=$lang->cabinet;
	if (isset($_GET['chpass'])){
		if (!empty($_POST['okch'])){
			
			$db->Query("SELECT password FROM rpg_users WHERE id='".USER_ID."'");
			$r=$db->FetchAssoc();
			if ($_POST['oldp']!=$r['password']) $err=$lang->curentpasserror;
			else{
				$pass=$_POST['np'];
			$err=(preg_match('/[^0-9a-zA-Z\-\_]+/',$pass)?$lang->ireg_pass:$err);
		    $err=((strlen($pass)<4)?$lang->small_pass:$err);
		    $err=((strlen($pass)>12)?$lang->big_pass:$err);
		    if (empty($err)){
		    	if ($pass!=$_POST['rnp']){
		    		$err=$lang->nowpasswrong;
		    	}
		    }
		    }
		    if (!empty($err)){
		    	$ans=$err;
		    }
		    else{
		    	$db->Query("UPDATE rpg_users SET password='".$pass."' WHERE id='".USER_ID."'");
		    	$ans=$lang->passsaccchanged;
		    }
		    $middlec.=$xhtml->getBlock($ans,'b');
		}
		$middlec.=$xhtml->startForm('index.php?cabinet&amp;chpass');
		$middlec.=$lang->nowpass.':'.$xhtml->getBR();
		$middlec.=$xhtml->getInput('oldp').$xhtml->getBR();
		$middlec.=$lang->newpass.':'.$xhtml->getBR();
		$middlec.=$xhtml->getInput('np').$xhtml->getBR();
		$middlec.=$lang->repeatpass.':'.$xhtml->getBR();
		$middlec.=$xhtml->getInput('rnp').$xhtml->getBR();
		$middlec.=$xhtml->getInput('okch',$lang->change,'submit');
		$middlec.=$xhtml->endForm();
		$downc.=$xhtml->getLink('index.php',$lang->main);
		$xhtml->createPage($title,$topc,$middlec,$downc);
	}
	if (!empty($_POST['abinf']) || !EMPTY($_POST['theme'])){
		$ntheme=intval($_POST['theme']);
		$_SESSION['theme']=$ntheme;
		$inf=htmlspecialchars(mysql_escape_string($_POST['abinf']));
		$db->Query("UPDATE rpg_users SET inf='".$inf."', theme='".$ntheme."' WHERE id='".USER_ID."'");
		$xhtml->redirect('index.php?cabinet');
		
	}
	$db->Query("SELECT inf,theme FROM rpg_users WHERE id='".USER_ID."'");
	$row=$db->FetchAssoc();
	$middlec.=$xhtml->getBlock($lang->infoaboutme.' (Max:255 Symbol)','b');
	$middlec.=$xhtml->startForm('index.php?cabinet','post');
	$middlec.=$xhtml->getBlock($xhtml->getTextarea('abinf',$row['inf'],5,20),'z');
	$middlec.=$xhtml->getBlock($lang->design.':'.$xhtml->getSelect('theme',array(1=>$lang->new,2=>$lang->old),$row['theme']),'z');
	$middlec.=$xhtml->getBlock($xhtml->getInput('chinf',$lang->change,'submit'),'z');
	$middlec.=$xhtml->getLink('index.php?cabinet&amp;chpass',$lang->changepass);
	$middlec.=$xhtml->getBlock($lang->reffurl.':','b');
	$middlec.=$xhtml->getBlock('http://legenda.wapop.org/?ref='.USER_ID.'','c');
	$middlec.=$lang->affilateinfo1.' 50 '.$lang->gold.$xhtml->getBR();
	$middlec.=$lang->affilateinfo2;
	$downc.=$xhtml->getLink('index.php',$lang->main);
	$xhtml->createPage($title,$topc,$middlec,$downc);
}
function teqSamushao(){
	global $xhtml;
	$title='Legenda.wapop.org';
	$topc.=$xhtml->getBlock($xhtml->getImage('icon/hd.gif','Legend'),'cnt');
	$topc.='Teqnikuri samushao';
	$middlec.='Mimdinareobs bazebis gegmiuri wmenda tamashi chairtveba ramodenime wutshi'.$xhtml->getBR();
	$xhtml->createPage($title,$topc,$middlec,$downc);
}

function gameNews($get=''){
	global $xhtml;
	$title='Tamashis siaxleebi';
	$top.='Tamashis siaxleebi';
	$news='Siaxleebis texti';
	
	$middlec.=$news;
	$downc.=$xhtml->getLink('index.php','Qalaqshi');
	$xhtml->createPage($title,$topc,$middlec,$downc);
}
function underKingdom(){
	global $db,$xhtml,$lang;
	$darr=array(1=>15,2=>19,3=>23,4=>27);
	if (intval($_GET['g'])>0){
		$g=intval($_GET['g']);
		if (array_key_exists($g,$_SESSION['dkeys'])){
			$_SESSION['dogo']=1;
			$db->Query("DELETE FROM users_items WHERE usid='".USER_ID."' AND ittype=22 AND lvl='".$g."' LIMIT 1");
			$xhtml->redirect('index.php?pl='.$darr[$g].'');
		}
	}

	$db->Query("SELECT * FROM users_items WHERE usid='".USER_ID."' and ittype=22");
	while ($row=$db->FetchAssoc()){
		$key[$row['lvl']]=1;
	}

	$_SESSION['dkeys']=$key;
	$title=$lang->magicland;
	$middlec.=$lang->magiclandwkey.$xhtml->getBR().'---'.$xhtml->getBR();
	$middlec.=($key[1]==1?$xhtml->getLink('index.php?unkin&amp;g=1',$lang->gate.' 1'):$lang->door.' 1').$xhtml->getBR();
	$middlec.=($key[2]==1?$xhtml->getLink('index.php?unkin&amp;g=2',$lang->gate.' 2'):$lang->door.' 2').$xhtml->getBR();
	$middlec.=($key[3]==1?$xhtml->getLink('index.php?unkin&amp;g=3',$lang->gate.' 3'):$lang->door.' 3').$xhtml->getBR();
	$middlec.=($key[4]==1?$xhtml->getLink('index.php?unkin&amp;g=4',$lang->gate.' 4'):$lang->door.' 4').$xhtml->getBR();
	$downc.=$xhtml->getLink('index.php',$lang->main);
	$xhtml->createPage($title,$topc,$middlec,$downc);
}
function ganulebamde(){
	global $xhtml;
	$title='samoqmedo gegma ganulebamde';
	$middlec.=$xhtml->getBlock('1. kvestebi uxucesis davaleba gasasworebelia ise rom axalbedas gaumartivdes tamashis gageba, aseve gadasaketebelia kvestebis moduli rata martivad moxdes axali kvestebis damateba','zvklist');
	$middlec.=$xhtml->getBlock('2. gadasawyvetia sxvadasxva klasis personajebsi dabalanseba ar unda iyos prioritetuli klassi, amastanave gasatvaliswinebelia or erti da imave klassshi brdzolis dasrulebis problema ','zvklist');
	$middlec.=$xhtml->getBlock('3. gasaketebelia nivtebis martivad damatebis moduli da unda moxdes nivtebze parametrebis minichebis principi da mati girebuleba rata ar moxdes tamashis mimdinareobasa da nivtebis girebulebebs shoris disbalansi','zvklist');
	$middlec.=$xhtml->getBlock('4. mosafiqrebelia nadirobisas migebuli exp da oqros optimaluri mnishvneloba shesacvlelia principi levelis matebastan ertad mowinaagmdege botebis da dropis mateba','zvklist');
	$middlec.=$xhtml->getBlock('5. gadasaketebelia satavgadasavlo botebi unda iyos sxvadasxva klasis rata martivad ar sheedzlos ert personajs yvelas damarcxeba','zvklist');
	$middlec.=$xhtml->getBlock('6. gasasworebelis cxovelis gachedva rodesac cxovelis gashveba xdeba roca brdola dasrulebulia','zvklist');
	$middlec.=$xhtml->getBlock('7. yvelafris gatvaliswinebit gamosatvlelia optimaluri levelze gadasasvleli exp rogorc personajis aseve cxovelis','zvklist');
	$middlec.=$xhtml->getBlock('8. dasamatebeli moderatoris funqcia da matze kontroli, chamosaweria wesebi da akrdzalvebi, unda moxdes moderatorta kontroli da agiricxos mat mier shesrulebuli bani mizezi, dro da a.sh','zvklist');
	$middlec.=$xhtml->getBlock('9. wesebit da moderatorebit unda shemcirdes multebis arseboba samudamod unda daiblokos nikebi romlebic amjgavneben nikidan nikze oqros an exp s gadacemas','zvklist');
	$middlec.=$xhtml->getBlock('10. mosawesrigebelia klanebi, metaurs unda sheedzlos martva. unda iyos klanta shoris turnirebi, aseve sheidzleba arsebobdes klanis cixesimaggrebi da klanebs shoris tavdasxma garkveul periodshi, mosafiqrebelia am procesebis mimdevroba, shedegebi, stimuli, waxalisebis metodi, reitingi','zvklist');
	$middlec.=$xhtml->getBlock('11. gasaketebelia jgufuri modzraoba rgac lokaciaze sadac motamasheebs sheedzlebat ertmanetis daxmareba jgufurad tavdasxma botebze da a.sh mosafiqrebelia aseti lokaaciis gegma da jildo/sargebeli','zvklist');
	$middlec.=$xhtml->getBlock('12. martivad shesadzlebelia sxva qalaqebis sheqmna da mosafiqrebelia am qalaqebis datvirtva','zvklist');
	$middlec.=$xhtml->getBlock('13. gasasworebelia da bolomde gadasatargmnia tamashis sxvadasxva ena unda moxdes iseti informaciis targmna romelic mxolod bazashi inaxeba, mag: kvestebi','zvklist');
	$middlec.=$xhtml->getBlock('14. mosafiqrebelia sxvadasxva enis motamasheebi ertad iyvnen tu izolirebuli, an tu ertad ra formit','zvklist');
	$middlec.=$xhtml->getBlock('15. dasamatebelia qaotur brdzolashi travmebi, da mosamatebelia qaotur brdzolashi exp an raime saxit waxaliseba qaoturi brdzolebisatvis','zvklist');
	$middlec.=$xhtml->getBlock('16. mosafiqrebelia sxva profesiebi tundac eqimi-travmebistvis, jadoqarti-eleqsirebis sheqmnistvis+damatebis sxva resursebis mopoveba eleqsiris ingredientebistvis','zvklist');
	$middlec.=$xhtml->getBlock('17. gadasaxedia yvela niuansi interfeisshi rac arakompfortulobas iwvevs navigaciashi','zvklist');
	$middlec.=$xhtml->getBlock('18. gadasaketebelia dizaini ufro didi ekranis mqone telefonebisatvis','zvklist');
	$middlec.=$xhtml->getBlock('19. mosafiqrebelia kompiuteris dashveba ar dashveba tamasshi','zvklist');
	$middlec.=$xhtml->getBlock('20. gasasworebelia nivtis vadis gauqmebisas misi bazidan droulad washla da fostashi misuli werili','zvklist');
	$xhtml->createPage($title,$topc,$middlec,$downc);
}
function gameRueles(){
	global $xhtml,$lang;
	$titel='Tamashis wesebi';
	$top.='tamashis cesebi';
	$langRul['ge'][1]='Akrdzalulia chatshi sxva momxmareblebis sheuracyofa, dafloodva (erti da igive textis mravaljer gameoreba)';
	$langRul['eng'][1]='It is prohibited insulting or flooding (sending the same text multiple times) of other users';
	$langRul['ge'][2]='Akrdzalulia yvelanairi reklama, garda satamasho profesiebisa';
	$langRul['eng'][2]='Any sort of advertisement is prohibitted, other than when it is required under the game profession';
	$langRul['ge'][3]='Akrdzalulia mravali nikis registracia';
	$langRul['eng'][3]='It is prohibited to register multiple user ids';
	$langRul['ge'][4]='Akrdzalulia erti da igive personajit ori adamianis tamashi';
	$langRul['eng'][4]='It is prohibited to play the same character by two users';
	$langRul['ge'][5]='Akrdzalulia personajis gayidva, gachuqeba, txoveba';
	$langRul['eng'][5]='the Sale, transfer or borrowing of the character is prohibitted';
	$langRul['ge'][6]='Akrdzalulia erti motamashedan meorestvis oqors gadacema profesiebit';
	$langRul['eng'][6]='transfer of gold from one user to another seemingly because of the profession (e.g. rendering services) is prohibitted';
	$langRul['ge'][7]='Akrdzalulia ucenzuro da sheuracmyofeli nikebis registracia';
	$langRul['eng'][7]='Registration of insulting and abusing user IDs is prohibitted';
	foreach ($langRul[LANG] as $v){
		$middlec.=$xhtml->getBlock($v,'zvklist');
	}
	$ft['ge']='Cesebis dargvevis shemtxvevashi administracia uflebas itovebs, danashaulis simdzimis mixedvit dablokos an daajarimos motamashe';
	$ft['eng']='In case of violation of these Rules the administration reserves the right to block or fine the violator based on the gravity of the violation';
	$middlec.=$ft[LANG];
	$downc.=$xhtml->getLink('index.php',$lang->main);
	$xhtml->createPage($title,$topc,$middlec,$downc);
}
function gameFaq(){
	global $xhtml,$LVL_EXP,$LVL_MONEY;
	$title='Daxmareba';
	$sec=intval($_GET['sec']);
  
	switch($sec){
		default:
			$middlec.=$xhtml->getImage('icon/magican.jpg','Uxucesi').$xhtml->getBR();
        	$middlec.=$xhtml->getBlock('Mogesalmebi mamaco meomaro, imedia uxucesis rchevebi gamogadgeba am samyaroshi gzis gasakvlevad','b');
		    $middlec.=$xhtml->getBlock('1. Zemot yoveltvis chans sheni saxeli, leveli, sicocxle (ramdeni gaqvs savse/ramdeni gaqvs sul), da oqro.','ql');
		    $middlec.=$xhtml->getBlock('2. Shen yoveltvis gelodeba brdzolebi rogorc sxva meomrebtan aseve sxvadasxva cxovelebtan, magram jer shedi qalaqshi shemdeg magaziashi da iyide shentvis sasurveli iaragebi','ql');
		    $middlec.=$xhtml->getBlock('3. Imisatvis rom chaicva shedzenili iaragebi tu tansacmeli shedi shens chantashi, ipove sheni shedzenili nivti da chaicvi','ql');
		    $middlec.=$xhtml->getBlock('4. Imisatvis rom naxo sheni dzala da sxvadasxva parametrebi daachire shens saxels romelic zeda meniushia:','ql');
		break;
		case 1;
		$middlec.=$xhtml->getBlock('Ra aris gmiris parametrebi?','b');
		$middlec.=$xhtml->getBlock('Leveli- Gansazgvravs shens dones','ql').
		    $xhtml->getBlock('Exp - es aris sheni gamocdilebis maxasiatebeli romelic gemateba brdzolebshi gamarjvebis shemdeg (frchxilebshi mocemulia im exp s raodenoba romelic sachiroa shemdeg levelze gadasasvlelad','ql').
		    $xhtml->getBlock('Oqro - aris fulis erteuli ritac shegidzlia iyido sxvadasxva nivtemi da gadzlierde','ql').
		    $xhtml->getBlock('Mogeba / Cageba- sxva meomrebtan gamarjvebuli tu cagebuli brdzolebis raodenoba','ql').
		    $xhtml->getBlock('Dzala - gansazgvravs dartymis sidzlieres rac metia dzala mit metia mocinaagmdegeze miyenebuli ziani','ql').
		    $xhtml->getBlock('Intuicia - aris parametri romelic gansazgvravs kritikul dartymas rac adzlierebs dzalas 2 jer, rac metia intuicia mit ufro metia kritikuli dartymis shansi','ql').
		    $xhtml->getBlock('Blokireba - aris parametri romelic gansazgvravs blokirebas ris shedegadac mocinaagmdegis dartyma ignorirdeba, magram aris gamonaklisi rodesac mocinaagmdege kritukuls gartyams blokirebis dros dartymis dzala naxevrdeba da gebulob shesabamis zians','ql').
		    $xhtml->getBlock('Moqniloba - aris parametri romelic gansazgvravs dartymis acilebas, rac metia moqniloba mit ufro metia shansi mocinaagmdegis dartymis acilebis','ql').
		    $xhtml->getBlock('Gamdzleoba - gansazgvravs tqvens sicocxles, gamdzleoba gamravlebuli 5 ze aris tqveni maximaluri sicocxle','ql').
		    $xhtml->getBlock('Anti Acileba - ecinaagmdegeba mocinaagmdegis acilebis unars rac metia anti acileba mit naklebia shansi rom mocinaagmdegeb aicilos tqveni dartyma','ql').
		    $xhtml->getBlock('Anti Kritikuli - ecinaagmdegeba mocinaagmdegis kritikul dartymas rac meria anti kritikuli mit naklebia shansi rom mocinaagmdegem dagartyat krituli','ql').
		    $xhtml->getBlock('Anti Blokireba - ecinaagmdegeba mocinaagmdegis blokirebis unars rac metia anti blokireba mit naklebia shansi rom mocinaagmdegem dablokos tqveni dartyma','ql').
		    $xhtml->getBlock('Broni - aris parametri romelic abatilebs dartymis sidzlieres rac metia broni mit naklebs dagartyamt mocinaagmdege','ql');
	    break;
	    case 2;
    	    $middlec.=$xhtml->getBlock('Rogor vebrdzolo sxva motamasheebs?','b');
	        $middlec.=$xhtml->getBlock('imisatvis rom ebrdzolo sxva motamasheebs shedi qalaqshi duelebshi, sadac shegidzlia sheqmna gamocveva da tu mas vinme daetanxmeba daadasturo brdzola da ebrdzolo mas, an miigo sxvisi gamocveva da rodesac mocinaagmdege daadasturebs daicyeba brdzola tqvens shoris','ql');
	    break;
	    case 3;
	        $middlec.=$xhtml->getBlock('Ra xdeba monadiris tyeshi?','b');
	        $middlec.=$xhtml->getBlock('Monadiris tye aris qalaqis axlos sadac sxvadasxva cxovelebi binadroben, tye dayofilia sxvadasxva lokaciebat, titoeul lokaciaze aris 1 an 2 saxeobis cxoveli, rogorc ki cxovels sheexebit icyeba brdzola tqvens shoris, cxovelis moklvlis shemtxvevashi igebt shesabamis exp s da oqros, rac ufro grmad shedixart tyeshi mit ufro dzlieri cxovelebi gxvdebat da shesabamisad mit metia gamarjvebis shemdeg migebuli exp da oqro, gaitvaliscinet yvela cxoveli tqveni levelis matebastan ertad dzlierdeba, da rodesac cxovels tavs daesxmebit misgan tavis dagceva sheudzlebelia sanam brdzola ar damtavrdeba. imisatvis rom tyidan ukan dabrundet miyevit isrebs romlebic ukan mimartulebas gichvenebt qalaqisaken','ql'); 
	    break;
	    case 4;
	        $middlec.=$xhtml->getBlock('Ra aris reitingi?','b');
	        $middlec.=$xhtml->getBlock('Reitingi gansazgvravs motamashis sidzlieres, rac gamoitvleba im parametrebit rac motamashes aqvs, reitingi aseve moqmedebs motamasheebs shoris brdzolis dros migebul exp ze','ql'); 
	    break;
	    case 5;
	        $middlec.=$xhtml->getBlock('Ra aris qaoturi brdzola?','b');
	        $middlec.=$xhtml->getBlock('Qaoturi brdzola aris brdzola ramodenime motamashes shoris, qaoturi brdzolis sheqmnisas irchevt dros tu ramden xanshi daicyos brdzola, brdzolashi monacileoba sheudzlia miigos yvelam vinc brdzolis shemqmnelis levelze ertit metia an naklebi, brdzolis dasacyebad aucilebelia 4 an meti motamashe, drois gasvlis shemdeg ki motamasheebi avtomaturad gadanacildebia or mxared da daicyeba jgufuri brdzola, gamarjvebulad itvleba is mxare romlis erti cevri mainc gadarcheba cocxali','ql'); 
	    break;
	    case 6;
	        $middlec.=$xhtml->getBlock('Levelze gadasvlis exp s cxrili','b');
	        $middlec.='Lvl | Exp | Oqro'.$xhtml->getBR();
	        foreach ($LVL_EXP as $key=>$value){
	        	if($key!=0) $middlec.=$key.'    |'.$value.'|'.$LVL_MONEY[$key].'|'.$xhtml->getBR();
	        }
	    break;
	    case 7;
	        $middlec.=$xhtml->getBlock('Satavgadasavlo brdzolebi','b');
	        $middlec.='Brdzolashi monaciloba shesadzlebelia me-3 levelidan, brdzolis dasacyebad sachiroa minimum 4 kaci, gamarjvebis shemtxvevashi jgufi miigebs exp s oqros da arsebobs shansi rom jgufis titoeulma cevrma miigos sxvadasxva eleqsiri. brdzolashi monacileobis migeba shesadzlebelia 3 saatshi ertxe';
	    break;
	    case 8;
	        $middlec.=$xhtml->getBlock('Madneulis magaro');
	        $middlec.='Magaroshi xdeba sxvadasxva resursebis mopoveba romlis chabarebac sheidzleba samchedloshi an mchedels sheudzlia gamoiyenos nivtebis gasazdzliereblad, yovel mopovebul resursze motamashes emateba meshaxtis gamocdileba rac ufro didia gamocdileba mit ufro didia resursis mopovebis shansi';
	    break;
	    	case 9;
	    	$middlec.=$xhtml->getBlock('Samchedlos shesaxeb');
	    	$middlec.='Samchedloshi shesadzlebelia nivtebis statebis momateba(gadzliereba), amisatvis sachiroa shexvidet samchedloshi shemdeg personalur samchdeloebshi, aq aris chamocerili mchedlebi romlebtac aqvt mchedlis licenzia irchevt nivts romlis gadzlierebac gindat da parametrs, gadzliereba fasiania da yvela mchedels sheudzlia daados tavisi fasi'.$xhtml->getBR();
	    	$middlec.='Mchedlis licenzia girs 500 oqro, licenziis agebis shemtxvevashi motamashes sheudzlia gaadzlieros tavisi nivtebi ufasod da miigos sxva motamasheebisgan oqro nivtebis gadzlierebistvia, yvela parametristvis sachiroa shesabamisi resursi:'.$xhtml->getBR();
	    	$middlec.='+5 Dzala = 20 Rkina'.$xhtml->getBR();
	    	$middlec.='+5 Intuicia = 20 Brinjao'.$xhtml->getBR();
	    	$middlec.='+5 Moqniloba = 20 Vercxli'.$xhtml->getBR();
	    	$middlec.='+5 Blokireba = 20 Tyvia'.$xhtml->getBR();
	    	$middlec.='Erti nivtis gadzliereba shesadzlebelia mxolod ertxel'.$xhtml->getBR();
	    	$middlec.='Mchedlis gamocdilebastan ertad izrdeba shansi rom shemtxvevit nivti gaadzlieros 6-7 it';
	    	break;
	}
	$middlec.=$xhtml->getBlock('Xshirad dasmuli kitxvebi','b');
	$middlec.=$xhtml->getBlock((($sec==1)?'Ra aris gmiris parametrebi?':$xhtml->getLink('index.php?faq&amp;sec=1','Ra aris gmiris parametrebi?')),'ql');
    $middlec.=$xhtml->getBlock((($sec==2)?'Rogor vebrdzolo sxva motamasheebs?':$xhtml->getLink('index.php?faq&amp;sec=2','Rogor vebrdzolo sxva motamasheebs?')),'ql');
    $middlec.=$xhtml->getBlock((($sec==3)?'Ra xdeba monadiris tyeshi?':$xhtml->getLink('index.php?faq&amp;sec=3','Ra xdeba monadiris tyeshi?')),'ql');
    $middlec.=$xhtml->getBlock((($sec==5)?'Ra aris qaoturi brdzola?':$xhtml->getLink('index.php?faq&amp;sec=5','Ra aris qaoturi brdzola?')),'ql');
    $middlec.=$xhtml->getBlock((($sec==4)?'Ra aris reitingi?':$xhtml->getLink('index.php?faq&amp;sec=4','Ra aris reitingi?')),'ql');
    $middlec.=$xhtml->getBlock((($sec==7)?'Satavgadasavlo brdzolebi':$xhtml->getLink('index.php?faq&amp;sec=7','Satavgadasavlo brdzolebi')),'ql');
    $middlec.=$xhtml->getBlock((($sec==8)?'Madneulis magaro':$xhtml->getLink('index.php?faq&amp;sec=8','Madneuli magaro')),'ql');
    $middlec.=$xhtml->getBlock((($sec==9)?'Samchedlos shesaxeb':$xhtml->getLink('index.php?faq&amp;sec=9','Samchedlos shesaxeb')),'ql');
    $middlec.=$xhtml->getBlock((($sec==6)?'Levelze gadasvlis exp s cxrili':$xhtml->getLink('index.php?faq&amp;sec=6','Levelze gadasvlis exp s cxrili')),'ql');
	$downc.=$xhtml->getLink('index.php','Qalaqshi');
	$xhtml->createPage($title,$topc,$middlec,$downc);
}
function INDEX(){
	global $xhtml;
	$title='Online RPG Game Legenda';
	$middlec.=$xhtml->getBlock('Choose your language','zvklist cnt');
	$topc.=$xhtml->getBlock($xhtml->getImage('icon/hd.gif','Legend'),'cnt');
	$middlec.=$xhtml->getBlock($xhtml->getLink('index.php?ver=3',''.$xhtml->getImage('icon/en.png')).$xhtml->getBR().'English','zvklist cnt');
	$middlec.=$xhtml->getBlock($xhtml->getLink('index.php?ver=1',''.$xhtml->getImage('icon/ge.png')).$xhtml->getBR().'Georgian','zvklist cnt');

	$xhtml->createPage($title,$topc,$middlec,$downc);
}

?>