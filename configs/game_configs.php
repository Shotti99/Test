<?php 
DEFINE('ITEMS_PATH','images/i/');
DEFINE('MITEMS_PATH','images/m/');
DEFINE('CLANICONS','images/ci/');
DEFINE('BOT_BATTLE_TIME',180);
DEFINE('DUEL_WAIT_TIME',180);
DEFINE('LIFE_UPDATE',600);
DEFINE('MANA_UPDATE',1800);
DEFINE('PIT_DEF_TIME',60);
DEFINE('HIT_TIME',30);

$LVL_EXP=array(
	0=>0,
	1=>100,
	2=>250,
	3=>700,
	4=>1800,
	5=>5000,
	6=>15000,
	7=>40000,
	8=>100000,
	9=>300000,
	10=>850000,
	11=>2000000,
	12=>5500000,
	13=>15000000,
	14=>40000000,
    15=>100000000,
    16=>250000000);
$LVL_MONEY=array(
    0=>0,
    1=>10,
    2=>30,
    3=>60,
    4=>120,
    5=>200,
    6=>400,
    7=>800,
    8=>1500,
    9=>3000,
    10=>6000,
    11=>12000,
    12=>24000,
    13=>48000,
    14=>90000,
    15=>150000,
    16=>200000);
$ANIMAL_EXP=array(
	1=>0,
	2=>200,
	3=>500,
	4=>2000,
	5=>5000,
	6=>12000,
	7=>30000,
	8=>80000,
	9=>200000,
	10=>500000,
	11=>1200000,
	12=>2500000,
	13=>6000000,
	14=>15000000,
    15=>35000000,
    16=>80000000);
$botdrop[1][]['item']=array('rand'=>150,'itname'=>'Relife +20','ittype'=>20,'rest_life'=>20,'description'=>'Sicocxlis Shevseba +20','acttime'=>7200,'broktime'=>604800,'img'=>'magic.gif','price'=>0.2);
$botdrop[1][]['item']=array('rand'=>100,'itname'=>'Fast relife +50%','ittype'=>21,'rest_life'=>50,'description'=>'Sicocxlis Scrafi shevseba','acttime'=>3600,'broktime'=>604800,'img'=>'magic.gif','price'=>0.2);
$botdrop[1][]['item']=array('rand'=>2000,'itname'=>'Forest magic','ittype'=>21,'strength_p'=>100,'accuracy_p'=>50,'dexterity_p'=>50,'bron_p'=>50,'blocking_p'=>50,'ant_accuracy_p'=>50,'ant_dexterity_p'=>50,'ant_blocking_p'=>50,'life_p'=>50,'description'=>'+100 dzala , +50 sxva parametrebi, +100% sicocxlis scrafi shevseba','acttime'=>7200,'broktime'=>604800,'img'=>'magic.gif','price'=>100);

$botdrop[2][]['item']=array('rand'=>100,'itname'=>'Relife +20','ittype'=>20,'rest_life'=>20,'description'=>'Sicocxlis Shevseba +20','acttime'=>7200,'broktime'=>604800,'img'=>'magic.gif','price'=>0.2);
$botdrop[2][]['item']=array('rand'=>100,'itname'=>'Fast relife +50%','ittype'=>21,'rest_life'=>50,'description'=>'Sicocxlis Scrafi shevseba','acttime'=>3600,'broktime'=>604800,'img'=>'magic.gif','price'=>0.2);
$botdrop[2][]['item']=array('rand'=>1900,'itname'=>'Forest magic','ittype'=>21,'strength_p'=>100,'accuracy_p'=>50,'dexterity_p'=>50,'bron_p'=>50,'blocking_p'=>50,'ant_accuracy_p'=>50,'ant_dexterity_p'=>50,'ant_blocking_p'=>50,'life_p'=>50,'description'=>'+100 dzala , +50 sxva parametrebi, +100% sicocxlis scrafi shevseba','acttime'=>7200,'broktime'=>604800,'img'=>'magic.gif','price'=>100);

$botdrop[3][]['item']=array('rand'=>50,'itname'=>'Relife +20','ittype'=>20,'rest_life'=>20,'description'=>'Sicocxlis Shevseba +20','acttime'=>7200,'broktime'=>604800,'img'=>'magic.gif','price'=>0.2);
$botdrop[3][]['item']=array('rand'=>100,'itname'=>'Fast relife +50%','ittype'=>21,'rest_life'=>50,'description'=>'Sicocxlis Scrafi shevseba','acttime'=>3600,'broktime'=>604800,'img'=>'magic.gif','price'=>0.2);
$botdrop[3][]['item']=array('rand'=>1800,'itname'=>'Forest magic','ittype'=>21,'strength_p'=>100,'accuracy_p'=>50,'dexterity_p'=>50,'bron_p'=>50,'blocking_p'=>50,'ant_accuracy_p'=>50,'ant_dexterity_p'=>50,'ant_blocking_p'=>50,'life_p'=>50,'description'=>'+100 dzala , +50 sxva parametrebi, +100% sicocxlis scrafi shevseba','acttime'=>7200,'broktime'=>604800,'img'=>'magic.gif','price'=>100);

