<?php

///By Qisho
	class game {
		function languageReplace($str,$langid){
			$strlow=strtolower($str);
        	$geoarr=array('gadzlierebuli parametrebi',
        		'dzala',
        		'moqniloba',
        		'intuicia',
        		'blokireba',
        		'gamdzleoba',
        		'sicocxlis scrafi shevseba',
        		'sicocxlis shevseba',
        		'sxva parametrebi',
             	'mela',
            	'tura',
        	    'gareuli taxi',
        	    'gveli kobra','bizoni',
        	'tetri mgeli',
        	'aftari',
        	'ruxi mgeli',
        	'ruxi datvi',
        	'vefxvi',
        	'shavi datvi',
        	'anakonda',
        	'niangi',
        	'xvliki varani',
        	'lomi',
        	'tetri drakoni',
        	'uxucesi',
        	'Mopovebis shansi'
        	);
	        $ruarr=array('Улучшени параметры','Сила','Ловкость','Интуиция','Блокировка','стойкость','Быстрое восстановление жизни','Восстановление жизни','Другие параметры','Лиса',
	        'Шакал',
	        'Кабан',
	        'Змея Кобра',
	        'Бизон',
	        'Белый Волк',
	        'Гиена',
	        'Серый Волк',
	        'Серый медведь',
	        'Тигр',
	        'Черный медведь',
	        'Анаконда',
	        'Крокодил',
	        'Ящерица',
	        'Лев',
	        'Белый дракон',
	        'Старейшина'
	        );
	        $enarr=array('Strengthen stats','Strength','Flexibility','Intuition','Blocking','Life','Fast relife','Relife','Other stats',
	        'Fox',
	        'Jackal',
	        'Boar',
	        'Cobra snake',
	        'Bison',
	        'White Wolf',
	        'Hyena',
	        'Grey Wolf',
	        'Grey bear',
	        'Tiger',
	        'Black Bear',
	        'Anakonda',
	        'Crocodile',
	        'Lizard',
	        'Leo',
	        'white Dragon',
	        'City Elder',
	        'The probability of obtaining'
	        );
	        if ($langid==2) {
	        	$str=$strlow;
	        	foreach ($geoarr as $key=>$value){
	        		$str=str_replace($value,$ruarr[$key],$str);
	        	}
	        }
	        if ($langid==3) {
	        	$str=$strlow;
	        	foreach ($geoarr as $key=>$value){
	        		$str=str_replace($value,$enarr[$key],$str);
	        	}
	        }
	        
	        
	        return $str;
        }

		function life_updater($cur_life,$life_point,$last_update,$up_speed,$bonus=0){
			//$up_speed  time for max life
			
			$max_life=$life_point*5+$bonus;
			$time_interval=time()-$last_update;
			$sec_life_update=$max_life/$up_speed;
			$add_life=round($sec_life_update*$time_interval);
			$refresh_life=$cur_life+$add_life;
			$ret_life=($refresh_life>$max_life?$max_life:$refresh_life);
			return $ret_life;
		}
		function doClansUpdate(){
			global $db;
			$db->Query("SELECT sum(us.exp)AS sumexp,us.clanid FROM rpg_users AS us WHERE us.clanid>0 GROUP BY us.clanid");
			WHILE ($row=$db->FetchAssoc()){
				$rt=round($row['sumexp']/10000);
				mysql_query("UPDATE rpg_clans SET rating='".$rt."' WHERE id='".$row['clanid']."'");
			}
		}
		function addLifeMana($uid,$life,$mana){
			global $db;
			$allLife=LIFE_POINTS*5;
			$allMana=MANA_POINTS*5;
			$l=NOW_LIFE+$life;
			$m=NOW_MANA+$mana;
			$l=($l>$allLife?$allLife:$l);
			$m=($m>$allMana?$allMana:$m);
			$db->Query("UPDATE rpg_users SET life=".$l.", mana=".$m." WHERE id='".$uid."'");
		}
		function retMinSec($sec,$t=0){
            global $lang;
			if ($sec<60){
				$s=$sec;
			}
			if ($sec>=60 && $sec<3600){
				$m=floor($sec/60);
				$s=$sec-($m*60);
				//$s=bcmod($sec,60);
				
			}
			if ($sec>=3600 && $sec<86400){
				$h=floor($sec/3600);
				$hm=$sec-($h*3600);
				//$hm=bcmod($sec,3600);
				$m=floor($hm/60);
				//$s=bcmod($hm,60);
				$s=$hm-($m*60);
			}
			if ($sec>=86400){
				$d=floor($sec/86400);
				$dh=$sec-($d*86400);
				//$dh=bcmod($sec,86400);
				$h=floor($dh/3600);
				//$hm=bcmod($sec,3600);
				$hm=$dh-($h*3600);
				$m=floor($hm/60);
				$s=$hm-($m*60);
				//$s=bcmod($hm,60);
			}
			if (strlen($s)==1) $s='0'.$s;
			if (strlen($m)==1) $m='0'.$m;
			if (strlen($h)==1) $h='0'.$h;
			if ($d>0) $str=$d.' '.$lang->day.' ';
			if ($h>0) $str.=$h.':';
			if (empty($m)) $m='00';
			if ($d>0 && $h=='00') return $str;
			$str.=$m.':'.$s.'';
			return $str;
		}
		function downMenu(){
			global $xhtml,$lang;
			//$source=$xhtml->getLink('index.php?person',$lang->heroe).'|';
	        $source.=$xhtml->getLink('index.php?bag',$lang->bag).$xhtml->getBR();
	        $source=$xhtml->getBlock($source,'downmenu');
	        return $source;
		}
		function removeBreakItems($uid,$items){
			global $db,$xhtml,$lang;
			$cit=count($items);
			foreach ($items as $value){
				$s++;
				$q.="utid='".$value['id']."' ".($s!=$cit?" OR ":"")."";
				if ($value['type']!=21){
				    $postmsg.=$value['name'].'['.$value['lvl'].']'.$xhtml->getBR();
				}
			}
			$postmsg=$lang->uitemshborken.$xhtml->getBR().$postmsg;
			$db->Query("DELETE FROM users_items WHERE ".$q."");
			$db->Query("INSERT INTO rpg_postbag SET slogin='System', reid='".$uid."', msg='".$postmsg."', readit=1, sdate=NOW()");
			$lid=$db->lastInsertID();
			$db->Query("UPDATE rpg_users SET postid='".$lid."' WHERE id='".$uid."'");
		}
		function doBotFight($bid,$mystats,$botStats,$userBot=''){
			global $db;
			if ($userBot==1) {
			    $mystats['name']=$mystats['aname'].'('.$mystats['mainname'].')';
			    $mystats['id']=$mystats['aid'];
			}
			    $actions=$this->actionRendomizer($mystats,$botStats);
			    $userAtac=$this->pointRendomizer($mystats['strength']);
			    $uhit=$userAtac;
			    $botAtac=$this->pointRendomizer($botStats['strength']);
			    $bhit=$botAtac;
			    $userBron=$this->pointRendomizer($mystats['bron'],1);
			    $botBron=$this->pointRendomizer($botStats['bron'],1);
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
			    if ($botStats['intelect']>0) {
			        $botmaghit=$this->pointRendomizer($botStats['intelect']);
			        $bothit=$bothit+$botmaghit;
			    }
   			    $getExp=($botStats['get_exp']>0?$this->getBotExp($userhit,$botStats['life'],$botStats['rlife'],$botStats['get_exp']):0);
			    $getDrop=($botStats['get_drop']>0?$this->getBotDrop($botStats['get_exp'],$getExp,$botStats['get_drop']):0);
			    $userStats['life']=$mystats['life']-$bothit;
			    $userStats['life']=($userStats['life']<0?0:$userStats['life']);
			    $botStats['life']=$botStats['life']-$userhit;
			    $botStats['life']=($botStats['life']<0?0:$botStats['life']);
			    if ($userBot==1){
			    	$db->Query("UPDATE rpg_usersanimals SET life='".$userStats['life']."' WHERE aid='".$mystats['aid']."'");
			    }
			    else{
			        $db->Query("UPDATE rpg_users SET life='".$userStats['life']."' WHERE id='".USER_ID."'");
			    }
			    $db->Query("UPDATE rpg_bots SET life='".$botStats['life']."' WHERE bid='".$botStats['bid']."'");
			    $db->Query("INSERT INTO rpg_botbattleslogs SET bid='".$bid."', userID='".USER_ID."', player1='".$mystats['name']."', player2='".$botStats['name']."', botID='".$botStats['bid']."', userhit='".$userhit."', bothit='".$bothit."', botmaghit='".$botmaghit."', userblock='".$userSBlock."', botblock='".$botSBlock."', useract='".$useract."', botact='".$botact."', acttime=NOW()");
			    $db->Query("UPDATE rpg_botbattlesaction SET acttime='".time()."', userHits=userHits+".$userhit.", userExp=userExp+".$getExp.", userDrop=userDrop+".$getDrop." WHERE bid='".$bid."' AND ".($userBot==1?"userBot='".$mystats['id']."'":"userID='".USER_ID."'")."");
			    $lifes['user']=$userStats['life'];
			    $lifes['bot']=$botStats['life'];
			    return $lifes;
		}

		function botBattlePlayers($bid){
			global $db,$xhtml;
			$db->Query("SELECT act.userID,act.userBot,act.botID,act.status,u.life as ulife,u.login,b.name,b.life,an.aname,an.mainname,an.life as alife FROM rpg_botbattlesaction as act LEFT JOIN rpg_users as u ON act.userID=u.id LEFT JOIN rpg_bots as b ON b.bid=act.botID LEFT JOIN rpg_usersanimals as an ON an.aid=act.userBot WHERE act.bid='".$bid."'");
			while ($row=$db->FetchAssoc()){
				if ($row['status']==1){
					$color='color_red';
				}
				if ($row['userID']>0) {
				    $user.=$xhtml->getSpan($row['login'],$color).'['.$row['ulife'].'] ';
				}
				if ($row['userBot']>0){
					$user.=$xhtml->getSpan($row['aname'].'('.$row['mainname'].')',$color).'['.$row['alife'].'] ';
				}
				if ($row['botID']>0) {
				    $bots.=$xhtml->getSpan($this->languageReplace($row['name'],$_SESSION['lang']),$color).'('.$row['life'].') ';
				    
				}
				unset($color);
			}
			return $user.' VS '.$bots;
		}
		function updateLevel($exp,$clvl){
			global $db,$LVL_EXP,$LVL_MONEY;
			$minExp=$LVL_EXP[$clvl];
			$maxExp=$LVL_EXP[$clvl+1];
			$rasplus[1]='strength=strength+1';
			$rasplus[2]='accuracy=accuracy+1';
			$rasplus[3]='dexterity=dexterity+1';
			$rasplus[4]='bron=bron+1';
			$rasplus[5]='blocking=blocking+1';
			if ($exp>=$maxExp){
				$this->questSubmiter(5);
				$plius['lvl']=1;
				$plius['stats']=5;
				$plius['money']=$LVL_MONEY[$clvl+$plius['lvl']];
				$db->Query("UPDATE rpg_users SET level=level+".$plius['lvl'].", money=money+".$plius['money'].", unused_points=unused_points+".$plius['stats'].", ".$rasplus[USER_RASE]." WHERE id='".USER_ID."'");
			}
		}
		function updateAnimalLevel($exp,$clvl,$type){
			global $ANIMAL_EXP,$db;
			$minExp=$ANIMAL_EXP[$clvl];
			$maxExp=$ANIMAL_EXP[$clvl+1];
			$animals[1]=array('type'=>1,'fmeal'=>2,'mainname'=>$lang->wolf,'alevel'=>1,'strength'=>20,'accuracy'=>0,'dexterity'=>20,'blocking'=>0,'ant_accuracy'=>20,'ant_dexterity'=>5,'ant_blocking'=>10,'life'=>200,'rlife'=>'200','bron'=>5,'price'=>500);
	        $animals[2]=array('type'=>2,'fmeal'=>2,'mainname'=>$lang->tiger,'alevel'=>1,'strength'=>20,'accuracy'=>20,'dexterity'=>0,'blocking'=>0,'ant_accuracy'=>5,'ant_dexterity'=>10,'ant_blocking'=>20,'life'=>200,'rlife'=>'200','bron'=>5,'price'=>500);
	        $animals[3]=array('type'=>3,'fmeal'=>2,'mainname'=>$lang->bear,'alevel'=>1,'strength'=>20,'accuracy'=>0,'dexterity'=>0,'blocking'=>20,'ant_accuracy'=>20,'ant_dexterity'=>10,'ant_blocking'=>5,'life'=>200,'rlife'=>'200','bron'=>5,'price'=>500);
	        $animals[4]=array('type'=>4,'fmeal'=>5,'mainname'=>$lang->dragon,'alevel'=>1,'strength'=>30,'accuracy'=>15,'dexterity'=>15,'blocking'=>15,'ant_accuracy'=>10,'ant_dexterity'=>10,'ant_blocking'=>10,'life'=>300,'rlife'=>'300','bron'=>10,'price'=>2000);
	        if ($exp>=$maxExp){
	        	$plius['alevel']=1;
	        	$plius['strength']=$animals[$type]['strength'];
	        	$plius['accuracy']=$animals[$type]['accuracy'];
	        	$plius['dexterity']=$animals[$type]['dexterity'];
	        	$plius['blocking']=$animals[$type]['blocking'];
	        	$plius['ant_accuracy']=$animals[$type]['ant_accuracy'];
	        	$plius['ant_dexterity']=$animals[$type]['ant_dexterity'];
	        	$plius['ant_blocking']=$animals[$type]['ant_blocking'];
	        	$plius['life']=$animals[$type]['life'];
	        	$plius['rlife']=$animals[$type]['rlife'];
	        	$plius['bron']=$animals[$type]['bron'];
	        	$db->Query("UPDATE rpg_usersanimals SET alevel=alevel+".$plius['alevel'].", strength=strength+".$plius['strength'].", accuracy=accuracy+".$plius['accuracy'].", dexterity=dexterity+".$plius['dexterity'].", blocking=blocking+".$plius['blocking'].", ant_accuracy=ant_accuracy+".$plius['ant_accuracy'].", ant_dexterity=ant_dexterity+".$plius['ant_dexterity'].", ant_blocking=ant_blocking+".$plius['ant_blocking'].", life=life+".$plius['life'].", rlife=rlife+".$plius['rlife'].", bron=bron+".$plius['bron']." WHERE usid='".USER_ID."'");
	        }
		}
		function countStatsRate($stats){
				$statsSum=$stats['strength']+$stats['accuracy']+$stats['dexterity']+$stats['blocking']+$stats['intelect']+$stats['life_points']+$stats['mana_points'];
				$anStatsSum=$stats['ant_accuracy']+$stats['ant_dexterity']+$stats['ant_blocking'];
				$bronStat=$stats['bron'];
				$rate=$statsSum*0.2+$anStatsSum*0.18+$bronStat*0.2;
				$rate=round($rate,1);
				return $rate;
				
		}
		function countOwnStatsRate(){
				$statsSum=STRENGTH+ACCURACY+DEXTERITY+BLOCKING+INTELECT+LIFE_POINTS+MANA_POINTS;
				$anStatsSum=ANT_ACCURACY+ANT_DEXTERITY+ANT_BLOCKING;
				$bronStat=BRON;
				$rate=$statsSum*0.2+$anStatsSum*0.18+$bronStat*0.2;
				$rate=round($rate,1);
				return $rate;
				
		}
		function bayItem($item,$myid,$free=0){
			global $db,$lang;
			$db->Query("SELECT money FROM rpg_users WHERE id='".$myid."'");
			$row=$db->FetchAssoc();
			if ($free=0) $row['money']=0;
				if ($row['money']>=$item['price']){
					if ($free==0){
					    $db->Query("UPDATE rpg_items SET csum=csum-1 WHERE itid='".$item['itid']."'");
					    $db->Query("UPDATE rpg_users SET money=money-".$item['price']." WHERE id='".$myid."'");
					}
				    $db->Query("INSERT into users_items SET usid='".$myid."',
				    itid='".$item['itid']."',
				    itname='".$item['itname']."',		 	 	 	 	 	 	 
                	lvl='".$item['lvl']."',
 	                ittype='".$item['ittype']."',	 	 	 	 	 	 
 	                strength_p='".$item['strength_p']."',
                	accuracy_p='".$item['accuracy_p']."',
                	dexterity_p='".$item['dexterity_p']."',
 	                blocking_p='".$item['blocking_p']."',
 	                life_p='".$item['life_p']."',
 	                mana_p='".$item['mana_p']."',		 	 	 	 	 	 	 
                	ant_accuracy_p='".$item['ant_accuracy_p']."',
 	                ant_dexterity_p='".$item['ant_dexterity_p']."',
 	                ant_blocking_p='".$item['ant_blocking_p']."',
 	                bron_p='".$item['bron_p']."',
 	                dig_p='".$item['dig_p']."',
 	                rest_life='".$item['rest_life']."',
 	                rest_mana='".$item['rest_mana']."',
 	                acttime='".$item['acttime']."',
 	                description='".$item['description']."',
 	                img='".$item['img']."',
 	                price='".$item['price']."',
 	                broktime='".(time()+(empty($item['broktime'])?604800:$item['broktime']))."'				    
				    ");
					$ret=$lang->uhavebuyitem;
				}
				else $ret=$lang->uhnotenougthmoney;
				
			return $ret;
		}
		function countBagItems(){
			global $db;
			$db->Query("SELECT count(utid) as itsum FROM users_items WHERE usid='".USER_ID."' AND act=0");
	        $items=$db->FetchAssoc();
	        return $items['itsum'];
		}
		function pointRendomizer($point,$st='0'){
			$rad=round($point-($point/10));
			$ret=rand($rad,$point);
			/*
			if ($st==1){
				$ret=rand(0,$point);
			}
			*/
			return $ret;
			
		}
		function getLifeManaLine(){
			global $xhtml;
			//$pstats=$this->countStats(USER_ID);
			//$points=$this->getUserPoints(USER_ID);
			return $xhtml->getLink('index.php?person',USER_NAME.'['.USER_LVL.'] ').$xhtml->getImage('icon/h.png','L:').''.$xhtml->getSpan((DEFINED('RENOW_LIFE')?RENOW_LIFE:NOW_LIFE).'/'.(LIFE_POINTS*5),'color_red').'|'.
				//'[M: '.$xhtml->getSpan($pstats['mana'].'/'.($pstats['mana_points']*5),'color_blue').']'.
				$xhtml->getImage('icon/g.png','O').MONEY.(NEWPOST>0?' '.$xhtml->getLink('index.php?postman',$xhtml->getImage('icon/np.gif','16','16','N')):'').$xhtml->getBR();
		}
		function getBotExp($hit,$life,$rlife,$exp){
			if ($hit>$life) $hit=$life;
			$fone=$exp/$rlife;
			$getexp=$hit*$fone;
			return $getexp;
		}
		function getBotDrop($botexp,$getexp,$drop){
			$getdrop=(($getexp/$botexp)*$drop);
			return $getdrop;
		}
function getUserPoints($uid){
	global $db;
	$db->Query("SELECT life,mana,life_points,mana_points FROM rpg_users WHERE id='".$uid."'");
	$stats=$db->FetchAssoc();
	if ($stats['life']<0) $stats['life']=0;
	return $stats;
}
		function countStats($uid,$inf=''){
			global $db;
			$db->Query("SELECT login,level,exp,money,wins,loses,strength,accuracy,dexterity,blocking,intelect,life_points,mana_points,life,mana,ant_accuracy,ant_dexterity,ant_blocking,bron,pitexp,huntexp".$inf." FROM rpg_users WHERE id='".$uid."'");
			$stats=$db->FetchAssoc();
			$db->Query("SELECT * FROM users_items WHERE usid='".$uid."' and act>0 and (acttime=0 || acttime>".time().")");
			while ($row=$db->FetchAssoc()){
				if ($row['ittype']==21 || !empty($row['rest_life'])){
					$stats['life_speed']+=$row['rest_life'];
				}
				$stats['strength']+=$row['strength_p'];
				$stats['accuracy']+=$row['accuracy_p'];
				$stats['dexterity']+=$row['dexterity_p'];
				$stats['blocking']+=$row['blocking_p'];
				$stats['intelect']+=$row['intelect_p'];
				$stats['life_points']+=$row['life_p'];
				$stats['mana_points']+=$row['mana_p'];
				$stats['ant_accuracy']+=$row['ant_accuracy_p'];
				$stats['ant_dexterity']+=$row['ant_dexterity_p'];
				$stats['ant_blocking']+=$row['ant_blocking_p'];
				$stats['bron']+=$row['bron_p'];
				
				$stats['life']+=$row['life'];
				$stats['mana']+=$row['mana'];
			}
			return $stats;
		}
		function countAddingStats($uid){
			global $db;
			$db->Query("SELECT * FROM users_items WHERE usid='".$uid."' and act>0 and (acttime=0 || acttime>".time().")");
			while ($row=$db->FetchAssoc()){
				if ($row['ittype']==21 || !empty($row['rest_life'])){
					$stats['life_speed']+=$row['rest_life'];
				}
				$stats['strength']+=$row['strength_p'];
				$stats['accuracy']+=$row['accuracy_p'];
				$stats['dexterity']+=$row['dexterity_p'];
				$stats['blocking']+=$row['blocking_p'];
				$stats['intelect']+=$row['intelect_p'];
				$stats['life_points']+=$row['life_p'];
				$stats['mana_points']+=$row['mana_p'];
				$stats['ant_accuracy']+=$row['ant_accuracy_p'];
				$stats['ant_dexterity']+=$row['ant_dexterity_p'];
				$stats['ant_blocking']+=$row['ant_blocking_p'];
				$stats['bron']+=$row['bron_p'];
				$stats['life']+=$row['life'];
				$stats['mana']+=$row['mana'];
				return $stats;
		    }
		}
		function getUserPars($uid){
			global $db;
			$db->Query("SELECT * FROM rpg_users WHERE id='".$uid."'");
			if ($row=$db->FetchAssoc()){
            $_SESSION['lang']=$row['lang'];
			$db->Query("SELECT * FROM users_items WHERE usid='".$uid."' and act>0 and (acttime=0 || acttime>".time().")");
			while ($stats=$db->FetchAssoc()){
				if ($stats['ittype']==21 || !empty($stats['rest_life'])){
					$row['life_speed']+=$stats['rest_life'];
				}
				//echo $stats['ant_accuracy_p'];
				$row['strength']+=$stats['strength_p'];
				$row['accuracy']+=$stats['accuracy_p'];
				$row['dexterity']+=$stats['dexterity_p'];
				$row['blocking']+=$stats['blocking_p'];
				$row['intelect']+=$stats['intelect_p'];
				$row['life_points']+=$stats['life_p'];
				$row['mana_points']+=$stats['mana_p'];
				$row['ant_accuracy']+=$stats['ant_accuracy_p'];
				$row['ant_dexterity']+=$stats['ant_dexterity_p'];
				$row['ant_blocking']+=$stats['ant_blocking_p'];
				$row['bron']+=$stats['bron_p'];
				$row['life']+=$stats['life'];
				$row['mana']+=$stats['mana'];
				$row['dig']+=$stats['dig_p'];

	
				
			}
			if ($row['bann']>time()) unset($_SESSION['user_id']);
				DEFINE(USER_NAME,$row['login']);
				DEFINE(USER_PLACE,$row['place']);
				DEFINE(TODAYHUNT,$row['huntcount']);
				DEFINE(USER_LVL,$row['level']);
				DEFINE(RASA,$row['rasa']);
				DEFINE(MONEY,$row['money']);
				DEFINE(ANIMAL,$row['animal']);
				DEFINE(CLANID,$row['clanid']);
				DEFINE(LIFE_POINTS,$row['life_points']);
				DEFINE(MANA_POINTS,$row['mana_points']);
				DEFINE(NOW_LIFE,$row['life']);
				DEFINE(NOW_MANA,$row['mana']);
				DEFINE(STRENGTH,$row['strength']);
				DEFINE(ACCURACY,$row['accuracy']);
				DEFINE(DEXTERITY,$row['dexterity']);
				DEFINE(BLOCKING,$row['blocking']);
				DEFINE(INTELECT,$row['intelect']);
				DEFINE(DIG_BONUS,$row['dig']);
			    DEFINE(ANT_ACCURACY,$row['ant_accuracy']);
			    DEFINE(ANT_DEXTERITY,$row['ant_dexterity']);
			    DEFINE(ANT_BLOCKING,$row['ant_blocking']);
			    DEFINE(BRON,$row['bron']);
				DEFINE(LIFE_SPEED,$row['life_speed']);
				DEFINE(USER_RASE,$row['rasa']);
				DEFINE(LAST_UPDATE,$row['last_update']);
				DEFINE(LAST_INST,$row['last_inst']);
				DEFINE(USER_EXP,$row['exp']);
				DEFINE(PIT_EXP,$row['pitexp']);
				DEFINE(HUNT_EXP,$row['huntexp']);
				DEFINE(QUEST,$row['actquest']);
				DEFINE(NEWPOST,$row['postid']);
				if (!empty($row['act'])){
				    $act=explode('_',$row['act']);
				    DEFINE(ACT_TYPE,$act[0]);
				    DEFINE(USER_ACT,$act[1]);
				}
				else DEFINE(USER_ACT,0);
				if (!empty($row['wbattle'])){
					$wb=explode('_',$row['wbattle']);
					DEFINE(WAIT_BATTLE_TYPE,$wb[0]);
					DEFINE(WAIT_BATTLE,$wb[1]);
				}
				else DEFINE(WAIT_BATTLE,0);
				DEFINE(USER_ID,$row['id']);
				DEFINE(VIEW_BATTLE,$row['viewb']);
			}
		}
		function acteptDuel($id){
			global $db;
			$db->Query("SELECT * FROM rpg_userduelbattles WHERE id='".$id."' AND user1='".USER_ID."' and user2!=0 AND endtime>'".time()."' AND status!=3");
			if ($db->getAffectedRows()>0){
				$row=$db->FetchAssoc();
				$db->Query("UPDATE rpg_users SET act='D_".$id."', wbattle=0 WHERE id='".$row['user1']."' OR id='".$row['user2']."'");
				$db->Query("UPDATE rpg_userduelbattles SET status=1, acttime='".time()."' WHERE id='".$id."'");
			}		
		}
		function declineDuel($id){
			global $db;
			$db->Query("SELECT user2 FROM rpg_userduelbattles WHERE id='".$id."' AND user1='".USER_ID."' and user2!=0 AND endtime>'".time()."' AND status!=3");
			$row=$db->FetchAssoc();
			$db->Query("UPDATE rpg_users SET wbattle=0 WHERE id='".$row['user2']."'");
			$db->Query("UPDATE rpg_userduelbattles SET user2=0, status=0  WHERE id='".$id."'");

		}
		function startAtacDuel($atid,$defid){
			global $xhtml,$db,$lang;
			$db->Query("SELECT id FROM rpg_users WHERE id='".$defid."' AND level<='".USER_LVL."' AND life>=15 AND act=0 AND id!='".USER_ID."' AND place<=1");
			if ($db->getAffectedRows()>0){
			    $db->Query("INSERT INTO rpg_userduelbattles SET user1='".$defid."', user2='".$atid."', ctime='".time()."', endtime='".(time()+DUEL_WAIT_TIME)."', btime='".DUEL_WAIT_TIME."', status='1', acttime='".time()."', type=1");
			    $id=$db->lastInsertID();
			    $db->Query("UPDATE rpg_users SET act='D_".$id."' WHERE id='".$atid."' OR id='".$defid."'");
			    $xhtml->redirect('index.php?arena','1');
			}
			else return $lang->cnotfigthtoop;
		}
		function joinToDuel($did){
			global $db;
			$db->Query("SELECT * FROM rpg_userduelbattles WHERE id='".$did."' AND (minlvl<='".USER_LVL."' AND maxlvl>='".USER_LVL."') AND user1!='".USER_ID."' AND user2!=0 AND endtime>'".time()."'  AND status!=3");
			if ($db->getAffectedRows()==0){
				$db->Query("UPDATE rpg_userduelbattles SET user2='".USER_ID."' WHERE id='".$did."'");
				$db->Query("UPDATE rpg_users SET wbattle='D_".$did."' WHERE id='".USER_ID."'");
			}
		}
		function joinToGroupBattle($did){
			global $db,$gClass;
			$db->Query("SELECT * FROM rpg_groupbattle WHERE id='".$did."' AND (minlvl<='".USER_LVL."' AND maxlvl>='".USER_LVL."') AND endtime>'".time()."' AND status=0");
			if ($db->getAffectedRows()==1){
				$pstats=$gClass->countStats(USER_ID);
	            $statsRate=$gClass->countStatsRate($pstats);
				$db->Query("INSERT INTO rpg_gbusers SET gid='".$did."', userID='".USER_ID."', login='".USER_NAME."', lvl='".USER_LVL."', rate='".$statsRate."'");
				$db->Query("UPDATE rpg_users SET wbattle='G_".$did."' WHERE id='".USER_ID."'");
			}
		}
		function joinToInstant($id){
			global $db;
 			$db->Query("SELECT * FROM rpg_instantbattle WHERE id='".$id."' AND endtime>'".time()."' AND status=0");
			if ($db->getAffectedRows()==1){
				$r=$db->FetchAssoc();
				if (($r['minlvl']<=USER_LVL) && ($r['maxlvl']>=USER_LVL)){
				    $db->Query("INSERT INTO rpg_instantusers SET insid='".$id."', userID='".USER_ID."', login='".USER_NAME."', lvl='".USER_LVL."'");
				    $db->Query("UPDATE rpg_users SET wbattle='INST_".$id."' WHERE id='".USER_ID."'");
				}
				
			}
		}

		function updateDuels($wb){
			global $xhtml,$db;
			$q=mysql_query("SELECT * FROM rpg_userduelbattles WHERE endtime<'".time()."' and  id='".$wb."'");
			if (mysql_affected_rows()>0){
			    $row=mysql_fetch_assoc($q);
			    

				$db->Query("UPDATE rpg_users SET wbattle=0 WHERE id='".$row['user1']."'");
				if ($row['user2']>0){
					$db->Query("UPDATE rpg_users SET wbattle=0 WHERE id='".$row['user2']."'");
				}
				$db->Query("UPDATE rpg_userduelbattles SET status=3 WHERE id='".$row['id']."'");
				$db->Query("UPDATE rpg_users SET wbattle=0 WHERE id='".USER_ID."'");
				$xhtml->redirect('index.php?arena',1);
			}


			
		}
		function getUserExp($hit,$myrate,$oprate,$tp=''){
			$ratio=$oprate/$myrate;
			if ($tp=='G'){
			   if ($oprate>100) $ncoef=$oprate/100;
			   if ($ncoef>1) $ratio=$ratio*$ncoef;
			}
			$getexp=$ratio*$hit;
			
			$getexp=round($getexp,2);
			return $getexp;
		}
        function doFight($bid,$user,$myP,$opP){
        	global $db;
        	   $user1Stats=$this->countStats($user[1]['ID']);
		       $user2Stats=$this->countStats($user[2]['ID']);
		       $user[1]['rate']=$this->countStatsRate($user1Stats);
		       $user[2]['rate']=$this->countStatsRate($user2Stats);
		       $actions=$this->actionRendomizer($user1Stats,$user2Stats);
		       $user1Atac=$this->pointRendomizer($user1Stats['strength']);
		       $user1hit=$user1Atac;
		       $user2Atac=$this->pointRendomizer($user2Stats['strength']);
		       $user2hit=$user2Atac;
		       $user1Bron=$this->pointRendomizer($user1Stats['bron'],1);
		       $user2Bron=$this->pointRendomizer($user2Stats['bron'],1);
		       if ($actions['blk1']==1){
		       	   $user1SBlock=1;
		       	   if ($actions['dex2']!=1){
		       	   	   $user2Atac=0;
		       	   }
		       }
		       if ($actions['blk2']==1){
		       	   $user2SBlock=1;
		       	   if ($actions['dex1']!=1){
		       	   	   $user1Atac=0;
		       	   }
		       	  
		       }
			   if ($actions['dex1']==1){
			    	if ($user2SBlock==0){
			    		
			    	    $user1Atac=($user1hit*2);
			    	}
			    	else $user1Atac=$user1hit;
			    	
			    	$user1act=2;
			    }
			    if ($actions['dex2']==1){
			    	if ($user1SBlock==0){
			    	    $user2Atac=($user2hit*2);
			    	}
			    	else{
			    		$user2Atac=$user2hit;
			    	}
			    	$user2act=2;
			    }
			    if ($actions['acc1']==1){
			    	$user2Atac=0;
			    	$user1act+=1;
			    }
			    if ($actions['acc2']==1){
			    	$user1Atac=0;
			    	$user2act+=1;
			    }
			    ///gasaketebelia brdzolis dasruleba
			    $user1hit=$user1Atac-$user2Bron;
			    if ($user1hit<0) $user1hit=0;
			    $user2hit=$user2Atac-$user1Bron;
			    if ($user2hit<0) $user2hit=0;
			    $res['bid']=$bid;
			    $res['user1']=$user[1]['ID'];
			    $res['user2']=$user[2]['ID'];
			    $res['user1hit']=$user1hit;
			    $res['user2hit']=$user2hit;
			    $res['user1block']=$user1SBlock;
			    $res['user2block']=$user2SBlock;
			    $res['user1act']=$user1act;
			    $res['user2act']=$user2act;
			    $user1Exp=0;
			    $user2Exp=0;

			    if ($user1hit>0) {
			     $user1Exp=$this->getUserExp($user1hit,$user[1]['rate'],$user[2]['rate']);
			    }
			    if ($user2hit>0) {
			     $user2Exp=$this->getUserExp($user2hit,$user[2]['rate'],$user[1]['rate']);
			    }
			    $this->doDuelBattleLog($res);
   			    $user1Stats['life']=$user1Stats['life']-$user2hit;
			    $user1Stats['life']=($user1Stats['life']<0?0:$user1Stats['life']);
			    $user2Stats['life']=$user2Stats['life']-$user1hit;
			    $user2Stats['life']=($user2Stats['life']<0?0:$user2Stats['life']);
			    $db->Query("UPDATE rpg_users SET life='".$user1Stats['life']."' WHERE id='".$user[1]['ID']."'");
			    $db->Query("UPDATE rpg_users SET life='".$user2Stats['life']."' WHERE id='".$user[2]['ID']."'");
			    if ($user1Stats['life']==0 || $user2Stats['life']==0){
			    	if ($user1Stats['life']==0 && $user2Stats['life']!=0) $duelEnd=2;
			    	if ($user2Stats['life']==0 && $user1Stats['life']!=0) $duelEnd=1;
			    	if ($user1Stats['life']==0 && $user2Stats['life']==0) $duelEnd=3;
			    }
			    $db->Query("UPDATE rpg_userduelbattles SET hit".$myP."=0, hit".$opP."=0, user1Hits=user1Hits+".$user1hit.", user2Hits=user2Hits+".$user2hit.", user1Exp=user1Exp+".$user1Exp.", user2Exp=user2Exp+".$user2Exp.", ".($duelEnd>0?"winer='".$duelEnd."', status=4,":"")." acttime='".time()."' WHERE id='".$bid."'");
			    if ($duelEnd>0){
		    	    $db->Query("SELECT user1Exp,user2Exp,type FROM rpg_userduelbattles WHERE id='".$bid."'");
		    	    $exprow=$db->FetchAssoc();
		    	    $db->Query("UPDATE rpg_users SET ".($duelEnd==1?"exp=exp+".round($exprow['user1Exp']).", wins=wins+1,":"loses=loses+1,")." ".($exprow['type']==1?"life='30',":"")." act=0, viewb='D_".$bid."', last_update='".time()."' WHERE id='".$user[1]['ID']."'");
		    	    $db->Query("UPDATE rpg_users SET ".($duelEnd==2?"exp=exp+".round($exprow['user2Exp']).", wins=wins+1,":"loses=loses+1,")." act=0, viewb='D_".$bid."', last_update='".time()."' WHERE id='".$user[2]['ID']."'");
			    }
			    
        }
        function countUserBotStats($id){
        	global $db;
        	$db->Query("SELECT * FROM rpg_usersanimals WHERE aid='".$id."'");
        	$row=$db->FetchAssoc();
        	$row['life_points']=round($row['rlife']/5);
        	return $row;
        }
        function doFightWithBot($bid,$bot1,$userid,$bot2){
        	global $db;
        	$user1Stats=$this->countUserBotStats($bot1);
        	$user1Stats['login']=$user1Stats['aname'].'('.$user1Stats['mainname'].')';
        	$user1Stats['life_points']=$user1Stats['rlife']/5;
        	if ($userid>0)
    	        $user2Stats=$this->countStats($userid);
    	    else{
    	    	$user2Stats=$this->countUserBotStats($bot2);
    	    	$user2Stats['life_points']=$user2Stats['rlife']/5;
    	    	$user2Stats['login']=$user2Stats['aname'].'('.$user2Stats['mainname'].')';
    	    }
    	       $user[1]['rate']=$this->countStatsRate($user1Stats);
		       $user[2]['rate']=$this->countStatsRate($user2Stats);
		       $actions=$this->actionRendomizer($user1Stats,$user2Stats);
		       $user1Atac=$this->pointRendomizer($user1Stats['strength']);
		       $user1hit=$user1Atac;
		       $user2Atac=$this->pointRendomizer($user2Stats['strength']);
		       $user2hit=$user2Atac;
		       $user1Bron=$this->pointRendomizer($user1Stats['bron'],1);
		       $user2Bron=$this->pointRendomizer($user2Stats['bron'],1);
		       if ($actions['blk1']==1){
		       	   $user1SBlock=1;
		       	   if ($actions['dex2']!=1){
		       	   	   $user2Atac=0;
		       	   }
		       }
		       if ($actions['blk2']==1){
		       	   $user2SBlock=1;
		       	   if ($actions['dex1']!=1){
		       	   	   $user1Atac=0;
		       	   }
		       	  
		       }
			   if ($actions['dex1']==1){
			    	if ($user2SBlock==0){
			    		
			    	    $user1Atac=($user1hit*2);
			    	}
			    	else $user1Atac=$user1hit;
			    	
			    	$user1act=2;
			    }
			    if ($actions['dex2']==1){
			    	if ($user1SBlock==0){
			    	    $user2Atac=($user2hit*2);
			    	}
			    	else{
			    		$user2Atac=$user2hit;
			    	}
			    	$user2act=2;
			    }
			    if ($actions['acc1']==1){
			    	$user2Atac=0;
			    	$user1act+=1;
			    }
			    if ($actions['acc2']==1){
			    	$user1Atac=0;
			    	$user2act+=1;
			    }
			    ///gasaketebelia brdzolis dasruleba
			    $user1hit=$user1Atac-$user2Bron;
			    if ($user1hit<0) $user1hit=0;
			    $user2hit=$user2Atac-$user1Bron;
			    if ($user2hit<0) $user2hit=0;
			    $res['bid']=$bid;
			    $res['user1']=$user[1]['ID'];
			    $res['user2']=$user[2]['ID'];
			    $res['login1']=$user1Stats['login'];
			    $res['login2']=$user2Stats['login'];
			    $res['user1hit']=$user1hit;
			    $res['user2hit']=$user2hit;
			    $res['user1block']=$user1SBlock;
			    $res['user2block']=$user2SBlock;
			    $res['user1act']=$user1act;
			    $res['user2act']=$user2act;
			    $user1Exp=0;
			    $user2Exp=0;
			    if ($user1hit>0) {
			     $user1Exp=$this->getUserExp($user1hit,$user[1]['rate'],$user[2]['rate']);
			    }
			    if ($user2hit>0) {
			     $user2Exp=$this->getUserExp($user2hit,$user[2]['rate'],$user[1]['rate']);
			    }
			    $this->doGroupBattleLog($res);
   			    $user1Stats['life']=$user1Stats['life']-$user2hit;
			    $user1Stats['life']=($user1Stats['life']<0?0:$user1Stats['life']);
			    $user2Stats['life']=$user2Stats['life']-$user1hit;
			    $user2Stats['life']=($user2Stats['life']<0?0:$user2Stats['life']);
			    $db->Query("UPDATE rpg_usersanimals SET life='".$user1Stats['life']."' WHERE aid='".$bot1."'");
			    if ($user>0){
			        $db->Query("UPDATE rpg_users SET life='".$user2Stats['life']."' WHERE id='".$userid."'");
			    }
			    else{
			    	$db->Query("UPDATE rpg_usersanimals SET life='".$user2Stats['life']."' WHERE id='".$bot2."'");
			    }
			    if ($user1Stats['life']==0) $status1=1;
			    if ($user2Stats['life']==0) $status2=1;
			    
			    $db->Query("UPDATE rpg_gbusers SET hits=hits+".$user1hit.", getexp=getexp+".$user1Exp.", status='".$status1."', lasthit='".time()."' WHERE gid='".$bid."'  AND userBot='".$bot1."'");
			    $db->Query("UPDATE rpg_gbusers SET hits=hits+".$user2hit.", getexp=getexp+".$user2Exp.", status='".$status2."', lasthit='".time()."' WHERE gid='".$bid."' AND ".($userid>0?"userID='".$userid."'":"userBot='".$bot2."'")."");
			    


        }
        function doFightGroup($bid,$user,$myP,$opP){
        	global $db;
        	   $user1Stats=$this->countStats($user[1]['ID']);
		       $user2Stats=$this->countStats($user[2]['ID']);
		       $user[1]['rate']=$this->countStatsRate($user1Stats);
		       $user[2]['rate']=$this->countStatsRate($user2Stats);
		       $actions=$this->actionRendomizer($user1Stats,$user2Stats);
		       
		       $user1MagAtac=$this->pointRendomizer($user1Stats['intelect']);
		       $user1Atac=$this->pointRendomizer($user1Stats['strength']);
		       $user1hit=$user1Atac;
		       
		       $user2MagAtac=$this->pointRendomizer($user2Stats['intelect']);
		       $user2Atac=$this->pointRendomizer($user2Stats['strength']);
		       $user2hit=$user2Atac;
		       
		       $user1Bron=$this->pointRendomizer($user1Stats['bron'],1);
		       $user2Bron=$this->pointRendomizer($user2Stats['bron'],1);
		       if ($actions['blk1']==1){
		       	   $user1SBlock=1;
		       	   if ($actions['dex2']!=1){
		       	   	   $user2Atac=0;
		       	   }
		       }
		       if ($actions['blk2']==1){
		       	   $user2SBlock=1;
		       	   if ($actions['dex1']!=1){
		       	   	   $user1Atac=0;
		       	   }
		       	  
		       }
			   if ($actions['dex1']==1){
			    	if ($user2SBlock==0){
			    		
			    	    $user1Atac=($user1hit*2);
			    	}
			    	else $user1Atac=$user1hit;
			    	
			    	$user1act=2;
			    }
			    if ($actions['dex2']==1){
			    	if ($user1SBlock==0){
			    	    $user2Atac=($user2hit*2);
			    	}
			    	else{
			    		$user2Atac=$user2hit;
			    	}
			    	$user2act=2;
			    }
			    if ($actions['acc1']==1){
			    	$user2Atac=0;
			    	$user1act+=1;
			    }
			    if ($actions['acc2']==1){
			    	$user1Atac=0;
			    	$user2act+=1;
			    }
			    ///gasaketebelia brdzolis dasruleba
			    $user1hit=$user1Atac-$user2Bron;
			    if ($user1hit<0) $user1hit=0;
			    if ($user1MagAtac>0)
			        $user1hit+=$user1MagAtac;
			    
			    $user2hit=$user2Atac-$user1Bron;
			    if ($user2hit<0) $user2hit=0;
			    if ($user2MagAtac>0)
			        $user2hit+=$user2MagAtac;
			    
			    $res['bid']=$bid;
			    $res['user1']=$user[1]['ID'];
			    $res['user2']=$user[2]['ID'];
			    $res['login1']=$user1Stats['login'];
			    $res['login2']=$user2Stats['login'];
			    $res['user1hit']=$user1hit;
			    $res['user2hit']=$user2hit;
			    $res['user1MagAtac']=$user1MagAtac;
			    $res['user2MagAtac']=$user2MagAtac;
			    $res['user1block']=$user1SBlock;
			    $res['user2block']=$user2SBlock;
			    $res['user1act']=$user1act;
			    $res['user2act']=$user2act;
			    $user1Exp=0;
			    $user2Exp=0;

			    if ($user1hit>0) {
			     $user1Exp=$this->getUserExp($user1hit,$user[1]['rate'],$user[2]['rate'],'G');
			    }
			    if ($user2hit>0) {
			     $user2Exp=$this->getUserExp($user2hit,$user[2]['rate'],$user[1]['rate'],'G');
			    }
			    $this->doGroupBattleLog($res);
   			    $user1Stats['life']=$user1Stats['life']-$user2hit;
			    $user1Stats['life']=($user1Stats['life']<0?0:$user1Stats['life']);
			    $user2Stats['life']=$user2Stats['life']-$user1hit;
			    $user2Stats['life']=($user2Stats['life']<0?0:$user2Stats['life']);
			    $db->Query("UPDATE rpg_users SET life='".$user1Stats['life']."' WHERE id='".$user[1]['ID']."'");
			    $db->Query("UPDATE rpg_users SET life='".$user2Stats['life']."' WHERE id='".$user[2]['ID']."'");
			    if ($user1Stats['life']==0) $status1=1;
			    if ($user2Stats['life']==0) $status2=1;
			    $db->Query("UPDATE rpg_gbusers SET hits=hits+".$user1hit.", getexp=getexp+".$user1Exp.", status='".$status1."', lasthit='".time()."' WHERE gid='".$bid."' AND userID='".$user[1]['ID']."'");
			    $db->Query("UPDATE rpg_gbusers SET hits=hits+".$user2hit.", getexp=getexp+".$user2Exp.", status='".$status2."', lasthit='".time()."' WHERE gid='".$bid."' AND userID='".$user[2]['ID']."'");
			    
        }
function doActionLogs($id1,$id2,$login1,$description){
	global $db;
	$db->Query("INSERT INTO usersactionLog SET id1='".$id1."', login1='".$login1."', id2='".$id2."', description='".$description."'");
}
        function groupBattleEnd($id,$winners){
        	global $db,$xhtml;
        	$db->Query("SELECT * FROM rpg_gbusers WHERE gid='".$id."'");
        	while ($row=$db->FetchAssoc()){
        		if ($winners==$row['team']) {
        			if ($row['lvl']>0) $bexp=(1+($row['lvl']/10));
        			else $bexp=1;
        		    $q=", exp=exp+".round(($row['getexp']*$bexp)).", wins=wins+1, medals=medals+3";
        		}
        		else { 
        		     $q=", loses=loses+1, medals=medals+1";
        		     if ($row['userID']>0){
        		     $botdrop[1][]['item']=array('rand'=>10,'itname'=>'Msubuqi travma','ittype'=>21,'strength_p'=>'-'.rand(0,10),'bron_p'=>'-'.rand(0,10),'life_p'=>'-'.rand(0,10),'description'=>'Travma','acttime'=>(time()+rand(1800,5400)),'broktime'=>604800,'img'=>'magic.gif','act'=>21);
        		     $botdrop[2][]['item']=array('rand'=>20,'itname'=>'Sashualo travma','ittype'=>21,'strength_p'=>'-'.rand(0,50),'bron_p'=>'-'.rand(0,50),'life_p'=>'-'.rand(0,50),'description'=>'Travma','acttime'=>(time()+rand(1800,5400)),'broktime'=>604800,'img'=>'magic.gif','act'=>21);
        		     $botdrop[3][]['item']=array('rand'=>50,'itname'=>'Mdzime travma','ittype'=>21,'strength_p'=>'-'.rand(0,100),'bron_p'=>'-'.rand(0,100),'life_p'=>'-'.rand(0,100),'description'=>'Travma','acttime'=>(time()+rand(1800,5400)),'broktime'=>604800,'img'=>'magic.gif','act'=>21);
        		     $type=rand(1,3);
        		     foreach ($botdrop[$type] as $key=>$value){	    						
          	                         if (!empty($value['item'])){
                                   		 $rn=rand(1,$value['item']['rand']);
     		                             if ($rn==1){
     		                             	 $getitem.=$value['item']['itname'];
     			                             foreach ($value['item'] as $key=>$value){                             	 
                     				            if ($key!='rand'){
                     				            	if ($key=='broktime') $qt.="".$key."='".(time()+$value)."', ";
                     				            	else{
                                	    			   $qt.="".$key."='".$value."'".($key!='act'?",":"")." ";
                                	    			}
                                	    		}
                               	    	     }
                                	     mysql_query("UPDATE rpg_gbusers SET travma='".$type."' WHERE id='".$row['id']."'");
                                	     mysql_query("INSERT INTO users_items SET usid='".$row['userID']."', ".$qt."");
                                	     unset($qt);
               		                     }
     	                             }

 
                    }
                     }
        		}
        		if ($row['userBot']>0){
        			mysql_query("UPDATE rpg_usersanimals SET act=0, exp=exp+".round(($row['getexp']*$bexp)).", replete=replete-(alevel*fmeal), life=rlife WHERE aid='".$row['userBot']."'");
        			
        		}
        		else{
        		    mysql_query("UPDATE rpg_users SET act=0, last_update='".time()."', viewb='G_".$id."'".$q." WHERE id='".$row['userID']."'");
        		}
        		unset($q);
        		unset($bexp);
        	}
        	$db->Query("UPDATE rpg_groupbattle SET status=2, winner='".$winners."' WHERE id='".$id."'");
        	$xhtml->redirect('index.php?garena',1);
        	exit;
        }
        function doGroupBattleLog($res){
        	global $db;
        	$db->Query("INSERT INTO rpg_groupbattleslogs SET bid='".$res['bid']."', user1='".$res['user1']."', player1='".$res['login1']."', player2='".$res['login2']."', user2='".$res['user2']."', user1hit='".$res['user1hit']."', user2hit='".$res['user2hit']."', user1maghit='".$res['user1MagAtac']."', user2maghit='".$res['user2MagAtac']."', user1block='".$res['user1block']."', user2block='".$res['user2block']."', user1act='".$res['user1act']."', user2act='".$res['user2act']."', acttime=NOW()");
        }
        function doDuelBattleLog($res){
        	global $db;
        	$db->Query("INSERT INTO rpg_duelbattleslogs SET bid='".$res['bid']."', user1='".$res['user1']."', user2='".$res['user2']."', user1hit='".$res['user1hit']."', user2hit='".$res['user2hit']."', user1block='".$res['user1block']."', user2block='".$res['user2block']."', user1act='".$res['user1act']."', user2act='".$res['user2act']."', acttime=NOW()");
        }

		function viewBattle($bid){
			global $db,$xhtml,$lang;
			$exbid=explode('_',$bid);
			$middlec.=$xhtml->getLink('index.php?'.($exbid=='D'?'arena&':'').rand(100,999).'',$xhtml->getImage('icon/re.png','Ganaxleba')).$xhtml->getBR();

			if ($exbid[0]=='B'){
			    $db->Query("SELECT winer,botID FROM rpg_botbattles WHERE id='".$exbid[1]."'");
			    $r=$db->FetchAssoc();
			    $e.=$lang->figthhaveend.':'.($r['winer']==1?$lang->uwin:$lang->ulose).$xhtml->getBR();
			    $db->Query("UPDATE rpg_users SET viewb=0 WHERE id='".USER_ID."'");
			    $db->Query("SELECT login,userBot,lvl,userHits,userExp,userDrop,getitem FROM rpg_botbattlesaction WHERE (userID>0 OR userBot>0) AND bid='".$exbid[1]."'");
			    while($row=$db->FetchAssoc()){
			    
			    	$e.=$row['login'].'['.$row['lvl'].'] '.$lang->hhitsum.':'.$xhtml->getSpan($row['userHits'],'color_red');
			        if ($r['winer']==1){
			        $e.=', '.$xhtml->getSpan(round($row['userExp']),'color_green').' Exp';
			        if (!empty($row['userDrop'])) $e.=', '.round($row['userDrop'],2).' '.$lang->gold;
			        }
			        $e.=$xhtml->getBR();
			        if (!empty($row['getitem'])){
			        	$itDrop.=$row['login'].' '.$lang->found.':'.$row['getitem'].$xhtml->getBR();
			        }
			    }
			    if (!empty($itDrop))
			         $e=$e.$xhtml->getBlock($itDrop,'b');
			    $middlec.=$this->showBotBattleLog($exbid[1]);
                //QUESTS
                    $this->questSubmiter(1,$r['botID']);
			    	$this->questSubmiter(3,$r['botID']);
			    //END QUEST
			    $db->Query("DELETE FROM rpg_bots WHERE bid='".$r['botID']."'");
			}
			if ($exbid[0]=='D'){
				$db->Query("SELECT user1,user2,user1Hits,user2Hits,user1Exp,user2Exp,winer FROM rpg_userduelbattles WHERE id='".$exbid[1]."'");
				$row=$db->FetchAssoc();
				if (USER_ID==$row['user1']) $myID=1;
				if (USER_ID==$row['user2']) $myID=2;
				if ($row['winer']==3) $ans='Fred';
				elseif ($myID==$row['winer']) {
					$win=1;
				    $ans=$lang->uwin;
				}
				else $ans=$lang->ulose;
				$db->Query("UPDATE rpg_users SET viewb=0 WHERE id='".USER_ID."'");
				$e.=$lang->figthhaveend.':'.$ans.''.$xhtml->getBR();
				$e.=$lang->uhitsum.':'.$row['user'.$myID.'Hits'].''.$xhtml->getBR();
				if ($win==1){
				    $e.=$lang->get.':'.round($row['user'.$myID.'Exp']).' Exp'.$xhtml->getBR();
				    $this->questSubmiter(4);
				}
				$middlec.=$this->showDuelBattleLog($exbid[1]);
                
			}
			if ($exbid[0]=='G'){
				$travmaraay=array(1=>'Msubuqi',2>='Sashualo',3=>'Mdzime');
				$db->Query("SELECT winner FROM rpg_groupbattle WHERE id='".$exbid[1]."'");
				$r=$db->FetchAssoc();
				$db->Query("SELECT * FROM rpg_gbusers WHERE gid='".$exbid[1]."'");
				while ($row=$db->FetchAssoc()){
					if ($r['winner']==$row['team']) $winner=1;
					else $winner=0;
					$users[]=array('travma'=>$row['travma'],'login'=>$row['login'],'lvl'=>$row['lvl'],'hits'=>$row['hits'],'exp'=>$row['getexp'],'team'=>$row['team'],'winner'=>$winner);
				}
				foreach ($users as $key=>$value){
					if ($r['winner']<3 && $r['winner']==$value['team']){
					    $winnerUsers.=$value['login'].'['.$value['lvl'].'] ';
					}
					if ($value['team']==1){
						if ($r['winner']==1) $getexp=', '.$xhtml->getSpan(($value['lvl']>0?round(($value['exp']*(1+$value['lvl']/10))):$value['exp']).' Exp','color_green');
						$team1.=$value['login'].'['.$value['lvl'].'] '.$lang->hhitsum.':'.$xhtml->getSpan($value['hits'],'color_red').''.$getexp.$xhtml->getBR();
						unset($getexp);
					}
					if ($value['team']==2){
						if ($r['winner']==2) $getexp=', '.$xhtml->getSpan(($value['lvl']>0?round(($value['exp']*(1+$value['lvl']/10))):$value['exp']).' Exp','color_green');
						$team2.=$value['login'].'['.$value['lvl'].'] '.$lang->hhitsum.':'.$xhtml->getSpan($value['hits'],'color_red').''.$getexp.$xhtml->getBR();
						unset($getexp);
					}
					if ($value['travma']>0){
						$travma.=$xhtml->getSpan($value['login'].' Miigo '.$travmaraay[$value['travma']].' travma','color_red').$xhtml->getBR();
					}
				}
				if ($r['winner']<3) 
				$middlec.=$xhtml->getBlock($lang->winners.':'.$winnerUsers,'b');
				else $middlec.=$lang->figthendfre.$xhtml->getBR();
				$middlec.=$team1.'---'.$xhtml->getBR().$team2;
				if (!empty($travma))
				    $middlec.=$xhtml->getBR().$travma;
				
				$db->Query("UPDATE rpg_users SET viewb=0 WHERE id='".USER_ID."'");
			}
			$middlec=$xhtml->getBlock($e,'acz').$middlec;
			$downc=$xhtml->getLink('index.php',$lang->main);
			$xhtml->createPage('Chemi Gmiri',$topc,$middlec,$downc);
	        exit;
		}
function getUserInfo($id,$infoarr=''){
	global $db;
	foreach ($infoarr as $value){
		$q.=$value.',';
		$last=$value;
	}
	$q=str_replace($last.',',$last,$q);
	$db->Query("SELECT ".$q." FROM rpg_users WHERE id='".$id."'");
	$row=$db->FetchAssoc();
	return $row;
}
function getProc($id1,$id2){
	$w=$this->minMax($id1,$id2);
		if ($w!=0){
			    $Acc=max($id1,$id2)-min($id1,$id2);
				if ($Acc<5) {
				    $Acc1=$Acc*5;
				    $Acc2=$Acc*2;
				}
				if ($Acc>=5 && $Acc<20){
					$Acc1=$Acc*4;
					$Acc2=round($Acc/2);
				}
				if ($Acc>=20){
					$Acc1=99;
					$Acc2=0;
				}
		}
		if ($w==1) {
		    $ret[1]=$Acc1;
		    $ret[2]=$Acc2;
		}
		if ($w==2){
			$ret[2]=$Acc1;
		    $ret[1]=$Acc2;
		}
		if ($w==0) {
			$ret[1]=20;
			$ret[2]=20;
		}
	return $ret;
}
		function actionRendomizer($id1Stats,$id2Stats){	
			/*
			$inAcc1=($sAcc==1?20:$inAcc1);
			$inDex1=($sDex==1?20:$inDex1);
			$inAcc2=($sAcc==1?20:$inAcc2);
			$inDex2=($sDex==1?20:$inDex2);
			$inBlk1=($sBlk==1?20:$inBlk1);
			$inBlk2=($sBlk==1?20:$inBlk2);
			*/
			/*
			$inDex=$this->getProc($id1Stats['dexterity'],$id2Stats['dexterity']);
			$inAcc=$this->getProc($id1Stats['accuracy'],$id2Stats['accuracy']);
			$inBlk=$this->getProc($id1Stats['blocking'],$id2Stats['blocking']);
			*/
			$id2Stats['ant_dexterity']+=$id2Stats['dexterity']*0.5;
			$id1Stats['ant_dexterity']+=$id1Stats['dexterity']*0.5;
			$id2Stats['ant_accuracy']+=$id2Stats['accuracy']*0.5;
			$id1Stats['ant_accuracy']+=$id1Stats['accuracy']*0.5;
			$id1Stats['ant_blocking']+=$id1Stats['blocking']*0.5;
			$id2Stats['ant_blocking']+=$id2Stats['blocking']*0.5;
			$id1_isDexterity=$this->countStatEfect($id1Stats['dexterity'],$id2Stats['ant_dexterity']);
			$id2_isDexterity=$this->countStatEfect($id2Stats['dexterity'],$id1Stats['ant_dexterity']);
			
			$id1_isAccuracy=$this->countStatEfect($id1Stats['accuracy'],$id2Stats['ant_accuracy']);
			$id2_isAccuracy=$this->countStatEfect($id2Stats['accuracy'],$id1Stats['ant_accuracy']);
			$id1_isBlocking=$this->countStatEfect($id1Stats['blocking'],$id2Stats['ant_blocking']);
			$id2_isBlocking=$this->countStatEfect($id2Stats['blocking'],$id1Stats['ant_blocking']);
			
			if (($id1Stats['accuracy']>0 && $id2Stats['blocking']>0) || ($id2Stats['accuracy']>0 && $id1Stats['blocking']>0)){
				
			if ($id2_isBlocking==1 && $id1_isAccuracy==1){
			    $acctodex=round($id1Stats['accuracy']/$id2Stats['blocking'],2);
			    $accdexr=round(170*$acctodex);
			    if (rand(1,$accdexr)<=100)
			    	$id1_isAccuracy=0;
				else
					$id2_isBlocking=0;
			}
			
			if ($id1_isBlocking==1 && $id2_isAccuracy==1){
			    $acctodex=round($id2Stats['accuracy']/$id1Stats['blocking'],2);
			    $accdexr=round(170*$acctodex);
			    if (rand(1,$accdexr)<=100)
			    	$id2_isAccuracy=0;
				else
					$id1_isBlocking=0;
			}
			}
			/*
			$cntAcc=$id2Stats['accuracy']-$id1Stats['ant_accuracy'];
			if ($cntAcc<=0) $cntAcc=1;
			$cntDex=$id1Stats['dexterity']-$id2Stats['ant_dexterity'];
			if ($cntDex<=0) $cntDex=1;
			*/
			
			if ($id1_isDexterity==1 && $id2_isAccuracy==1){
				$acctodex=round($id2Stats['accuracy']/$id1Stats['dexterity'],2);
		    	$accdexr=round(600*$acctodex);
			    if (rand(1,$accdexr)<=100)
					$id2_isAccuracy=0;
				else
					$id1_isDexterity=0;
			}
			
			if ($id2_isDexterity==1 && $id1_isAccuracy==1){
				$acctodex=round($id1Stats['accuracy']/$id2Stats['dexterity'],2);
		    	$accdexr=round(600*$acctodex);
				if (rand(1,$accdexr)<=100)
					$id1_isAccuracy=0;
				else
					$id2_isDexterity=0;
			}
			
			
			
			if ($id1_isBlocking==1 && $id2_isBlocking=1){
				$blktoblk=round($id2Stats['blocking']/$id1Stats['blocking'],2);
		    	$blkdx=round(500*$blktoblk);
		    	
			    if (rand(1,$blkdx)<=100)
					$id2_isBlocking=0;
				else
					$id1_isBlocking=0;
			}
			
			if ($id2_isBlocking==1 && $id1_isBlocking==1){
				$blktoblk=round($id1Stats['blocking']/$id2Stats['blocking'],2);
		    	$blkdx=round(500*$blktoblk);
				if (rand(1,$blkdx)<=100)
					$id1_isBlocking=0;
				else
					$id2_isBlocking=0;
			}
			
			if ($id1_isAccuracy==1 && $id2_isAccuracy==1){
				if ($id2Stats['accuracy']>$id1Stats['accuracy']){
					$acctoacc=round($id2Stats['accuracy']/$id1Stats['accuracy'],2);
					$accdx=round(500*$acctoacc);
					if (rand(1,$accdx)<=250)
					    $id2_isAccuracy=0;
				    else
					     $id1_isAccuracy=0;
				}
				else{
					$acctoacc=round($id1Stats['accuracy']/$id2Stats['accuracy'],2);
					$accdx=round(500*$acctoacc);
					if (rand(1,$accdx)<=250)
						$id1_isAccuracy=0;
				    else
					    $id2_isAccuracy=0;
				}
			}
			$action['acc1']=$id1_isAccuracy;
			$action['dex1']=$id1_isDexterity;
			$action['acc2']=$id2_isAccuracy;
			$action['dex2']=$id2_isDexterity;
			$action['blk1']=$id1_isBlocking;
			$action['blk2']=$id2_isBlocking;
			return $action;
		}
		
		function minMax($id1,$id2){
			if ($id1>$id2) return 1;
			if ($id2>$id1) return 2;
			if ($id1==$id2) return 0;
		}
		function countStatEfect($stat,$anti){
			if ($anti>0 && $stat>0){
				$cs=$stat-(100*$anti/100);
				if ($cs>0)
				    $stat=round($cs);
				else
					$stat=0;
			}
			if ($stat>=100) return 1;
			$at=rand(1,100);
			if ($stat>=$at){
				return 1;
			}
			else return 0;
		}
		function showGroupBattleLog($bid){
			global $db,$xhtml,$lang;
			$db->Query("SELECT * FROM rpg_groupbattleslogs WHERE bid='".$bid."' ORDER BY id DESC LIMIT 4");
			while ($row=$db->FetchAssoc()){
				if (empty($row['description'])){
				if ($row['user2act']==1 || $row['user2act']==3){
					$user1Missed=1;
				}
				if ($row['user1act']==1 || $row['user1act']==3){
					$user2Missed=1;
				}
				if (empty($user2Missed)){
					if ($row['user2act']==2 || $row['user2act']==3){
						$user2Crit=1;
					}
					else{
						if ($row['user1block']!=1){
							$user2Hit=1;
						}
					//	else $user1Block=1;
					}
					
				}
				if (empty($user1Missed)){
					if ($row['user1act']==2 || $row['user1act']==3){
						$user1Crit=1;
					}
					else{
						if ($row['user2block']!=1){
							$user1Hit=1;
						}
					//	else $user2Block=1;
					}
					
				}
				if ($row['user1block']==1 && $user2Crit!=1){
					$user1Block=1;
				}
				if ($row['user2block']==1 && $user1Crit!=1){
					$user2Block=1;
				}
				if ($user2Missed==1){
					$user2Source.=$xhtml->getSpan($row['player2'],'color_blue').' '.$xhtml->getSpan(''.$lang->muffed.'','color_green').' '.$xhtml->getSpan($row['player1'],'color_blue').$xhtml->getBR();
				}
				   if ($user2Crit==1 && empty($user2Missed)){
					   $user2Source.=$xhtml->getSpan($row['player2'],'color_blue').' '.$lang->hhitsum.' '.$xhtml->getSpan($row['player1'],'color_blu').':'.$xhtml->getSpan($row['user2hit'],'color_red').''.$xhtml->getBR();
				    }
				    if ($user2Hit==1 && empty($user2Missed) && empty($user1Block)){
					$user2Source.=$xhtml->getSpan($row['player2'],'color_blue').' '.$lang->hhitsum.' '.$xhtml->getSpan($row['player1'],'color_blue').':'.$row['user2hit'].''.$xhtml->getBR();
				    }
				    if ($user1Block==1 && empty($user2Missed)){
					$user2Source.=$xhtml->getSpan($row['player1'],'color_blue').' '.$xhtml->getSpan(''.$lang->blocked.'','color_grey').' '.$xhtml->getSpan($row['player2'],'color_blue').$xhtml->getBR();
				    }
                /////
                if ($user1Missed==1){
                	$user1Source.=$xhtml->getSpan($row['player1'],'color_blue').' '.$xhtml->getSpan(''.$lang->muffed.'','color_green').' '.$xhtml->getSpan($row['player2'],'color_blue').$xhtml->getBR();
                }
                	if ($user1Crit==1 && empty($user1Missed)){
                	    $user1Source.=$xhtml->getSpan($row['player1'],'color_blue').' '.$lang->hhitsum.' '.$xhtml->getSpan($row['player2'],'color_blue').' '.$xhtml->getSpan($row['user1hit'],'color_red').$xhtml->getBR();
                    }
                    if ($user1Hit==1 && empty($user1Missed) && empty($user2Block)){
                	    $user1Source.=$xhtml->getSpan($row['player1'],'color_blue').' '.$lang->hhitsum.' '.$xhtml->getSpan($row['player2'],'color_blue').':'.$row['user1hit'].''.$xhtml->getBR();
                    }
                    if ($user2Block==1 && empty($user1Missed)){
                	    $user1Source.=$xhtml->getSpan($row['player2'],'color_blue').' '.$xhtml->getSpan(''.$lang->blocked.'','color_grey').' '.$xhtml->getSpan($row['player1'],'color_blue').$xhtml->getBR();
                    }
                    $source.=$user1Source.$user2Source;
                }
                else{
                	$source.=(!empty($row['player1'])?$row['player1']:$row['player2']).' '.$xhtml->getSpan($row['description'],'color_green').(!empty($row['repoint'])?'('.$xhtml->getSpan($row['repoint'],'color_green').')':'').$xhtml->getBR();
                }
                if ($row['user1maghit']>0) $source.=$xhtml->getSpan($row['player1'],'color_blue').' Mag.Dartyma '.$xhtml->getSpan($row['player2'],'color_blue').':'.$xhtml->getSpan($row['user1maghit'],'color_blue').$xhtml->getBR();
                if ($row['user2maghit']>0) $source.=$xhtml->getSpan($row['player2'],'color_blue').' Mag.Dartyma '.$xhtml->getSpan($row['player1'],'color_blue').':'.$xhtml->getSpan($row['user2maghit'],'color_blue').$xhtml->getBR();
				unset($user1Source);
				unset($user1Crit);
				unset($user1Hit);
				unset($user1Block);
				unset($user1Missed);
				unset($user2Source);
				unset($user2Crit);
				unset($user2Hit);
				unset($user2Block);
				unset($user2Missed);
			
			}
			return $source;
		}
		function showDuelBattleLog($bid){
			global $db,$xhtml,$lang;
			$db->Query("SELECT log.*,u1.login as login1,u2.login as login2 FROM rpg_duelbattleslogs as log LEFT JOIN rpg_users AS u1 ON log.user1=u1.id LEFT JOIN rpg_users AS u2 ON log.user2=u2.id WHERE log.bid='".$bid."' ORDER BY log.id DESC LIMIT 4");
			while ($row=$db->FetchAssoc()){
				if (empty($row['description'])){
				if ($row['user2act']==1 || $row['user2act']==3){
					$user1Missed=1;
				}
				if ($row['user1act']==1 || $row['user1act']==3){
					$user2Missed=1;
				}
				if (empty($user2Missed)){
					if ($row['user2act']==2 || $row['user2act']==3){
						$user2Crit=1;
					}
					else{
						if ($row['user1block']!=1){
							$user2Hit=1;
						}
					//	else $user1Block=1;
					}
					
				}
				if (empty($user1Missed)){
					if ($row['user1act']==2 || $row['user1act']==3){
						$user1Crit=1;
					}
					else{
						if ($row['user2block']!=1){
							$user1Hit=1;
						}
					//	else $user2Block=1;
					}
					
				}
				if ($row['user1block']==1 && $user2Crit!=1){
					$user1Block=1;
				}
				if ($row['user2block']==1 && $user1Crit!=1){
					$user2Block=1;
				}
				if ($user2Missed==1){
					$user2Source.=$xhtml->getSpan($row['login2'],'color_blue').' '.$xhtml->getSpan($lang->muffed,'color_green').' '.$xhtml->getSpan($row['login1'],'color_blue').$xhtml->getBR();
				}
				   if ($user2Crit==1 && empty($user2Missed)){
					   $user2Source.=$xhtml->getSpan($row['login2'],'color_blue').' '.$lang->hhitsum.' '.$xhtml->getSpan($row['login1'],'color_blu').':'.$xhtml->getSpan($row['user2hit'],'color_red').''.$xhtml->getBR();
				    }
				    if ($user2Hit==1 && empty($user2Missed) && empty($user1Block)){
					$user2Source.=$xhtml->getSpan($row['login2'],'color_blue').' '.$lang->hhitsum.' '.$xhtml->getSpan($row['login1'],'color_blue').':'.$row['user2hit'].''.$xhtml->getBR();
				    }
				    if ($user1Block==1 && empty($user2Missed)){
					$user2Source.=$xhtml->getSpan($row['login1'],'color_blue').' '.$xhtml->getSpan($lang->blocked,'color_grey').' '.$xhtml->getSpan($row['login2'],'color_blue').$xhtml->getBR();
				    }
				

                /////
                if ($user1Missed==1){
                	$user1Source.=$xhtml->getSpan($row['login1'],'color_blue').' '.$xhtml->getSpan($lang->muffed,'color_green').' '.$xhtml->getSpan($row['login2'],'color_blue').$xhtml->getBR();
                }
                	if ($user1Crit==1 && empty($user1Missed)){
                	    $user1Source.=$xhtml->getSpan($row['login1'],'color_blue').' '.$lang->hhitsum.' '.$xhtml->getSpan($row['login2'],'color_blue').' '.$xhtml->getSpan($row['user1hit'],'color_red').$xhtml->getBR();
                    }
                    if ($user1Hit==1 && empty($user1Missed) && empty($user2Block)){
                	    $user1Source.=$xhtml->getSpan($row['login1'],'color_blue').' '.$lang->hhitsum.' '.$xhtml->getSpan($row['login2'],'color_blue').':'.$row['user1hit'].''.$xhtml->getBR();
                    }
                    if ($user2Block==1 && empty($user1Missed)){
                	    $user1Source.=$xhtml->getSpan($row['login2'],'color_blue').' '.$xhtml->getSpan($lang->blocked,'color_grey').' '.$xhtml->getSpan($row['login1'],'color_blue').$xhtml->getBR();
                    }
                    $source.=$xhtml->getBlock($user1Source.$user2Source,'z');
                }
                else{
                	$source.=$xhtml->getBlock((!empty($row['login1'])?$row['login1']:$row['login2']).' '.$xhtml->getSpan($row['description'],'color_green').' ('.$xhtml->getSpan($row['repoint'],'color_green').')','z');
                }				
                
				unset($user1Source);
				unset($user1Crit);
				unset($user1Hit);
				unset($user1Block);
				unset($user1Missed);
				unset($user2Source);
				unset($user2Crit);
				unset($user2Hit);
				unset($user2Block);
				unset($user2Missed);
			
			}
			return $source;
		}
		function showBotBattleLog($bid){
			global $db,$xhtml,$lang,$gClass;
			$db->Query("SELECT * FROM rpg_botbattleslogs WHERE bid='".$bid."' ORDER BY id DESC LIMIT 4");
			while ($row=$db->FetchAssoc()){
				if (empty($row['description'])){
				if ($row['botact']==1 || $row['botact']==3){
					$userMissed=1;
				}
				if ($row['useract']==1 || $row['useract']==3){
					$botMissed=1;
				}
				if (empty($botMissed)){
					if ($row['botact']==2 || $row['botact']==3){
						$botCrit=1;
					}
					else{
						if ($row['userblock']!=1){
							$botHit=1;
						}
						else $userBlock=1;
					}
					
				}
				if (empty($userMissed)){
					if ($row['useract']==2 || $row['useract']==3){
						$userCrit=1;
					}
					else{
						if ($row['botblock']!=1){
							$userHit=1;
						}
						else $botBlock=1;
					}
					
				}
				if ($botMissed==1){
					$botSource.=$xhtml->getSpan($gClass->languageReplace($row['player2'],$_SESSION['lang']),'color_blue').' '.$xhtml->getSpan($lang->muffed,'color_green').' '.$xhtml->getSpan($row['player1'],'color_blue').$xhtml->getBR();
				}
				if ($botCrit==1){
					$botSource.=$xhtml->getSpan($gClass->languageReplace($row['player2'],$_SESSION['lang']),'color_blue').' '.$lang->hhitsum.' '.$xhtml->getSpan($row['player1'],'color_blu').':'.$xhtml->getSpan($row['bothit'],'color_red').''.$xhtml->getBR();
				}
				if ($botHit==1){
					$botSource.=$xhtml->getSpan($gClass->languageReplace($row['player2'],$_SESSION['lang']),'color_blue').' '.$lang->hhitsum.' '.$xhtml->getSpan($row['player1'],'color_blue').':'.$row['bothit'].''.$xhtml->getBR();
				}
				if ($botBlock==1){
					$botSource.=$xhtml->getSpan($row['player1'],'color_blue').' '.$xhtml->getSpan($lang->blocked,'color_grey').' '.$xhtml->getSpan($gClass->languageReplace($row['player2'],$_SESSION['lang']),'color_blue').$xhtml->getBR();
				}
                /////
                if ($userMissed==1){
                	$userSource.=$xhtml->getSpan($row['player1'],'color_blue').' '.$xhtml->getSpan($lang->muffed,'color_green').' '.$xhtml->getSpan($gClass->languageReplace($row['player2'],$_SESSION['lang']),'color_blue').$xhtml->getBR();
                }
                if ($userCrit==1){
                	$userSource.=$xhtml->getSpan($row['player1'],'color_blue').' '.$lang->hhitsum.' '.$xhtml->getSpan($gClass->languageReplace($row['player2'],$_SESSION['lang']),'color_blue').' '.$xhtml->getSpan($row['userhit'],'color_red').$xhtml->getBR();
                }
                if ($userHit==1){
                	$userSource.=$xhtml->getSpan($row['player1'],'color_blue').' '.$lang->hhitsum.' '.$xhtml->getSpan($gClass->languageReplace($row['player2'],$_SESSION['lang']),'color_blue').':'.$row['userhit'].''.$xhtml->getBR();
                }
                if ($userBlock==1){
                	$userSource.=$xhtml->getSpan($gClass->languageReplace($row['player2'],$_SESSION['lang']),'color_blue').' '.$xhtml->getSpan($lang->blocked,'color_grey').' '.$xhtml->getSpan($row['player1'],'color_blue').$xhtml->getBR();
                }
                if ($row['botmaghit']>0) $botSource.=$xhtml->getSpan($gClass->languageReplace($row['player2'],$_SESSION['lang']),'color_blue').' Mag.Dartyma '.$xhtml->getSpan($row['player1'],'color_blue').':'.$xhtml->getSpan($row['botmaghit'],'color_blue').$xhtml->getBR();
                $source.=$xhtml->getBlock($userSource.$botSource,'z');
                				
				}
				else {
					$source.=$xhtml->getBlock($row['player1'].' '.$xhtml->getSpan($row['description'],'color_green').' ('.$xhtml->getSpan($row['repoint'],'color_green').')','z');
				}
				unset($userSource);
				unset($userCrit);
				unset($userHit);
				unset($userBlock);
				unset($userMissed);
				unset($botSource);
				unset($botCrit);
				unset($botHit);
				unset($botBlock);
				unset($botMissed);
			
			}
			return $source;
		}
		
		function rases($r=''){
			global $lang;
			$rases=array('1'=>$lang->human,'2'=>$lang->elf,'3'=>$lang->ork,'4'=>$lang->gnom,'5'=>$lang->hobit);
			if (!empty($r)) return $rases[$r];
			else
			return $rases;
		}
		function botStats($bid,$pars=''){
			global $db;
			$db->Query("SELECT ".(!empty($pars)?$pars:'*')." FROM rpg_bots WHERE bid='".$bid."'");
			$botStats=$db->FetchAssoc();
			return $botStats;
		}
		function countTodayHunt($type=0){
			$nowdate=date('md');
			if(defined('TODAYHUNT')){
			$td=TODAYHUNT;
			$ex=explode(':',$td);
			if ($type==0){
					if (!empty($ex)){
						if ($nowdate>$ex[0]){
							return 0;
						}
						else{
							return intval($ex[1]);
						}
					}
					else return 0;
				}
			}

			if ($type==1){
				if (!empty($ex)){
					if ($nowdate>$ex[0]){
							return ''.$nowdate.':1';
					}
					else{
						return $ex[0].':'.($ex[1]+1);
					}
				}
			}
		}
		function botBattleEnd($bid,$winner,$type=''){
			global $db,$xhtml,$botdrop,$lang;
			$db->Query("UPDATE rpg_botbattles SET status=0, winer='".$winner."' WHERE id='".$bid."'");
			$r=mysql_query("SELECT userID,botID,userBot,userExp,userDrop FROM rpg_botbattlesaction WHERE bid='".$bid."'");
        	while ($res=mysql_fetch_assoc($r)){
      	    		if ($res['userID']>0) {
      	    			$userExp=0;
      	    			$userDrop=0;
      	    			$win=0;
      	    			$lose=0;
      	    			if ($winner==1) {
      	    			    $userExp=round($res['userExp']);
      	    			    $userDrop=round($res['userDrop'],2);
      	    			    $win=1;
      	    			}
      	    			else $lose=1;
      	    			if ($win==1 && $type>0){
      	    				if(!empty($botdrop[$type])){
      	    					foreach ($botdrop[$type] as $key=>$value){	    						
          	                         if (!empty($value['item'])){
                                   		 $rn=rand(1,$value['item']['rand']);
     		                             if ($rn==1){
     		                             	 $getitem.=$value['item']['itname'];
     			                             foreach ($value['item'] as $key=>$value){                             	 
                     				            if ($key!='rand'){
                     				            	if ($key=='broktime') $qfr.="".$key."='".(time()+$value)."', ";
                     				            	else{
                                	    			   $qfr.="".$key."='".$value."'".($key!='price'?",":"")." ";
                                	    			}
                                	    		}
                               	    	     }
                                	     
                                	     $db->Query("INSERT INTO users_items SET usid='".$res['userID']."', ".$qfr."");
                                	     unset($qfr);
               		                     }
     	                             }

 
                                   }
                                   if (!empty($getitem)){
                                       mysql_query("UPDATE rpg_botbattlesaction SET getitem='".$getitem."' WHERE userID='".$res['userID']."' and bid='".$bid."'");
                                   }
                                  unset($getitem);
      	    				}
      	    			}
      	    			
      	    			$forestRange=range(3,8);
      	    			if (in_array(USER_PLACE,$forestRange)) $setHuntDate=$this->countTodayHunt(1);
      	    		    $db->Query("UPDATE rpg_users SET exp=exp+".$userExp.", money=money+".$userDrop.", ".($win==1?"huntexp=huntexp+1,":"")." act=0, ".(!empty($setHuntDate)?"huntcount='".$setHuntDate."',":"")." last_update='".time()."', viewb='B_".$bid."' WHERE id='".$res['userID']."'");
      	    		}
      	    		
      	    		if ($res['userBot']>0){
      	    			$botExp=0;
      	    			if ($winner==1) {
      	    			    $botExp=round($res['userExp']);
      	    			    $botDrop=round($res['userDrop'],2);
      	    			    $win=1;
      	    			    $q=mysql_query("SELECT * FROM rpg_usersanimals WHERE aid='".$res['userBot']."'");
      	    			    if (mysql_affected_rows()>0){
      	    			    	$qrow=mysql_fetch_assoc($q);
      	    			    	$db->Query("UPDATE rpg_users SET money=money+".$botDrop." WHERE id='".$qrow['usid']."'");
      	    			    }
      	    			}
      	    			$db->Query("UPDATE rpg_usersanimals SET exp=exp+".$botExp.", replete=replete-(alevel*fmeal), life=rlife, act='0' WHERE aid='".$res['userBot']."'");
      	    		}
      	    		
      	    		/*
       	    		if ($res['botID']>0) {
       	    			$db->Query("DELETE FROM rpg_bots WHERE bid='".$res['botID']."'");
       	    		    //$db->Query("UPDATE rpg_bots SET life=rlife, act=0, death='".time()."' WHERE bid='".$res['botID']."'");
       	    		}
       	    		*/
   	    	}
   	    	$xhtml->redirect('index.php');
   	    	exit;
		}
		function goPlace($id){
			global $db,$xhtml;
			$id=intval($id);
			if (empty($_SESSION['dogo']) && ($id==15 || $id==19 || $id==24 || $id==29)){
				$xhtml->redirect('index.php');
				exit;
			}
			else{
				unset($_SESSION['dogo']);
				unset($_SESSION['dkeys']);
			}
			$db->Query("SELECT place FROM rpg_users WHERE id='".$_SESSION['user_id']."'");
			$row=$db->FetchAssoc();
			$db->Query("SELECT id FROM rpg_place WHERE (id='".$id."' AND parent='".$row['place']."') OR (id='".$row['place']."' AND parent='".$id."') ");
			if ($db->getAffectedRows()>0){
				$db->Query("UPDATE rpg_users SET place='".$id."' WHERE id='".$_SESSION['user_id']."'");
			}
			$xhtml->redirect('index.php');
		}
		function getPitWorck($exp,$proc=''){
			$mid=70000;
			$gid=20000;
			
			if (DIG_BONUS>0){
			    
			    if (DIG_BONUS==10) $mindig=18150;
			    if (DIG_BONUS==20) $mindig=28830;
			    if (DIG_BONUS==30) $mindig=35860;
			    if (DIG_BONUS==40) $mindig=40835;
			    $mid-=$mindig;
			}

			if (RASA==4) $exp=$exp*2;
			if (RASA==5) $exp=round($exp*1.5);
			if (empty($proc)){
			    $r=rand(0,($mid-$exp));
			    if ($gid>=$r) return rand(1,7);
			}
			else{
				$pradio=$gid/($mid-$exp);
				$pradio=round($pradio*100,2);
				if ($pradio>100) $pradio=100;
				return $pradio;
			}
			return 0;
		}
		function getOnline(){
			global $db;
			$db->Query("SELECT count(id) as onl FROM rpg_users WHERE last_visit>".(time()-600)."");
			$row=$db->FetchAssoc();
			return $row['onl'];
		}
		function chatSendForm($m=1,$url=''){
			global $xhtml,$db;
			if (isset($_GET['s'])) $who=intval($_GET['s']);
			
			if ($who>0){
				$db->Query("SELECT id,login FROM rpg_users WHERE id='".$who."'");
				$r=$db->FetchAssoc();
				$login=$r['login'];
			}
			
			if (isset($_POST['msg'])){
				$t=time();
				if ((empty($_SESSION['lastmsg']) || ($_SESSION['lastmsg']+30)<$t)){
					$msg=$_POST['msg'];
					if (USER_ID!=1)
					$msg=htmlspecialchars(stripslashes($msg));
					if (strlen($msg)>1){
				        $_SESSION['lastmsg']=time();
				        if ($who>0){
				        	$q.="uid2='".$r['id']."', inboxer='".$r['login']."'";
				        	if ($_POST['prv']==1){
				        		$q.=", privat=1";
				        	}
				        }
				        $db->Query("INSERT INTO rpg_chat SET uid1='".USER_ID."', sender='".USER_NAME."', ".(!empty($q)?$q.',':'')." msg='".$msg."', lang='".$_SESSION['lang']."', clanid='".($_SESSION['chaton']['clanon']>0?CLANID:'0')."', sdate=NOW()");
				        $xhtml->redirect((!empty($url)?$url:'index.php?chat'),($m==1?1:''));
				    }
				}
				else{
					$source.=$xhtml->getBlock($lang->chatsendalert,'color_red');
				}
			}

$furl=(!empty($url)?$url:'index.php?chat').($who>0?'&amp;s='.$who:'');

			$source.=$xhtml->startForm($furl,'post');
			if ($who>0) $source.=$xhtml->getBlock($xhtml->getSpan($login,'color_red').' '.$lang->privat.':'.$xhtml->getInput('prv','1','checkbox'),'b');
			$source.=$xhtml->getBlock($xhtml->getInput('msg','','text','input_100').$xhtml->getInput('s','OK','submit','submit_h12'));
			$source.=$xhtml->endForm();
			return $source;
		}
		function getDateMS($date){
			$t=strtotime($date);
			$r=date('H:i',$t);
			return $r;
		}
		function getChatLetters($m=0,$url=''){
			global $db,$xhtml;
			$db->Query("SELECT * FROM rpg_chat WHERE (privat=0 OR (uid1=".USER_ID." OR uid2=".USER_ID.")) AND lang='".$_SESSION['lang']."' AND clanid='".($_SESSION['chaton']['clanon']>0?CLANID:'0')."' ORDER BY id DESC LIMIT 10");
			while ($row=$db->FetchAssoc()){
				$msg=$row['msg'];
				$msg=str_replace(":h","<img src='images/smile/06.jpg'>",$msg);
				$msg=str_replace(":)","<img src='images/smile/03.jpg'>",$msg);
				$msg=str_replace(":D","<img src='images/smile/04.jpg'>",$msg);
				$msg=str_replace(":d","<img src='images/smile/04.jpg'>",$msg);
				$msg=str_replace(":(","<img src='images/smile/20.gif'>",$msg);
				$msg=str_replace(";)","<img src='images/smile/05.jpg'>",$msg);
				$msg=str_replace(":o","<img src='images/smile/08.jpg'>",$msg);
				if ($row['uid1']>0)
				    $login1=(USER_ID!=$row['uid1']?$xhtml->getLink((!empty($url)?$url.'':'index.php?chat&amp;').'s='.$row['uid1'],$row['sender']).'['.$xhtml->getLink('index.php?person&amp;u='.$row['uid1'].'','?').']':$xhtml->getSpan($row['sender'],'color_blue'));
				if ($row['uid2']>0)
				    $login2=(USER_ID!=$row['uid2']?$xhtml->getLink((!empty($url)?$url.'':'index.php?chat&amp;').'s='.$row['uid2'],$row['inboxer']).'['.$xhtml->getLink('index.php?person&amp;u='.$row['uid2'].'','?').']':$xhtml->getSpan($row['inboxer'],'color_blue'));
				$source.=$xhtml->getBlock($this->getDateMS($row['sdate']).' '.($row['privat']==1?'['.$xhtml->getSpan('P','color_red').']':'').' '.$login1.''.($row['uid2']>0?' &gt;'.$login2.'':'').', '.$msg,'b');
			}
			return $source;
		}
		function placeChat($url=''){
			global $xhtml,$lang;
            //return $xhtml->getBR().$xhtml->getBlock('Chati gamortulia','b');
			if (empty($url)) $curl='index.php';
			else $curl=$url;
			//$source.=$xhtml->getLink('index.php?chat&'.rand(100,999).'',$xhtml->getImage('icon/re.png','Ganaxleba')).$xhtml->getBR();
			if (isset($_GET['chon'])) {
			   $_SESSION['chaton']['conf']=1;
			   $xhtml->redirect($curl,1);
			}
			if (isset($_GET['choff'])) {
			   $_SESSION['chaton']['conf']=0;
			   $xhtml->redirect($curl,1);
			}
			if (isset($_GET['clnon'])) {
			   $_SESSION['chaton']['clanon']=1;
			   $xhtml->redirect($curl,1);
			}
			if (isset($_GET['clnoff'])) {
			   $_SESSION['chaton']['clanon']=0;
			   $xhtml->redirect($curl,1);
			}
			if (empty($url)) $url=$curl.'?';
			else $url=$curl.'&amp;';
			$isch=$_SESSION['chaton']['conf'];
			$ischcln=$_SESSION['chaton']['clanon'];
			$source.=$xhtml->getBLock('&nbsp;');
			
			$chatTurnSource=($isch==1?'On/'.$xhtml->getLink($url.'choff','Off').'':$xhtml->getLink($url.'chon','On').'/Off');
			if (CLANID>0)
			    $chatTurnSource.='&nbsp;'.($ischcln==1?'Klani/'.$xhtml->getLink($url.'clnoff','Saerto').'':$xhtml->getLink($url.'clnon','Klani').'/Saerto');
			$source.=$xhtml->getBlock($chatTurnSource,'b');
			if ($isch==1){
				if (USER_LVL>1){
                    $source.=$xhtml->getBlock($this->chatSendForm(0,$url),'b');
                }
                else{
                	$source.=$lang->chatlimit;
                }
                $source.=$this->getChatLetters(0,$url);
            }
            return $source;
		}
		function save_file ($size,$type,$name,$tmp_name,$target_path,$forname,$pars=''){
		$res='';
	    if ($type!=="image/gif" && $type!=="image/jpeg" && $type!=="image/pjpeg" && $type!=="image/jpg" && $type!=="image/png") {
			$res['error'] = 'araswori failis tipi!'; return $res;
		}
        $jpgsize = GetImageSize($tmp_name);
	    if (($jpgsize[0]>128)||($jpgsize[1]>128)) {
			$res['error']="failis zomebi didia"; return $res;
		}
	    if (($jpgsize[0]<12)||($jpgsize[1]<12)) {
			$res['error']="failis zomebi pataraa"; return $res;
		}
    	$nm = strtolower($name);
	    $co = substr_count($nm,'.');
	    $ex = @explode('.',$nm);
	    if ($ex[$co]!=="gif" && $ex[$co]!=="jpeg" && $ex[$co]!=="jpg" && $ex[$co]!=="png") {
		   $res['error'] = 'araswori failis tipi'; return $res;
		}
	    if (empty($res['error'])) {
	    	if (move_uploaded_file($tmp_name,$target_path.$forname.'.'.$ex[$co])){
	    		$res['save']['name']=''.$target_path.$forname.''.'.'.$ex[$co].'';
                $res['save']['ex']=$ex[$co];
	    	}
	    	else {
	    		$res['error']='ar aitvirta, sistemuri shecdoma';
	    	}
	    	/*
            if ($ex[$co]=='gif') { $img = imagecreatefromgif($tmp_name); }
            if ($ex[$co]=='jpg' || $ex[$co]=='jpeg') { $img=imagecreatefromjpeg($tmp_name); }
            if ($ex[$co]=='png') { $img = imagecreatefrompng($tmp_name); }
            if ($img) { 
                $imx=(!empty($pars['w'])?$pars['w']:imagesx($img));
                $imy=(!empty($pars['h'])?$pars['h']:imagesy($img));
                
                if ($ex[$co]=='gif')
                    imagegif($img, ''.$target_path.$forname.''.'.gif');
                if ($ex[$co]=='png')
                    imagepng($img, ''.$target_path.$forname.''.'.png');
                if ($ex[$co]=='jpg' || $ex[$co]=='jpeg')
                    imagejpeg($img, ''.$target_path.$forname.''.'.jpg');
                */
                
        }

        return $res;
	}
	function battleQuestDetect($id){
		global $db;
        if (defined('QUEST')){
        	$db->Query("SELECT q.id AS QUID FROM rpg_bots as b LEFT JOIN rpg_realbots as r on r.bid=b.rbid LEFT JOIN rpg_quest AS q ON q.fbot=r.bid WHERE b.bid='".$id."' AND q.id='".QUEST."'");
        	$row=$db->FetchAssoc();
        	if (QUEST==$row['QUID']){
        		$db->Query("UPDATE rpg_usersquest SET status=2 WHERE uid='".USER_ID."' AND qid='".QUEST."'");
        	}
        }
    }
	function questSubmiter($qid,$botid=''){
		global $db;
		if ($qid==QUEST){
		    switch (QUEST){
			   case 1;
			   $this->battleQuestDetect($botid);
			   break;
			   case 2;
			   $db->Query("UPDATE rpg_usersquest SET status=2 WHERE uid='".USER_ID."' AND qid='".QUEST."'");
			   break;
			   case 3;
			   $this->battleQuestDetect($botid);
			   break;
			   case 4;
			   $db->Query("UPDATE rpg_usersquest SET status=2 WHERE uid='".USER_ID."' AND qid='".QUEST."'");
			   break;
			   case 5;
			   $db->Query("UPDATE rpg_usersquest SET status=2 WHERE uid='".USER_ID."' AND qid='".QUEST."'");
			   break;
		
	    }
	    }
	}
	function isMobile() {
		               if ( preg_match ( "/phone|iphone|itouch|ipod|symbian|android|htc_|htc-|palmos|blackberry|opera mini|iemobile|windows ce|nokia|alcatel|fennec|hiptop|kindle|mot |mot-|webos\/|samsung|sonyericsson|^sie-|nintendo/i", $_SERVER["HTTP_USER_AGENT"] ) ) {
                        // these are the most common
                        return true;
                } else if ( preg_match ( "/mobile|pda;|avantgo|eudoraweb|minimo|netfront|brew|teleca|lg;|lge |wap;| wap /i", $_SERVER["HTTP_USER_AGENT"] ) ) {
                        // these are less common, and might not be worth checking
                        return true;
                }
    }
}
?>