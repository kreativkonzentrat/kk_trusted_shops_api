<?php

require_once(PFAD_ROOT . PFAD_CLASSES . "class.JTL-Shop.Jtllog.php");

class KkTsApiHelper
{
	function getFileContentRemote($timeout, $url)
	{
		$ch = curl_init();
		curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
		curl_setopt($ch,CURLOPT_SSL_VERIFYHOST ,false);
		curl_setopt($ch,CURLOPT_URL,$url);
		curl_setopt($ch,CURLOPT_RETURNTRANSFER,1);
		curl_setopt($ch,CURLOPT_CONNECTTIMEOUT,$timeout);
		$data = curl_exec($ch);
		
		if(curl_errno($ch))
			KkTsApiHelper::logError("CURL Error " . curl_errno($ch)); 
				
		curl_close($ch);
		return $data;
	}
	
	function getAggregatedRating($strXmlContent)
	{
		if ($xml = simplexml_load_string($strXmlContent) )
		{
			$ret = new stdClass;
			
			// average rating
			foreach($xml->ratings->result as $result)
			{
				if($result["name"] == "average")
				{
					$ret->result = $result;
					break;
				}
			}
			
			// max rating
			$ret->max = "5.00";
			
			// nr of ratings
			$ret->count = $xml->ratings["amount"];
			return $ret;
		}
		else
		{
			// error reading xml
			return false;
		}
	}
	
	function getFileContentCached($fileUrl, $cacheDir, $lifetime, $timeout)
	{
		// get file and folder names
		$cacheFileName = md5($fileUrl) . ".xml";
		$cacheFilePath = $cacheDir . "/" . $cacheFileName;

		// cache it
		$content = "";

		if(!file_exists($cacheFilePath) || filemtime($cacheFilePath) < (time()-$lifetime))
		{
			$content = KkTsApiHelper::getFileContentRemote($timeout, $fileUrl);
		
			// error retrieving url content
			if($content === false)
			{
				KkTsApiHelper::logError("Error while getting file content for url " . $fileUrl);
			}
			else
			{
				// content valid, so try to cache it
				$success = KkTsApiHelper::cacheFile($fileUrl, $content, $cacheDir);
				
				// error while caching
				if($sucess === false)
				{
					KkTsApiHelper::logError("Error while caching file content to disk for url " . $fileUrl);
							
					// even if there errors in the previous caching step, we will try
					// to read a previously created cache file
				}
			}
		}

		// try to read from cache
		// but do this only if we having no "live" content result from the previous step
		if($content == "")
		{
			// error: cache file does not exist
			if(!file_exists($cacheFilePath))
			{
				KkTsApiHelper::logError("Error while reading cache file. File does not exist: " . $cacheFilePath);
			}
			else
			{
				// file exist, so try to read it
				$content = file_get_contents($cacheFilePath);
				
				KkTsApiHelper::logDebug("Read cached ts api file!");
				
				// error reading cached file
				if($content === false)
				{
					KkTsApiHelper::logError("Although the cache file seems to exist, there was an error reading it: " . $cacheFilePath);
				}
			}
		}

		return $content; 
	}

	function cacheFile($fileUrl, $content, $cacheDir = "kk_rss_feed_cache")
	{
		$cacheFileName = md5($fileUrl) . ".xml";
		$cacheFilePath = $cacheDir . "/" . $cacheFileName;
 
		// create dir if not exists	
		if(!file_exists($cacheDir)) 
		{		 
			$dirSuccess == mkdir($cacheDir); 
		
			if($dirSuccess === false)
			{
				KkTsApiHelper::logError("dir creation failed for folder " . $cacheDir);
				return false;
			}
		}

		// write cache file
		$putSuccess = file_put_contents($cacheFilePath, $content);	
		if($putSuccess === false)
		{
			KkTsApiHelper::logError("cache file writing failed for filepath " . $cacheFilePath . " in folder " . $cacheDir);
			return false; 
		}
	}
	
	function logError($msg)
	{
		$prefix = "kk_ts_api";
		Jtllog::writeLog($prefix . ": " . $msg, JTLLOG_LEVEL_ERROR);
	}
	
	function logDebug($msg)
	{
		$prefix = "kk_ts_api";
		Jtllog::writeLog($prefix . ": " . $msg, JTLLOG_LEVEL_DEBUG);
	}
}

?>