//$botdrop[4][]['res']=array('rand'=>500,'itname'=>'Gvelis shxami','resid'=>8);
$botdrop[4][]['item']=array('rand'=>200,'itname'=>'Relife +20','ittype'=>20,'rest_life'=>20,'description'=>'Sicocxlis Shevseba +20','acttime'=>7200,'broktime'=>604800,'img'=>'magic.gif','price'=>0.5);
$botdrop[4][]['item']=array('rand'=>100,'itname'=>'Fast relife +50%','ittype'=>21,'rest_life'=>50,'description'=>'Sicocxlis Scrafi shevseba','acttime'=>3600,'broktime'=>604800,'img'=>'magic.gif','price'=>0.2);
$botdrop[4][]['item']=array('rand'=>1700,'itname'=>'Forest magic','ittype'=>21,'strength_p'=>100,'accuracy_p'=>50,'dexterity_p'=>50,'bron_p'=>50,'blocking_p'=>50,'ant_accuracy_p'=>50,'ant_dexterity_p'=>50,'ant_blocking_p'=>50,'life_p'=>50,'description'=>'+100 dzala , +50 sxva parametrebi, +100% sicocxlis scrafi shevseba','acttime'=>7200,'broktime'=>604800,'img'=>'magic.gif','price'=>100);

$botdrop[5][]['item']=array('rand'=>150,'itname'=>'Relife +20','ittype'=>20,'rest_life'=>20,'description'=>'Sicocxlis Shevseba +20','acttime'=>7200,'broktime'=>604800,'img'=>'magic.gif','price'=>0.5);
$botdrop[5][]['item']=array('rand'=>100,'itname'=>'Fast relife +50%','ittype'=>21,'rest_life'=>50,'description'=>'Sicocxlis Scrafi shevseba','acttime'=>3600,'broktime'=>604800,'img'=>'magic.gif','price'=>0.2);
$botdrop[5][]['item']=array('rand'=>1600,'itname'=>'Forest magic','ittype'=>21,'strength_p'=>100,'accuracy_p'=>50,'dexterity_p'=>50,'bron_p'=>50,'blocking_p'=>50,'ant_accuracy_p'=>50,'ant_dexterity_p'=>50,'ant_blocking_p'=>50,'life_p'=>50,'description'=>'+100 dzala , +50 sxva parametrebi, +100% sicocxlis scrafi shevseba','acttime'=>7200,'broktime'=>604800,'img'=>'magic.gif','price'=>100);

$botdrop[6][]['item']=array('rand'=>100,'itname'=>'Relife +20','ittype'=>20,'rest_life'=>20,'description'=>'Sicocxlis Shevseba +20','acttime'=>7200,'broktime'=>604800,'img'=>'magic.gif','price'=>0.5);
$botdrop[6][]['item']=array('rand'=>100,'itname'=>'Fast relife +50%','ittype'=>21,'rest_life'=>50,'description'=>'Sicocxlis Scrafi shevseba','acttime'=>3600,'broktime'=>604800,'img'=>'magic.gif','price'=>0.2);
$botdrop[6][]['item']=array('rand'=>1500,'itname'=>'Forest magic','ittype'=>21,'strength_p'=>100,'accuracy_p'=>50,'dexterity_p'=>50,'bron_p'=>50,'blocking_p'=>50,'ant_accuracy_p'=>50,'ant_dexterity_p'=>50,'ant_blocking_p'=>50,'life_p'=>50,'description'=>'+100 dzala , +50 sxva parametrebi, +100% sicocxlis scrafi shevseba','acttime'=>7200,'broktime'=>604800,'img'=>'magic.gif','price'=>100);

$botdrop[7][]['item']=array('rand'=>200,'itname'=>'Relife +50','ittype'=>20,'rest_life'=>50,'description'=>'Sicocxlis Shevseba +50','acttime'=>7200,'broktime'=>604800,'img'=>'magic.gif','price'=>0.5);
$botdrop[7][]['item']=array('rand'=>250,'itname'=>'Gigantis eleqsiri +1','ittype'=>21,'strength_p'=>1,'accuracy_p'=>1,'dexterity_p'=>1,'blocking_p'=>1,'life_p'=>1,'description'=>'Gadzlierebuli parametrebi +1','acttime'=>7200,'img'=>'magic.gif','price'=>1);
$botdrop[7][]['item']=array('rand'=>200,'itname'=>'Fast relife +100%','ittype'=>21,'rest_life'=>100,'description'=>'Sicocxlis Scrafi shevseba','acttime'=>3600,'broktime'=>604800,'img'=>'magic.gif','price'=>0.2);
$botdrop[7][]['item']=array('rand'=>1400,'itname'=>'Forest magic','ittype'=>21,'strength_p'=>100,'accuracy_p'=>50,'dexterity_p'=>50,'bron_p'=>50,'blocking_p'=>50,'ant_accuracy_p'=>50,'ant_dexterity_p'=>50,'ant_blocking_p'=>50,'life_p'=>50,'description'=>'+100 dzala , +50 sxva parametrebi, +100% sicocxlis scrafi shevseba','acttime'=>7200,'broktime'=>604800,'img'=>'magic.gif','price'=>100);

