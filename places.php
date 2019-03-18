<?php
///By Qisho
	function placeFunctions($f,$pars=''){
		switch ($f){
			case 'aboutforest';
			return aboutForest();
			break;
			case 'pitPlace';
			return pitPlace();
			break;
			case 'questH';
			return questH();
			break;
		}
    }
    function aboutForest(){
    	global $lang;
    	$source=$lang->forestalert;
    	return $source;
    }
    function pitPlace(){
    	global $db,$xhtml,$gClass,$lang;
    	$litarray=array(1=>$lang->iron,2=>$lang->bronze,3=>$lang->plumbum,4=>$lang->silver,5=>$lang->copper,6=>$lang->aluminium,7=>$lang->platinum);
    	$db->Query("SELECT * FROM rpg_worck WHERE uid='".USER_ID."'");
    	if ($db->getAffectedRows()==1){
    		$row=$db->FetchAssoc();
    		$allt=$row['startt']+$row['durt'];
    		if ((time()-$allt)>=0){
    			$res=$gClass->getPitWorck(PIT_EXP);
    			if ($_SESSION['PIT']!=1) $res=0;
    			$noworck=1;
    			if ($res>0){
    				$point=round(rand(10,20)/10);
    				if (DIG_BONUS>0){
    					$resrand=rand(0,100);
    					if (DIG_BONUS>$resrand){
    						$resum=2*$point;
    					}
    					else{
    					    $resum=1*$point;
    					}
    				}
    				else{
    					$resum=1*$point;
    				}
    			    $db->Query("UPDATE rpg_users SET pitexp=pitexp+".$resum." WHERE id='".USER_ID."'");
    			    $answer=$lang->ufound.':'.$resum.' '.$litarray[$res].$xhtml->getBR();
    			    $db->Query("UPDATE users_resours set type".$res."=type".$res."+".$resum." WHERE uid='".USER_ID."'");
    			    if ($db->getAffectedRows()==0){
    			    	$db->Query("INSERT INTO users_resours SET uid='".USER_ID."', type".$res."=+".$resum."");
    			    }
    			}
    			else{
    				$answer=$lang->minminnotfound.$xhtml->getBR();
    			}
    			$db->Query("DELETE FROM rpg_worck WHERE id='".$row['id']."'");
    		}
    		else{
    			$stop=$allt-time();
    		}
    		
    	}
    	else{
    		$noworck=1;
    	}
    	if (isset($_GET['dig']) && $noworck==1){
    		if (empty($row)){
    			$_SESSION['PIT']=1;
    			$db->Query("INSERT INTO rpg_worck SET uid='".USER_ID."', type=1, startt='".time()."', durt='".PIT_DEF_TIME."'");
    		}
    		$xhtml->redirect('index.php');
    		
    	}
    	if ($noworck!=1){
    		$source.=$xhtml->getLink('index.php?'.rand(100,999).'',$xhtml->getImage('icon/re.png',$lang->refresh)).$xhtml->getBR();
    		$source.=$lang->timeendwork.' ['.$gClass->retMinSec($stop).']';
    	}
    	else {
    	
    		if (!empty($answer))
    			$source.=$answer;
    	$source.=$xhtml->getLink('index.php?dig',$lang->digging).$xhtml->getBR();
    	$source.=$lang->resfoundexp.':'.PIT_EXP.$xhtml->getBR();
    	$source.=$lang->resfoundhaz.':'.$gClass->getPitWorck(PIT_EXP,1).' &#37;';
    	
    	}
    	return $source;
    }
    function questH(){
    	global $db,$xhtml,$lang,$gClass;
    	$db->Query("SELECT * FROM rpg_usersquest WHERE uid='".USER_ID."' order by id desc limit 1");
    	$source.=$xhtml->getBlock($xhtml->getImage('icon/magican.jpg',60,60),'z');
    	if ($db->getAffectedRows()>0){
    		$row=$db->FetchAssoc();
    		$lastq=$row['qid'];
    		$isquest=1;
    		$uq=$row['id'];
    	}
    	else{
    		
    		$lastq=0;
    	}
    	if ($lastq>0){
    	   $db->Query("SELECT * FROM rpg_quest WHERE id='".$lastq."'");
    	   $rowq=$db->FetchAssoc();
    	}
    	    if ($row['status']==2){
    		    $source.=$rowq['answer_'.LANG];
    		    if (!empty($rowq['money'])){
    		    	$source.=$xhtml->getBlock($lang->gift.':'.$rowq['money'].' '.$lang->gold,'b');
    		    }
    		    if (!empty($rowq['gift'])){
    		    	$db->Query("SELECT * FROM rpg_items WHERE itid='".$rowq['gift']."'");
    		    	$itrow=$db->FetchAssoc();
    		    	$gClass->bayItem($itrow,USER_ID,1);
    		    	$source.=$xhtml->getBlock($lang->gift.':'.$xhtml->getSpan($itrow['itname'],'color_red'),'b');
    		    }
    		    $db->Query("UPDATE rpg_usersquest SET status=3 WHERE id='".$uq."'");
    		    $db->Query("UPDATE rpg_users SET actquest=0".($rowq['money']>0?', money=money+'.$rowq['money'].'':'')." WHERE id='".USER_ID."'");
    		    $source.=$xhtml->getLink('index.php','&gt; '.$lang->wnext).$xhtml->getBR();
    		    
    		}
    		elseif($row['status']==1){
    			$source.=$gClass->languageReplace($rowq['waittext_'.LANG],$_SESSION['lang']);
    		}
    		else{
    	    $db->Query("SELECT * FROM rpg_quest WHERE id>'".($lastq)."' order by id asc limit 1");
        	if ($db->getAffectedRows()>0){
    		    $row=$db->FetchAssoc();
    		    if (isset($_GET['do'])){
   			        $db->Query("INSERT INTO rpg_usersquest SET uid='".USER_ID."', qid='".$row['id']."', status=1");
    			    $db->Query("UPDATE rpg_users SET actquest='".$row['id']."' WHERE id='".USER_ID."'");
    		        if ($row['fbot']>0){
    		    	    $_SESSION['lookbots'][]=$row['fbot'];
    		    	    $xhtml->redirect('index.php?b='.$row['fbot'].'');
    		       }
    		       else
    		       $xhtml->redirect('index.php');
    	        }
    		    $source.=$row['about_'.LANG.''].$xhtml->getBR();
    		    
    		    $source.=$xhtml->getBlock($xhtml->getLink('index.php?do',$row['anchor_'.LANG.'']),'b');

    	    }
    	else
    	$source.=$lang->hnotquester;
    		}
    	return $source;
    }
