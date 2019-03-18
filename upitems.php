<?php
require_once('united_function.php');
if ($_SESSION['user_id']!=1) exit;
$arr=array(1=>'iaragi',2=>'tavi',3=>'zedatani',4=>'sharvali',5=>'fexsacmeli',6=>'xeltatmani',7=>'bechedi',8=>'amulet');
// tavi 2
// zedatani 3
// sharvlebi 4
// fexsacmeli 5
// xeltatmani 6
// bechedi 7
//amuleti 8

$img=$_FILES['img']['name'];
switch ($_FILES['img']['type']){
	case 'image/gif';
	$ex='gif';
	break;
	case 'image/x-png':
	case 'image/png':
	$ex='png';
	break;
	default;
	$ex='jpg';
	break;
}
$ex='gif';
$imgname=str_replace(' ','_',$_POST['name']).'.'.$ex;
move_uploaded_file($_FILES['img']['tmp_name'],'images/i/'.$imgname.'');

$db->Query("INSERT INTO rpg_items set itname='".$_POST['name']."', lvl='".$_POST['lvl']."', ittype='".$_POST['type']."', strength_p='".$_POST['str']."', accuracy_p='".$_POST['acc']."', dexterity_p='".$_POST['dex']."', blocking_p='".$_POST['blk']."', life_p='".$_POST['life']."', ant_accuracy_p='".$_POST['ant_acc']."', ant_dexterity_p='".$_POST['ant_dex']."', ant_blocking_p='".$_POST['ant_blk']."', bron_p='".$_POST['bron']."', img='".$imgname."', price='".$_POST['price']."', broktime=5184000, csum=500");
   echo '
   	   <form method="post" action="upitems.php" enctype="multipart/form-data">
   	   <table>
   	   <tr><td>Img</td><td><input type="file" name="img"></td></tr>
   	   <tr><td>Saxeli</td><td><input type="text" name="name"></td></tr>
       <tr><td>tipi:</td><td><select name="type">';
       foreach($arr as $key=>$value){
   	           echo '<option value="'.$key.'">'.$value.'</option>';
       }
       echo '</select></td></tr>';
   	   echo  '<tr><td>dzala</td><td><input type="text" name="str"></td></tr>
   	   <tr><td>Moqniloba</td><td><input type="text" name="acc"></td></tr>
   	   <tr><td>Intuicia</td><td><input type="text" name="dex"></td></tr>
   	   <tr><td>Blokireba</td><td><input type="text" name="blk"></td></tr>
   	   <tr><td>sicocxle</td><td><input type="text" name="life"></td></tr>
   	   <tr><td>broni</td><td><input type="text" name="bron"></td></tr>
   	   <tr><td>anti acileba</td><td><input type="text" name="ant_acc"></td></tr>
   	   <tr><td>anti intuicia</td><td><input type="text" name="ant_dex"></td></tr>
   	   <tr><td>anti blokireba</td><td><input type="text" name="ant_blk"></td></tr>
   	   <tr><td>level</td><td><input type="text" name="lvl"></td></tr>
   	   <tr><td>Fasi</td><td><input type="text" name="price"></td></tr>
   	   <tr><td colspan="2"><input type="submit" value="add" name="go"></td></tr>
   	   </table></form>';
?>