$botdrop[8][]['item']=array('rand'=>150,'itname'=>'Relife +50','ittype'=>20,'rest_life'=>50,'description'=>'Sicocxlis Shevseba +50','acttime'=>7200,'broktime'=>604800,'img'=>'magic.gif','price'=>0.5);
$botdrop[8][]['item']=array('rand'=>200,'itname'=>'Gigantis eleqsiri +1','ittype'=>21,'strength_p'=>1,'accuracy_p'=>1,'dexterity_p'=>1,'blocking_p'=>1,'life_p'=>1,'description'=>'Gadzlierebuli parametrebi +1','acttime'=>7200,'img'=>'magic.gif','price'=>1);
$botdrop[8][]['item']=array('rand'=>200,'itname'=>'Fast relife +100%','ittype'=>21,'rest_life'=>100,'description'=>'Sicocxlis Scrafi shevseba','acttime'=>3600,'broktime'=>604800,'img'=>'magic.gif','price'=>0.2);
$botdrop[8][]['item']=array('rand'=>1300,'itname'=>'Forest magic','ittype'=>21,'strength_p'=>100,'accuracy_p'=>50,'dexterity_p'=>50,'bron_p'=>50,'blocking_p'=>50,'ant_accuracy_p'=>50,'ant_dexterity_p'=>50,'ant_blocking_p'=>50,'life_p'=>50,'description'=>'+100 dzala , +50 sxva parametrebi, +100% sicocxlis scrafi shevseba','acttime'=>7200,'broktime'=>604800,'img'=>'magic.gif','price'=>100);

$botdrop[9][]['item']=array('rand'=>100,'itname'=>'Relife +50','ittype'=>20,'rest_life'=>50,'description'=>'Sicocxlis Shevseba +50','acttime'=>7200,'broktime'=>604800,'img'=>'magic.gif','price'=>0.5);
$botdrop[9][]['item']=array('rand'=>150,'itname'=>'Gigantis eleqsiri +1','ittype'=>21,'strength_p'=>1,'accuracy_p'=>1,'dexterity_p'=>1,'blocking_p'=>1,'life_p'=>1,'description'=>'Gadzlierebuli parametrebi +1','acttime'=>7200,'img'=>'magic.gif','price'=>1);
$botdrop[9][]['item']=array('rand'=>200,'itname'=>'Fast relife +100%','ittype'=>21,'rest_life'=>100,'description'=>'Sicocxlis Scrafi shevseba','acttime'=>3600,'broktime'=>604800,'img'=>'magic.gif','price'=>0.2);
$botdrop[9][]['item']=array('rand'=>1200,'itname'=>'Forest magic','ittype'=>21,'strength_p'=>100,'accuracy_p'=>50,'dexterity_p'=>50,'bron_p'=>50,'blocking_p'=>50,'ant_accuracy_p'=>50,'ant_dexterity_p'=>50,'ant_blocking_p'=>50,'life_p'=>50,'description'=>'+100 dzala , +50 sxva parametrebi, +100% sicocxlis scrafi shevseba','acttime'=>7200,'broktime'=>604800,'img'=>'magic.gif','price'=>100);

$botdrop[10][]['item']=array('rand'=>200,'itname'=>'Relife +50','ittype'=>20,'rest_life'=>50,'description'=>'Sicocxlis Shevseba +50','acttime'=>7200,'broktime'=>604800,'img'=>'magic.gif','price'=>0.5);
$botdrop[10][]['item']=array('rand'=>250,'itname'=>'Gigantis eleqsiri +2','ittype'=>21,'strength_p'=>2,'accuracy_p'=>2,'dexterity_p'=>2,'blocking_p'=>2,'life_p'=>2,'description'=>'Gadzlierebuli parametrebi +2','acttime'=>7200,'img'=>'magic.gif','price'=>2);
$botdrop[10][]['item']=array('rand'=>200,'itname'=>'Fast relife +100%','ittype'=>21,'rest_life'=>100,'description'=>'Sicocxlis Scrafi shevseba','acttime'=>3600,'broktime'=>604800,'img'=>'magic.gif','price'=>0.2);
$botdrop[10][]['item']=array('rand'=>1100,'itname'=>'Forest magic','ittype'=>21,'strength_p'=>100,'accuracy_p'=>50,'dexterity_p'=>50,'bron_p'=>50,'blocking_p'=>50,'ant_accuracy_p'=>50,'ant_dexterity_p'=>50,'ant_blocking_p'=>50,'life_p'=>50,'description'=>'+100 dzala , +50 sxva parametrebi, +100% sicocxlis scrafi shevseba','acttime'=>7200,'broktime'=>604800,'img'=>'magic.gif','price'=>100);

