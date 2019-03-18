<?php 
///By Qisho
require_once('configs/theme_config.php');
class xhtml {
	function getHeader($title='title',$refresh='',$description='',$keywords='',$css=CSS_URL,$cache=false,$lang='ru',$icourl=''){
		$res = '<?xml version="1.0" encoding="UTF-8"?>
			   <!DOCTYPE html PUBLIC "-//WAPFORUM//DTD XHTML Mobile 1.0//EN" "http://www.wapforum.org/DTD/xhtml-mobile10.dtd">
			   <html xmlns="http://www.w3.org/1999/xhtml"xml:lang="'.$lang.'">
			   <head>';
        if ($keywords!='')      $res.= '<meta name="keywords" content="'.$keywords.'"/>';
        if ($description!='')   $res.= '<meta name="description" content="'.$description.'"/>';
        if ($cache==true)       $res.= '<meta http-equiv="Cache-Control" content="no-cache"/>';
        if (is_array($refresh)) $res.= '<meta http-equiv="refresh" content="'.$refresh[0].';URL='.$refresh[1].'"/>';
        if ($icourl!='')        $res.='<link rel="shortcut icon" type="image/x-ico" href="'.$icourl.'"/>';
        $res.= '<title>'.$title.'</title>';
        if ($css!='')           $res.='<link rel="stylesheet" href="'.$css.'" type="text/css"/>';
        $res.= '</head><body>';
        return $res;
	}
	function getFooter(){
		return '</body></html>';
	}
	function createPage($headtitle='',$topc='',$middlec='',$downc=''){
global $lang;
		if (is_array($title)) {
		   $title=$headtitle[0];
		   $refresh[0]=$headtitle[1];
		   $refresh[1]=$headtitle[2];
		}else {
			$title=$headtitle;
		}
		if (empty($title)) $title='legenda.wapop.org';
		if ($_SESSION['user_id']>0) $downc.=(!empty($downc)?
			$this->getBR():'').
			$this->getImage('icon/cp.gif','*','12','12').$this->getLink('index.php?cabinet',$lang->cabinet).
			$this->getBR().
			$this->getImage('icon/ph.png','*','12','12').$this->getLink('index.php?postman',$lang->myphost).
			$this->getBR().
		    $this->getImage('icon/rul.gif','*','12','12').$this->getLink('index.php?rules',$lang->gamerules);
		if ($_SESSION['lang']==1 && $_SESSION['user_id']>0){
			$downc.=$this->getBR().$this->getImage('icon/news.gif','*','12','12').$this->getLink('index.php?news','Siaxleebi').$this->getBR().
			
			$this->getImage('icon/qu.png','*','12','12').$this->getLink('index.php?faq','Daxmareba').$this->getBR();
		}   
		    $downc.=$this->getBR();
            $downc.='(c)'.$this->getLink('http://legenda.wapop.org','Legenda.Wapop.Org');
		if (!empty($_SESSION['wmasterurl'])){
		}
		$topc=(!empty($topc)?$topc:$title);
		//$downc.=$this->getLink('index.php','Mtavari');
		if (defined('TOPC_STYLE')) $topc=$this->getBlock($topc,TOPC_STYLE);
		else $topc=$this->getBlock($topc);
        if (defined('MIDDLEC_STYLE')) $middlec=$this->getBlock($middlec,MIDDLEC_STYLE);
        else $middlec=$this->getBlock($middlec);
        if (defined('DOWNC_STYLE')) $downc=$this->getBlock($downc,DOWNC_STYLE);		
		$content.=$this->getHeader($title,$refresh,'wap rpg game','rpg, wap, game, mobile, fight');
		$content.=$topc.$middlec.$downc;
		$content.=$this->getFooter();
		unset($_SESSION['Q']);
		echo $content;
		
		exit;
	}
	function startForm($action,$method='post',$multi=''){
		global $ses;
		if (strpos($action,'?')!==false) $add='&amp;';
		else $add='?';
		return '<form action="'.$action.$add.$ses.'" method="'.$method.'" '.($multi==1?'enctype="multipart/form-data"':'').'>';
	}
	function endForm(){
		return '</form>';
	}
	function getInput($name,$value='',$type='text',$style=''){
		return '<input type="'.$type.'" name="'.$name.'" '.($value!=''?'value="'.$value.'"':'').' '.($style!=''?'class="'.$style.'"':'').'/>';
	}
	function getTextarea($name,$value='',$rows=5,$cols=5){
		return '<textarea name="'.$name.'" rows="'.$rows.'" cols="'.$cols.'">'.$value.'</textarea>';
	}
	function getSelect($name,$SelArr,$value=''){
		$cont='<select name="'.$name.'">';
		foreach ($SelArr as $key=>$val){
			$cont.='<option value="'.$key.'" '.($key==$value?'selected':'').'>'.$val.'</option>';
		}
		$cont.='</select>';
		return $cont;
	}
	function getCheckBox($name,$value,$sel=''){
		$source='<input type="checkbox" name="'.$name.'" value="'.$value.'" '.(!empty($sel)?'checked':'').'/>';
		return $source;
	}
	function getBlock($content='',$style=''){
		return '<div '.($style!=''?'class="'.$style.'"':'').'>'.$content.'</div>';
	}
	function getLink($url,$title,$style=''){
		global $ses;
		if (strpos($url,'?')!==false) $add='&amp;';
		else $add='?';
		if (empty($url) || empty($title)) return false;
		else {
			return '<a '.($style!=''?'class="'.$style.'"':'').' href="'.$url.$add.rand(100,999).'&amp;'.$ses.'">'.$title.'</a>';
		}
	}
	function getSpan($text,$style=''){
		return '<span '.($style!=''?'class="'.$style.'"':'').'>'.$text.'</span>';
	}
	function getBR(){
		return '<br/>';
	}
	function getImage($url,$alt='',$width='',$height='',$style=''){
		return '<img '.($style!=''?'class='.$style.'':'').' src="'.$url.'" alt="'.$alt.'" '.($width!=''?'width="'.$width.'"':'').' '.($width!=''?'height="'.$height.'"':'').'/>';
	}
	function redirect($url,$isamp=''){
		global $ses;
			header('Location:'.$url.(substr_count($url,'?')>0?'&':'?').SES.'');
	}
}
?>