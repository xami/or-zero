<?php

class ApiController extends Controller
{

	public function actions()
	{
	}

    

	// Uncomment the following methods and override them if needed
	/*
	public function filters()
	{
		// return the filter configuration for this controller, e.g.:
		return array(
			'inlineFilterName',
			array(
				'class'=>'path.to.FilterClass',
				'propertyName'=>'propertyValue',
			),
		);
	}

	public function actions()
	{
		// return external action classes, e.g.:
		return array(
			'action1'=>'path.to.ActionClass',
			'action2'=>array(
				'class'=>'path.to.AnotherActionClass',
				'propertyName'=>'propertyValue',
			),
		);
	}
	*/
    public function actionArticle()
	{
        $id=Yii::app()->request->getParam('id', 0);
        $tianya=new Tianya();
        $st=$tianya->setArticle($id);
        pd($st);
    }

    public function actionItem()
	{
        $id=Yii::app()->request->getParam('id', 0);
        $tianya=new Tianya();
        $st=$tianya->setItem($id);
        pd($st);
    }

    public function actionChannel()
	{
        $id=Yii::app()->request->getParam('id', 0);
        $tianya=new Tianya();
        $st=$tianya->setChannel($id);
        pd($st);
    }


    public function actionF()
	{
		$g=trim($_REQUEST['_']);
		if(($op=strrpos($g,'.'))!==false){
			$info=substr($g, 0, $op);
		}else{
			$info=$g;
		}

		$src=MCrypy::decrypt(rawurldecode($info), Yii::app()->params['mcpass'], 128);
		if(Tools::is_url($src)===false){
			return false;
		}
        
		if(($op=strrpos($src,'.'))===false){
			return false;
		}
		$ext=substr($src, $op);

		switch ($ext) {
			//case ".pdf": $ctype="application/pdf"; break;
			//case ".exe": $ctype="application/octet-stream"; break;
			//case ".zip": $ctype="application/zip"; break;
			//case ".doc": $ctype="application/msword"; break;
			//case ".xls": $ctype="application/vnd.ms-excel"; break;
			//case ".ppt": $ctype="application/vnd.ms-powerpoint"; break;
			case ".gif": $ctype="image/gif"; break;
			case ".png": $ctype="image/png"; break;
			case ".jpeg":
			case ".jpg": $ctype="image/jpg"; break;
			default: $ctype="application/force-download";
	    }
	    if(empty($ctype)){
	    	return false;
	    }

        $dir=Yii::getPathOfAlias('application.data.tianya.img');
	    if(!is_dir($dir))
			mkdir($dir,0777,true);
//		echo $ext;
		$key=md5($src);
		$file=$dir.DIRECTORY_SEPARATOR.$key.$ext;
//		echo $dir.DIRECTORY_SEPARATOR.$key.$ext;

		$results=Yii::app()->cache->get($key);
		if($results!==false){
			$size=strlen($results);
			if(headers_sent()) die('Headers Sent');
			if(ini_get('zlib.output_compression')) ini_set('zlib.output_compression', 'Off');
			header("Pragma: public"); // required
			header("Cache-Control: max-age=864000");//24小时
			header('Last-Modified:'.gmdate('D, d M Y H:i:s').'GMT');
			header('Expires:'.gmdate('D, d M Y H:i:s', time() + '864000').'GMT');
			header("Cache-Control: private",false); // required for certain browsers
			header("Content-Type: $ctype");
			header("Content-Transfer-Encoding: binary");
		    header("Content-Length: ".$size);
		    ob_clean();
		    flush();
		    echo $results;
		    return true;
		}


//		$results=false;
		if(is_file($file)){
//		    echo $ctype;
//	    	$s=$this->g();
			$results=file_get_contents($file);
			$size=strlen($results);
		}else{
			$this->_referer=$src;
			if($this->g()->fetch($src)===false){
				return false;
			}

			if(($results=Yii::app()->cache->get($src))===false){
				$results=$this->g()->results;
				Yii::app()->cache->set($src, $results, 300);
			}



			$size=strlen($results);
			if(substr($results,0,1)=='<'){
				return false;
			}

			if(file_put_contents($file, $results)!==false){
//				echo $src;
				$fm=new File;
				$fm->find('`src`=:src', array(':src'=>$src));
				$fm->aid=$aid;
				$fm->type=substr($ext,1);
				$fm->size=$size;
				$fm->pnum=$page;
				$fm->name=$key;
				$fm->src=$src;
				$fm->fsrc=$fsrc;
				$fm->time=time();
				$fm->save();
			}else{
				return false;
			}

			//大于100k图片加水印
			if($size>60*1024){
				//$im=imagecreatefromstring($results);
				Yii::import('application.extensions.image.Image');
				$image = new Image($file);

				$height=$image->height;
				$width=$image->width;
				if($width>1440){
					$height=intval((1440/$width)*$height);
					$width=1440;
				}
				if($height>900){
					$width=intval((900/$height)*$width);
					$height=900;
				}
//				$waterfile=Yii::getPathOfAlias('application.data').DIRECTORY_SEPARATOR.'';
				$image->resize($width, $height);
				$image->watermark();
				$image->save();
			}
		}

		if($results===false){
			return false;
		}else{
			Yii::app()->cache->set($key, $results, 300);
		}

//		pr($results);
//		echo ($this->g()->results);die;
		if(headers_sent()) die('Headers Sent');
		if(ini_get('zlib.output_compression')) ini_set('zlib.output_compression', 'Off');
		header("Pragma: public"); // required
		header("Cache-Control: max-age=864000");//24小时
		header('Last-Modified:'.gmdate('D, d M Y H:i:s').'GMT');
		header('Expires:'.gmdate('D, d M Y H:i:s', time() + '864000').'GMT');
		header("Cache-Control: private",false); // required for certain browsers
		header("Content-Type: $ctype");
//		header("Content-Disposition: attachment; filename=\"".basename($fullPath)."\";" );
		header("Content-Transfer-Encoding: binary");
	    header("Content-Length: ".$size);
	    ob_clean();
	    flush();
	    echo $results;
	}