$botdrop[11][]['item']=array('rand'=>150,'itname'=>'Relife +50','ittype'=>20,'rest_life'=>50,'description'=>'Sicocxlis Shevseba +50','acttime'=>7200,'broktime'=>604800,'img'=>'magic.gif','price'=>0.5);
$botdrop[11][]['item']=array('rand'=>200,'itname'=>'Gigantis eleqsiri +2','ittype'=>21,'strength_p'=>2,'accuracy_p'=>2,'dexterity_p'=>2,'blocking_p'=>2,'life_p'=>2,'description'=>'Gadzlierebuli parametrebi +2','acttime'=>7200,'img'=>'magic.gif','price'=>2);
$botdrop[11][]['item']=array('rand'=>200,'itname'=>'Fast relife +100%','ittype'=>21,'rest_life'=>100,'description'=>'Sicocxlis Scrafi shevseba','acttime'=>3600,'broktime'=>604800,'img'=>'magic.gif','price'=>0.2);
$botdrop[11][]['item']=array('rand'=>1000,'itname'=>'Forest magic','ittype'=>21,'strength_p'=>100,'accuracy_p'=>50,'dexterity_p'=>50,'bron_p'=>50,'blocking_p'=>50,'ant_accuracy_p'=>50,'ant_dexterity_p'=>50,'ant_blocking_p'=>50,'life_p'=>50,'description'=>'+100 dzala , +50 sxva parametrebi, +100% sicocxlis scrafi shevseba','acttime'=>7200,'broktime'=>604800,'img'=>'magic.gif','price'=>100);

$botdrop[12][]['item']=array('rand'=>100,'itname'=>'Relife +50','ittype'=>20,'rest_life'=>50,'description'=>'Sicocxlis Shevseba +50','acttime'=>7200,'broktime'=>604800,'img'=>'magic.gif','price'=>0.5);
$botdrop[12][]['item']=array('rand'=>150,'itname'=>'Gigantis eleqsiri +2','ittype'=>21,'strength_p'=>2,'accuracy_p'=>2,'dexterity_p'=>2,'blocking_p'=>2,'life_p'=>2,'description'=>'Gadzlierebuli parametrebi +2','acttime'=>7200,'img'=>'magic.gif','price'=>2);
$botdrop[12][]['item']=array('rand'=>200,'itname'=>'Fast relife +100%','ittype'=>21,'rest_life'=>100,'description'=>'Sicocxlis Scrafi shevseba','acttime'=>3600,'broktime'=>604800,'img'=>'magic.gif','price'=>0.2);
$botdrop[12][]['item']=array('rand'=>900,'itname'=>'Forest magic','ittype'=>21,'strength_p'=>100,'accuracy_p'=>50,'dexterity_p'=>50,'bron_p'=>50,'blocking_p'=>50,'ant_accuracy_p'=>50,'ant_dexterity_p'=>50,'ant_blocking_p'=>50,'life_p'=>50,'description'=>'+100 dzala , +50 sxva parametrebi, +100% sicocxlis scrafi shevseba','acttime'=>7200,'broktime'=>604800,'img'=>'magic.gif','price'=>100);

$botdrop[13][]['item']=array('rand'=>200,'itname'=>'Relife +100','ittype'=>20,'rest_life'=>100,'description'=>'Sicocxlis Shevseba +100','acttime'=>7200,'broktime'=>604800,'img'=>'magic.gif','price'=>1);
$botdrop[13][]['item']=array('rand'=>250,'itname'=>'Gigantis eleqsiri +3','ittype'=>21,'strength_p'=>3,'accuracy_p'=>3,'dexterity_p'=>3,'blocking_p'=>3,'life_p'=>3,'description'=>'Gadzlierebuli parametrebi +3','acttime'=>7200,'img'=>'magic.gif','price'=>3);
$botdrop[13][]['item']=array('rand'=>300,'itname'=>'Fast relife +200%','ittype'=>21,'rest_life'=>200,'description'=>'Sicocxlis Scrafi shevseba','acttime'=>3600,'broktime'=>604800,'img'=>'magic.gif','price'=>0.2);
$botdrop[13][]['item']=array('rand'=>1000,'itname'=>'Forest magic','ittype'=>21,'strength_p'=>100,'accuracy_p'=>50,'dexterity_p'=>50,'bron_p'=>50,'blocking_p'=>50,'ant_accuracy_p'=>50,'ant_dexterity_p'=>50,'ant_blocking_p'=>50,'life_p'=>50,'description'=>'+100 dzala , +50 sxva parametrebi, +100% sicocxlis scrafi shevseba','acttime'=>7200,'broktime'=>604800,'img'=>'magic.gif','price'=>100);

