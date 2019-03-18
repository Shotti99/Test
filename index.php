<?php
///By Qisho
   require_once('united_function.php');

/*
teqSamushao();
exit;
*/
IF (isset($_COOKIE['BN']) && $_COOKIE['BN']>time()){
	cookieBANN();
} 


if (empty($_SESSION['lang'])){
	INDEX();
}
if (date('H')=='00' && intval(date('i'))>55){
	$db->Query("SELECT * FROM rpg_updates WHERE uptime='".date('Ymd')."'");
	if ($db->getAffectedRows()==0){
	    $gClass->doClansUpdate();
	    $db->Query("INSERT INTO rpg_updates SET uptime='".date('Ymd')."'");
	}
}
   if (isset($_GET['ref']) && !isset($_SESSION['refu'])) $_SESSION['refu']=intval($_GET['ref']);

   if (isset($_GET['faq'])){
   	   gameFaq();
   }
   if (isset($_GET['news'])){
   	   gameNews();
   }
   if (isset($_GET['rules'])){
   	   gameRueles();
   }
   if (isset($_POST['us_login']) && isset($_POST['us_pass'])){
   	   authorize($_POST['us_login'],$_POST['us_pass']);
   }
   if (empty($set) && isset($_GET['top_users'])){
   	   topUsers();
   	   $set=1;
   }
   if (isset($_GET['gegma']) && empty($set)){
   	   ganulebamde();
   	   $set=1;
   }
   if (empty($_SESSION['user_id'])){
       if (isset($_GET['reg']) && empty($set)){
	       registration(); $set=1;
       }
       if (empty($set)){
       	   index_main(); $set=1;
       }
   }
   else{ 
   	   $uid=$_SESSION['user_id'];
   	   $db->Query("UPDATE rpg_users SET last_visit='".time()."', ua='".$ua."', uip='".$ip."' WHERE id='".$uid."'");
   	   if (isset($_GET['u'])){
   	   	   $usid=intval($_GET['u']);
   	   	   if ($usid<0) $usid=0;
   	   }
   	   $gClass->getUserPars(intval($uid));
   	   if (empty($set) && isset($_GET['person'])){
   	   	   if ($usid>0)
   	   	       getHeroeStats($usid,1);
   	   	   else
   	   	       getHeroeStats($uid);
   	   	   $set=1;
   	   }

   	   if (empty($set) && isset($_GET['cabinet'])){
   	   	   userCabinet();
   	   	   $set=1;
   	   }
   	   if (isset($_GET['myanimal']) && ANIMAL>0 && empty($set)){
   	   	   myAnimal();
   	   	   $set=1;
   	   }

   	   if (empty($set) && isset($_GET['dressing'])){
   	   	   if ($usid>0){
   	   	       dressing($usid,1);
   	   	   }
   	   	   else
   	   	   	   dressing($uid);
   	   	   $set=1;
   	   }
   	   if (USER_ACT==0){
   	   	   if (USER_PLACE>=15 && USER_PLACE<=30){
   	   	   	   if (NOW_LIFE==0){
   	   	   	   	   $db->Query("UPDATE rpg_users SET place=1 WHERE id='".USER_ID."'");
   	   	   	   }
   	   	   }
   	   	   else
   	       update_heroe(USER_ID);
   	   }
   	   if (WAIT_BATTLE>0){
   	   	   if (WAIT_BATTLE_TYPE=='D')
   	   	       arena();
   	   	   if (WAIT_BATTLE_TYPE=='G'){
   	   	   	   groupArena();
   	   	   }
   	   	   
   	   	   if (WAIT_BATTLE_TYPE=='INST'){
   	   	   	   instantGroup();
   	   	   }
   	   	   
   	   }
   	   if (VIEW_BATTLE){
   	   	   $gClass->viewBattle(VIEW_BATTLE);
   	   }
   	   if (ACT_TYPE=='B' && USER_ACT>0){
   	   	   botBattle(USER_ACT);
   	   }
   	   if (ACT_TYPE=='D' && USER_ACT>0){
   	   	   duelBattle(USER_ACT);
   	   }
   	   if (ACT_TYPE=='G' && USER_ACT>0){
   	   	   groupBattle(USER_ACT);
   	   }
   	   
   	   if (isset($_GET['postman']) && empty($set)){
   	   	   gamePost();
   	   	   $set=1;
   	   }
   	   
   	   if (isset($_GET['onlusers']) && empty($set)){
   	   	   usersOnline();
   	   	   $set=1;
   	   }
/*
   	   if (isset($_GET['chat']) && empty($set)){
           $middlec.=$xhtml->getBR().$xhtml->getBlock('Chati gamortulia','b');
   	   	   //gameChat();
   	   	   $set=1;
   	   }
*/
   	   if (isset($_GET['b']) && empty($set)){
   	   	   $forestRange=range(3,8);
      	   if (in_array(USER_PLACE,$forestRange)){
   	   	      if ((USER_LVL+1)*20>$gClass->countTodayHunt(0)){
   	   	          startBotBattle(intval($_GET['b']));
   	   	      }
   	   	      
   	   	   }
   	   	   else{
   	   	   	   startBotBattle(intval($_GET['b']));
   	   	   }
   	   	   
   	   	   $set=1;
   	   }
   	   if (isset($_GET['arena']) && empty($set)){
   	   	   arena();
   	   	   $set=1;
   	   }
   	   if (isset($_GET['unkin']) && empty($set)){
   	   	   underKingdom();
   	   	   $set=1;
   	   }
   	   if (isset($_GET['garena']) && empty($set)){
   	   	   groupArena();
   	   	   $set=1;
   	   }
   	   
   	   if (isset($_GET['instant']) && empty($set)){
   	   	   instantGroup();
   	   	   $set=1;
   	   }
   	   if (isset($_GET['forgehous']) && empty($set) && USER_LVL>2){
   	   	   forgeHous();
   	   	   $set=1;
   	   }
   	   if (isset($_GET['clans']) && empty($set)){
   	   	   clansHouse();
   	   	   $set;
   	   }
   	   if (isset($_GET['zooshop']) && empty($set) && USER_LVL>2){
   	   	   zooShop();
   	   	   $set=1;
   	   }
   	   if (isset($_GET['magichouse'])){
   	   	   magicHouse();
   	   	   $set=1;
   	   }
   	   if (isset($_GET['pl'])){
   	   	   $gClass->goPlace(intval($_GET['pl']));
   	   	   $set=1;
   	   }
   	   if (empty($set) && isset($_GET['shop'])){
   	   	   shop();
   	       $set=1;
   	   }

   	   if (empty($set) && isset($_GET['bag'])){
   	   	   bag();
   	   	   $set=1;
   	   }
   	   
       if (empty($set)){
   	   game_main();
       }
       
   }
   

   
?>