	public function actionA()
	{
//		$href=html_entity_decode(MCrypy::decrypt(rawurldecode(trim($_REQUEST['href'])), Yii::app()->params['mcpass'], 128));
		parse_str(MCrypy::decrypt(rawurldecode(trim($_REQUEST['href'])), Yii::app()->params['mcpass'], 128), $_get);
		$href=html_entity_decode(base64_decode($_get['a']));
		$main=rawurldecode(base64_decode(MCrypy::decrypt($_REQUEST['m'], Yii::app()->params['mcpass'], 128)));
		$title=rawurldecode($_get['t']);
		$rehref=html_entity_decode(base64_decode($_get['r']));
//		pr($href);

		$validate=new CUrlValidator();
		if($validate->validateValue($href)===false){
			return false;
		}

		$js_reload="setTimeout(r,30000);function r(){location.reload();}";
		$packer = new JavaScriptPacker($js_reload, 'Normal', true, false);
		$packed = $packer->pack();

		echo
'<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<style>a {text-decoration: none;}</style>
'."<title>外链转到:$title($href)</title></head><body><h1 style=\"margin-bottom:0;\"><a href='$rehref'>$title</a></h1>"
.'<script type="text/javascript"><!--
google_ad_client = "pub-4726192443658314";
/* 336x280, 创建于 11-3-10 */
google_ad_slot = "4619865687";
google_ad_width = 336;
google_ad_height = 280;
//-->
</script>
<script type="text/javascript"
src="http://pagead2.googlesyndication.com/pagead/show_ads.js">
</script>
<script type="text/javascript"><!--
google_ad_client = "pub-4726192443658314";
/* 336x280, 创建于 11-3-10 */
google_ad_slot = "4619865687";
google_ad_width = 336;
google_ad_height = 280;
//-->
</script>
<script type="text/javascript"
src="http://pagead2.googlesyndication.com/pagead/show_ads.js">
</script>
'."
<script language='javascript'>$packed</script>
<h2 style=\"margin:-5px 0 0 0;\"><a href='$href'>$title<span style=\"color:red;\">(点此跳转)</span></a></h2>".'
<script type="text/javascript"><!--
google_ad_client = "pub-4726192443658314";
/* 468x60, 创建于 11-3-3 */
google_ad_slot = "7613266296";
google_ad_width = 468;
google_ad_height = 60;
//-->
</script>
<script type="text/javascript"
src="http://pagead2.googlesyndication.com/pagead/show_ads.js">
</script>'."
<h4 style=\"margin:0;\"><a href=\"$rehref\">$title<span style=\"color:red;\">(返回继续阅读)</span></a></h4>".
'<script type="text/javascript"><!--
google_ad_client = "pub-4726192443658314";
/* 468x15, 09-8-19, link */
google_ad_slot = "9464079913";
google_ad_width = 468;
google_ad_height = 15;
//-->
</script>
<script type="text/javascript"
src="http://pagead2.googlesyndication.com/pagead/show_ads.js">
</script>'."<div style=\"width:680px;padding:10px;\">$main</div>".'
<form action="http://www.orzero.com/search" id="cse-search-box">
  <div>
    <input type="hidden" name="cx" value="partner-pub-4726192443658314:lofclyqlq8w" />
    <input type="hidden" name="cof" value="FORID:11" />
    <input type="hidden" name="ie" value="UTF-8" />
    <input type="text" name="q" size="40" />
    <input type="submit" name="sa" value="&#x641c;&#x7d22;" />
  </div>
</form>
<script type="text/javascript" src="http://www.google.com.hk/cse/brand?form=cse-search-box&amp;lang=zh-Hans"></script>
<script>
(function () {
  var ie = !!(window.attachEvent && !window.opera);
  var wk = /webkit\/(\d+)/i.test(navigator.userAgent) && (RegExp.$1 < 525);
  var fn = [];
  var run = function () { for (var i = 0; i < fn.length; i++) fn[i](); };
  var d = document;
  d.ready = function (f) {
    if (!ie && !wk && d.addEventListener)
      return d.addEventListener("DOMContentLoaded", f, false);
    if (fn.push(f) > 1) return;
    if (ie)
      (function () {
        try { d.documentElement.doScroll("left"); run(); }
        catch (err) { setTimeout(arguments.callee, 0); }
      })();
    else if (wk)
      var t = setInterval(function () {
        if (/^(loaded|complete)$/.test(d.readyState))
          clearInterval(t), run();
      }, 0);
  };
})();
var sl=document.getElementsByName("siteurl")[0];
sl.setAttribute("value","http://www.orzero.com");
</script>
'."
</body>
</html>";

	}
    
}