$botdrop[14][]['item']=array('rand'=>150,'itname'=>'Relife +100','ittype'=>20,'rest_life'=>100,'description'=>'Sicocxlis Shevseba +100','acttime'=>7200,'broktime'=>604800,'img'=>'magic.gif','price'=>1);
$botdrop[14][]['item']=array('rand'=>200,'itname'=>'Gigantis eleqsiri +3','ittype'=>21,'strength_p'=>3,'accuracy_p'=>3,'dexterity_p'=>3,'blocking_p'=>3,'life_p'=>3,'description'=>'Gadzlierebuli parametrebi +3','acttime'=>7200,'img'=>'magic.gif','price'=>3);
$botdrop[14][]['item']=array('rand'=>300,'itname'=>'Fast relife +200%','ittype'=>21,'rest_life'=>200,'description'=>'Sicocxlis Scrafi shevseba','acttime'=>3600,'broktime'=>604800,'img'=>'magic.gif','price'=>0.2);
$botdrop[14][]['item']=array('rand'=>900,'itname'=>'Forest magic','ittype'=>21,'strength_p'=>100,'accuracy_p'=>50,'dexterity_p'=>50,'bron_p'=>50,'blocking_p'=>50,'ant_accuracy_p'=>50,'ant_dexterity_p'=>50,'ant_blocking_p'=>50,'life_p'=>50,'description'=>'+100 dzala , +50 sxva parametrebi, +100% sicocxlis scrafi shevseba','acttime'=>7200,'broktime'=>604800,'img'=>'magic.gif','price'=>100);

$botdrop[15][]['item']=array('rand'=>100,'itname'=>'Relife +100','ittype'=>20,'rest_life'=>100,'description'=>'Sicocxlis Shevseba +100','acttime'=>7200,'broktime'=>604800,'img'=>'magic.gif','price'=>1);
$botdrop[15][]['item']=array('rand'=>150,'itname'=>'Gigantis eleqsiri +3','ittype'=>21,'strength_p'=>3,'accuracy_p'=>3,'dexterity_p'=>3,'blocking_p'=>3,'life_p'=>3,'description'=>'Gadzlierebuli parametrebi +3','acttime'=>7200,'img'=>'magic.gif','price'=>3);
$botdrop[15][]['item']=array('rand'=>300,'itname'=>'Fast relife +200%','ittype'=>21,'rest_life'=>200,'description'=>'Sicocxlis Scrafi shevseba','acttime'=>3600,'broktime'=>604800,'img'=>'magic.gif','price'=>0.2);
$botdrop[15][]['item']=array('rand'=>800,'itname'=>'Forest magic','ittype'=>21,'strength_p'=>100,'accuracy_p'=>50,'dexterity_p'=>50,'bron_p'=>50,'blocking_p'=>50,'ant_accuracy_p'=>50,'ant_dexterity_p'=>50,'ant_blocking_p'=>50,'life_p'=>50,'description'=>'+100 dzala , +50 sxva parametrebi, +100% sicocxlis scrafi shevseba','acttime'=>7200,'broktime'=>604800,'img'=>'magic.gif','price'=>100);

$botdrop[16][]['item']=array('rand'=>200,'itname'=>'Relife +200','ittype'=>20,'rest_life'=>200,'description'=>'Sicocxlis Shevseba +200','acttime'=>7200,'broktime'=>604800,'img'=>'magic.gif','price'=>2);
$botdrop[16][]['item']=array('rand'=>250,'itname'=>'Gigantis eleqsiri +4','ittype'=>21,'strength_p'=>4,'accuracy_p'=>4,'dexterity_p'=>4,'blocking_p'=>4,'life_p'=>4,'description'=>'Gadzlierebuli parametrebi +4','acttime'=>7200,'img'=>'magic.gif','price'=>4);
$botdrop[16][]['item']=array('rand'=>400,'itname'=>'Fast relife +300%','ittype'=>21,'rest_life'=>300,'description'=>'Sicocxlis Scrafi shevseba','acttime'=>3600,'broktime'=>604800,'img'=>'magic.gif','price'=>0.2);
$botdrop[16][]['item']=array('rand'=>700,'itname'=>'Forest magic','ittype'=>21,'strength_p'=>100,'accuracy_p'=>50,'dexterity_p'=>50,'bron_p'=>50,'blocking_p'=>50,'ant_accuracy_p'=>50,'ant_dexterity_p'=>50,'ant_blocking_p'=>50,'life_p'=>50,'description'=>'+100 dzala , +50 sxva parametrebi, +100% sicocxlis scrafi shevseba','acttime'=>7200,'broktime'=>604800,'img'=>'magic.gif','price'=>100);

$botdrop[17][]['item']=array('rand'=>150,'itname'=>'Relife +200','ittype'=>20,'rest_life'=>200,'description'=>'Sicocxlis Shevseba +200','acttime'=>7200,'broktime'=>604800,'img'=>'magic.gif','price'=>2);
$botdrop[17][]['item']=array('rand'=>200,'itname'=>'Gigantis eleqsiri +5','ittype'=>21,'strength_p'=>5,'accuracy_p'=>5,'dexterity_p'=>5,'blocking_p'=>5,'life_p'=>5,'description'=>'Gadzlierebuli parametrebi +5','acttime'=>7200,'img'=>'magic.gif','price'=>5);
$botdrop[17][]['item']=array('rand'=>400,'itname'=>'Fast relife +300%','ittype'=>21,'rest_life'=>300,'description'=>'Sicocxlis Scrafi shevseba','acttime'=>3600,'broktime'=>604800,'img'=>'magic.gif','price'=>0.2);
$botdrop[17][]['item']=array('rand'=>600,'itname'=>'Forest magic','ittype'=>21,'strength_p'=>100,'accuracy_p'=>50,'dexterity_p'=>50,'bron_p'=>50,'blocking_p'=>50,'ant_accuracy_p'=>50,'ant_dexterity_p'=>50,'ant_blocking_p'=>50,'life_p'=>50,'description'=>'+100 dzala , +50 sxva parametrebi, +100% sicocxlis scrafi shevseba','acttime'=>7200,'broktime'=>604800,'img'=>'magic.gif','price'=>100);