function place($id){
	global $db,$xhtml,$gClass,$lang;
	switch ($id){
		case $id;
		$db->Query("SELECT * FROM rpg_place WHERE id='".$id."'");
		$row=$db->FetchAssoc();
		if (!empty($row['function'])){

			$source.=placeFunctions($row['function']).$xhtml->getBR();
		}
		
		if ($row['isbots']==1){unset($_SESSION['lookbots']);
		    $db->Query("SELECT * FROM rpg_realbots WHERE place='".$id."'");
		    if ($db->getAffectedRows()>0){
		        $ism=1;
		    	$source.=$lang->animals.':'.$xhtml->getBR();
		    	if (empty($alert)){
		    while ($row=$db->FetchAssoc()){
		    	$_SESSION['lookbots'][]=$row['bid'];
		    	if ($row['agres']>0){
		    		$rand=rand(1,100);
		    		if ($rand<$row['agres'] && NOW_LIFE>0){
		    			$xhtml->redirect('index.php?pl='.$id.'&b='.$row['bid'].'',1);
		    		}
		    	}
			   $source.=$xhtml->getLink('index.php?pl='.$id.'&b='.$row['bid'].'',$gClass->languageReplace($row['name'],$_SESSION['lang'])).''.$xhtml->getBR();
		    }
		    	}
		    	else
		    		$source.=$alert;
		    }
		}
		    if ($ism)
		    $source.='----'.$xhtml->getBR();
		    $db->Query("SELECT * FROM rpg_place WHERE parent='".$id."'");
	        while ($r=$db->FetchAssoc()){
		            $source.='=&gt;'.$xhtml->getLink('index.php?pl='.$r['id'].'',$gClass->languageReplace($r['pname_'.LANG.''],$_SESSION['lang'])).$xhtml->getBR();
	        }
		    $db->Query("SELECT s.* FROM rpg_place as c left join rpg_place as s on c.parent=s.id WHERE c.id='".$id."' and c.parent!=0");
		    
	        while ($r=$db->FetchAssoc()){
		            $source.='&lt;='.$xhtml->getLink('index.php?pl='.$r['id'].'',$gClass->languageReplace($r['pname_'.LANG.''],$_SESSION['lang']));
	        }
	        $forestRange=range(3,8);
      	    if (in_array(USER_PLACE,$forestRange))
	            $source.=$xhtml->getBlock($lang->huntToday.':('.((USER_LVL+1)*20).'/'.$gClass->countTodayHunt(0).')','b '.((USER_LVL+1)*20==$gClass->countTodayHunt(0)?'color_red':'').'');
		break;
	}
	
	return $source;
}
?>
