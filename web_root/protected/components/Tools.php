<?php
class Tools
{
    private static $_snoopy;
    private $result;
    
    public function OZSnoopy($URI='', $formvars="", $referer='', $expire=60)
	{
		if(self::$_snoopy == null){
			self::$_snoopy = new Snoopy();
			self::$_snoopy->agent = "Mozilla/4.0 (compatible; MSIE 6.0; Windows NT 5.1)";
			self::$_snoopy->rawheaders["Pragma"] = "no-cache";
		}

		if(empty($URI)){
			return self::$_snoopy;
		}

		if(self::is_url($URI)===false){
			return false;
		}
        
        if(!empty($referer)&&self::is_url($referer)){
		    self::$_snoopy->referer = $referer;
        }else{
            self::$_snoopy->referer = $URI;
        }

		$cache = Yii::app()->cache;		//默认缓存30秒远程数据
		if(is_array($formvars) && !empty($formvars)){
			$key = md5(md5($URI).md5(serialize($formvars)));
			if((self::$_snoopy->results=$cache->get($key))!==false){
                return self::$_snoopy->results;
            }

			if(self::$_snoopy->submit($URI,$formvars)!==false){
				$cache->set($key, self::$_snoopy->results, $expire);
				return self::$_snoopy->results;
			}
		}else{
			$key = md5($URI);
			if((self::$_snoopy->results=$cache->get($key))!==false)
				return self::$_snoopy->results;
			if(self::$_snoopy->fetch($URI)!==false){
				$cache->set($key, self::$_snoopy->results, $expire);
				return self::$_snoopy->results;
			}
		}
        
		return false;
	}

	public static function OZCurl($src, $expire=60, $show=false)
	{

		$expire = intval($expire)>20 ? intval($expire) : 20;
		$src = trim($src);
		if(empty($src)) return false;

        if(!self::is_url($src)) return false;
		
		$c = null;
		$key = md5($src);
		$cache = Yii::app()->cache;
		$c=$cache->get($key);
		
		if(empty($c)){
			//Run curl
			$curl = Yii::app()->CURL;
			$curl->run(array(CURLOPT_REFERER => $src));
			$curl->setUrl($src);
			$curl->exec();
			
			if(Yii::app()->CURL->isError()) {
				// Error
				var_dump($curl->getErrNo());
				var_dump($curl->getError());
				
			}else{
				// More info about the transfer
				$c=array(
					'ErrNo'=>$curl->getErrNo(),
					'Error'=>$curl->getError(),
					'Header'=>$curl->getHeader(),
					'Info'=>$curl->getInfo(),
					'Result'=>$curl->getResult(),
				);
			}
            //小于5M缓存
            if(sizeof($c)<1024*1024*5){
                $cache->set($key, $c, $expire);
            }
		}
		
		if($show==true){
			if(!empty($c['Info']['content_type']))
				header('Content-Type: '.$c['Info']['content_type']);
			if($c['Info']['http_code']==200)
				echo $c['Result'];
		}
		
		return $c;
	}
	
	public static function is_url($url){
		$validate=new CUrlValidator();
		if(empty($url)){
			return false;
		}
		if($validate->validateValue($url)===false){
			return false;
		}
	    return true;
	}

    //取得字段间字符
	public static function cutContent($content='', $start='', $end='', $reg=false)
	{
        //是否启用正则
        if($reg){
            $e_start=preg_split($start, $content, 2, PREG_SPLIT_OFFSET_CAPTURE);
            if(empty($e_start[1][0]) || empty($e_start[1][1])){
                return false;
            }

            $e_end=preg_split($end, $e_start[1][0], 2, PREG_SPLIT_OFFSET_CAPTURE);
            if(empty($e_end[1][0]) || empty($e_end[1][1])){
                return false;
            }

            return $e_end[0][0];
        }else{
            $e_start=explode($start, $content);
            if(!isset($e_start[1])){
                return false;
            }
            $e_end=explode($end, $e_start[1]);
            if(!isset($e_end[1])){
                return false;
            }

            return $e_end[0];
        }

	}

}