$botdrop[19][]['item']=array('rand'=>50,'itname'=>'Gremlin magic','ittype'=>21,'strength_p'=>5,'description'=>'Dzala +5','acttime'=>7200,'broktime'=>604800,'img'=>'magic.gif','price'=>10);
$botdrop[20][]['item']=array('rand'=>50,'itname'=>'Goblin magic','ittype'=>21,'strength_p'=>10,'description'=>'Dzala +10','acttime'=>7200,'broktime'=>604800,'img'=>'magic.gif','price'=>10);
$botdrop[21][]['item']=array('rand'=>50,'itname'=>'Zombie magic','ittype'=>21,'strength_p'=>15,'description'=>'Dzala +15','acttime'=>7200,'broktime'=>604800,'img'=>'magic.gif','price'=>10);
$botdrop[22][]['item']=array('rand'=>50,'itname'=>'Werewolf magic','ittype'=>21,'strength_p'=>20,'description'=>'Dzala +20','acttime'=>7200,'broktime'=>604800,'img'=>'magic.gif','price'=>10);
$botdrop[23][]['item']=array('rand'=>50,'itname'=>'Orc Arche magic','ittype'=>21,'strength_p'=>25,'description'=>'Dzala +25','acttime'=>7200,'broktime'=>604800,'img'=>'magic.gif','price'=>10);
$botdrop[24][]['item']=array('rand'=>50,'itname'=>'Vampire Bat magic','ittype'=>21,'strength_p'=>30,'description'=>'Dzala +30','acttime'=>7200,'broktime'=>604800,'img'=>'magic.gif','price'=>10);
$botdrop[25][]['item']=array('rand'=>50,'itname'=>'Evil Eye magic','ittype'=>21,'strength_p'=>35,'description'=>'Dzala +35','acttime'=>7200,'broktime'=>604800,'img'=>'magic.gif','price'=>10);
$botdrop[26][]['item']=array('rand'=>50,'itname'=>'Skeleton magic','ittype'=>21,'strength_p'=>40,'description'=>'Dzala +40','acttime'=>7200,'broktime'=>604800,'img'=>'magic.gif','price'=>10);
$botdrop[27][]['item']=array('rand'=>50,'itname'=>'Fungus magic','ittype'=>21,'strength_p'=>55,'description'=>'Dzala +55','acttime'=>7200,'broktime'=>604800,'img'=>'magic.gif','price'=>10);
$botdrop[28][]['item']=array('rand'=>50,'itname'=>'Colossus magic','ittype'=>21,'strength_p'=>60,'description'=>'Dzala +60','acttime'=>7200,'broktime'=>604800,'img'=>'magic.gif','price'=>10);







$botdrop[1001][]['item']=array('rand'=>10,'itname'=>'Gigantis eleqsiri +3','ittype'=>21,'strength_p'=>3,'accuracy_p'=>3,'dexterity_p'=>3,'blocking_p'=>3,'life_p'=>3,'description'=>'Gadzlierebuli parametrebi +3','acttime'=>7200,'broktime'=>604800,'img'=>'magic.gif','price'=>2);
$botdrop[1001][]['item']=array('rand'=>10,'itname'=>'Dzalis eleqsiri +5','ittype'=>21,'strength_p'=>5,'description'=>'Dzala +5','acttime'=>7200,'broktime'=>604800,'img'=>'magic.gif','price'=>2);
$botdrop[1001][]['item']=array('rand'=>10,'itname'=>'Moqnilobis eleqsiri +5','ittype'=>21,'accuracy_p'=>5,'description'=>'Moqniloba +5','acttime'=>7200,'broktime'=>604800,'img'=>'magic.gif','price'=>2);
$botdrop[1001][]['item']=array('rand'=>10,'itname'=>'Intuiciis eleqsiri +5','ittype'=>21,'dexterity_p'=>5,'description'=>'Intuicia +5','acttime'=>7200,'broktime'=>604800,'img'=>'magic.gif','price'=>2);
$botdrop[1001][]['item']=array('rand'=>10,'itname'=>'Blokirebis eleqsiri +5','ittype'=>21,'blocking_p'=>5,'description'=>'Blokireba +5','acttime'=>7200,'broktime'=>604800,'img'=>'magic.gif','price'=>2);
$botdrop[1001][]['item']=array('rand'=>10,'itname'=>'Gamdzleobis eleqsiri +5','ittype'=>21,'life_p'=>5,'description'=>'Gamdzleoba +5','acttime'=>7200,'broktime'=>604800,'img'=>'magic.gif','price'=>2);
$botdrop[1001][]['item']=array('rand'=>10,'itname'=>'Winaagmdegobis eleqsiri +20','ittype'=>21,'ant_accuracy_p'=>20,'ant_dexterity_p'=>20,'ant_blocking_p'=>20,'description'=>'Anti parametrebi +20','acttime'=>7200,'broktime'=>604800,'img'=>'magic.gif','price'=>100);
$botdrop[1001][]['item']=array('rand'=>3,'itname'=>'Magiuri gasagebi 1','lvl'=>1,'ittype'=>22,'broktime'=>604800,'img'=>'magic_key.gif','price'=>0);

$botdrop[1002][]['item']=array('rand'=>10,'itname'=>'Gigantis eleqsiri +5','ittype'=>21,'strength_p'=>5,'accuracy_p'=>5,'dexterity_p'=>5,'blocking_p'=>5,'life_p'=>5,'description'=>'Gadzlierebuli parametrebi +5','acttime'=>7200,'broktime'=>604800,'img'=>'magic.gif','price'=>2);
$botdrop[1002][]['item']=array('rand'=>10,'itname'=>'Dzalis eleqsiri +10','ittype'=>21,'strength_p'=>10,'description'=>'Dzala +10','acttime'=>7200,'broktime'=>604800,'img'=>'magic.gif','price'=>2);
$botdrop[1002][]['item']=array('rand'=>10,'itname'=>'Moqnilobis eleqsiri +10','ittype'=>21,'accuracy_p'=>10,'description'=>'Moqniloba +10','acttime'=>7200,'broktime'=>604800,'img'=>'magic.gif','price'=>2);
$botdrop[1002][]['item']=array('rand'=>10,'itname'=>'Intuiciis eleqsiri +10','ittype'=>21,'dexterity_p'=>10,'description'=>'Intuicia +10','acttime'=>7200,'broktime'=>604800,'img'=>'magic.gif','price'=>2);
$botdrop[1002][]['item']=array('rand'=>10,'itname'=>'Blokirebis eleqsiri +10','ittype'=>21,'blocking_p'=>10,'description'=>'Blokireba +10','acttime'=>7200,'broktime'=>604800,'img'=>'magic.gif','price'=>2);
$botdrop[1002][]['item']=array('rand'=>10,'itname'=>'Gamdzleobis eleqsiri +10','ittype'=>21,'life_p'=>10,'description'=>'Gamdzleoba +10','acttime'=>7200,'broktime'=>604800,'img'=>'magic.gif','price'=>2);
$botdrop[1002][]['item']=array('rand'=>10,'itname'=>'Winaagmdegobis eleqsiri +50','ittype'=>21,'ant_accuracy_p'=>50,'ant_dexterity_p'=>50,'ant_blocking_p'=>50,'description'=>'Anti parametrebi +50','acttime'=>7200,'broktime'=>604800,'img'=>'magic.gif','price'=>100);
$botdrop[1002][]['item']=array('rand'=>3,'itname'=>'Magiuri gasagebi 2','lvl'=>2,'ittype'=>22,'broktime'=>604800,'img'=>'magic_key.gif','price'=>0);

$botdrop[1003][]['item']=array('rand'=>10,'itname'=>'Gigantis eleqsiri +7','ittype'=>21,'strength_p'=>7,'accuracy_p'=>7,'dexterity_p'=>7,'blocking_p'=>7,'life_p'=>7,'description'=>'Gadzlierebuli parametrebi +7','acttime'=>7200,'broktime'=>604800,'img'=>'magic.gif','price'=>2);
$botdrop[1003][]['item']=array('rand'=>10,'itname'=>'Dzalis eleqsiri +15','ittype'=>21,'strength_p'=>15,'description'=>'Dzala +15','acttime'=>7200,'broktime'=>604800,'img'=>'magic.gif','price'=>2);
$botdrop[1003][]['item']=array('rand'=>10,'itname'=>'Moqnilobis eleqsiri +15','ittype'=>21,'accuracy_p'=>15,'description'=>'Moqniloba +15','acttime'=>7200,'broktime'=>604800,'img'=>'magic.gif','price'=>2);
$botdrop[1003][]['item']=array('rand'=>10,'itname'=>'Intuiciis eleqsiri +15','ittype'=>21,'dexterity_p'=>15,'description'=>'Intuicia +15','acttime'=>7200,'broktime'=>604800,'img'=>'magic.gif','price'=>2);
$botdrop[1003][]['item']=array('rand'=>10,'itname'=>'Blokirebis eleqsiri +15','ittype'=>21,'blocking_p'=>15,'description'=>'Blokireba +15','acttime'=>7200,'broktime'=>604800,'img'=>'magic.gif','price'=>2);
$botdrop[1003][]['item']=array('rand'=>10,'itname'=>'Gamdzleobis eleqsiri +15','ittype'=>21,'life_p'=>15,'description'=>'Gamdzleoba +15','acttime'=>7200,'broktime'=>604800,'img'=>'magic.gif','price'=>2);
$botdrop[1003][]['item']=array('rand'=>10,'itname'=>'Winaagmdegobis eleqsiri +100','ittype'=>21,'ant_accuracy_p'=>100,'ant_dexterity_p'=>100,'ant_blocking_p'=>100,'description'=>'Anti parametrebi +100','acttime'=>7200,'broktime'=>604800,'img'=>'magic.gif','price'=>100);
$botdrop[1003][]['item']=array('rand'=>3,'itname'=>'Magiuri gasagebi 3','lvl'=>3,'ittype'=>22,'broktime'=>604800,'img'=>'magic_key.gif','price'=>0);

$botdrop[1004][]['item']=array('rand'=>10,'itname'=>'Gigantis eleqsiri +10','ittype'=>21,'strength_p'=>10,'accuracy_p'=>10,'dexterity_p'=>10,'blocking_p'=>10,'life_p'=>10,'description'=>'Gadzlierebuli parametrebi +10','acttime'=>7200,'broktime'=>604800,'img'=>'magic.gif','price'=>2);
$botdrop[1004][]['item']=array('rand'=>10,'itname'=>'Dzalis eleqsiri +20','ittype'=>21,'strength_p'=>20,'description'=>'Dzala +20','acttime'=>7200,'broktime'=>604800,'img'=>'magic.gif','price'=>2);
$botdrop[1004][]['item']=array('rand'=>10,'itname'=>'Moqnilobis eleqsiri +20','ittype'=>21,'accuracy_p'=>20,'description'=>'Moqniloba +20','acttime'=>7200,'broktime'=>604800,'img'=>'magic.gif','price'=>2);
$botdrop[1004][]['item']=array('rand'=>10,'itname'=>'Intuiciis eleqsiri +20','ittype'=>21,'dexterity_p'=>20,'description'=>'Intuicia +20','acttime'=>7200,'broktime'=>604800,'img'=>'magic.gif','price'=>2);
$botdrop[1004][]['item']=array('rand'=>10,'itname'=>'Blokirebis eleqsiri +20','ittype'=>21,'blocking_p'=>20,'description'=>'Blokireba +20','acttime'=>7200,'broktime'=>604800,'img'=>'magic.gif','price'=>2);
$botdrop[1004][]['item']=array('rand'=>10,'itname'=>'Gamdzleobis eleqsiri +20','ittype'=>21,'life_p'=>20,'description'=>'Gamdzleoba +20','acttime'=>7200,'broktime'=>604800,'img'=>'magic.gif','price'=>2);
$botdrop[1004][]['item']=array('rand'=>10,'itname'=>'Winaagmdegobis eleqsiri +150','ittype'=>21,'ant_accuracy_p'=>150,'ant_dexterity_p'=>150,'ant_blocking_p'=>150,'description'=>'Anti parametrebi +150','acttime'=>7200,'broktime'=>604800,'img'=>'magic.gif','price'=>100);
$botdrop[1004][]['item']=array('rand'=>3,'itname'=>'Magiuri gasagebi 4','lvl'=>4,'ittype'=>22,'broktime'=>604800,'img'=>'magic_key.gif','price'=>0);

$botdrop[1005][]['item']=array('rand'=>10,'itname'=>'Gigantis eleqsiri +20','ittype'=>21,'strength_p'=>20,'accuracy_p'=>20,'dexterity_p'=>20,'blocking_p'=>20,'life_p'=>20,'description'=>'Gadzlierebuli parametrebi +20','acttime'=>7200,'broktime'=>604800,'img'=>'magic.gif','price'=>2);
$botdrop[1005][]['item']=array('rand'=>10,'itname'=>'Dzalis eleqsiri +40','ittype'=>21,'strength_p'=>40,'description'=>'Dzala +40','acttime'=>7200,'broktime'=>604800,'img'=>'magic.gif','price'=>2);
$botdrop[1005][]['item']=array('rand'=>10,'itname'=>'Moqnilobis eleqsiri +40','ittype'=>21,'accuracy_p'=>40,'description'=>'Moqniloba +40','acttime'=>7200,'broktime'=>604800,'img'=>'magic.gif','price'=>2);
$botdrop[1005][]['item']=array('rand'=>10,'itname'=>'Intuiciis eleqsiri +40','ittype'=>21,'dexterity_p'=>40,'description'=>'Intuicia +40','acttime'=>7200,'broktime'=>604800,'img'=>'magic.gif','price'=>2);
$botdrop[1005][]['item']=array('rand'=>10,'itname'=>'Blokirebis eleqsiri +40','ittype'=>21,'blocking_p'=>40,'description'=>'Blokireba +40','acttime'=>7200,'broktime'=>604800,'img'=>'magic.gif','price'=>2);
$botdrop[1005][]['item']=array('rand'=>10,'itname'=>'Gamdzleobis eleqsiri +40','ittype'=>21,'life_p'=>40,'description'=>'Gamdzleoba +40','acttime'=>7200,'broktime'=>604800,'img'=>'magic.gif','price'=>2);
$botdrop[1005][]['item']=array('rand'=>10,'itname'=>'Winaagmdegobis eleqsiri +200','ittype'=>21,'ant_accuracy_p'=>200,'ant_dexterity_p'=>200,'ant_blocking_p'=>200,'description'=>'Anti parametrebi +200','acttime'=>7200,'broktime'=>604800,'img'=>'magic.gif','price'=>200);

?>