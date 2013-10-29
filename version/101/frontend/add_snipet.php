<?php
/**
 * KK Trusted Shops Rich Snipet
 * File: add_snipet.php, php file
 *
 * @author Niels Baumbach <niels.baumbach@kreativkonzentrat.de>
 * @copyright 2012, Kreativkonzentrat
 * @link http://www.kreativkonzentrat.de
 * @version 1.01
 * ------------------------------------------------------------------------------- */

if(isset($oPlugin->oPluginEinstellungAssoc_arr['kk_add_snipet']) && 
	$oPlugin->oPluginEinstellungAssoc_arr['kk_add_snipet'] == "Y")
{ 
	include_once("inc/class.KkTsApiHelper.php");
	require_once(PFAD_ROOT . PFAD_CLASSES . "class.JTL-Shop.Jtllog.php"); 

	// get plugin settings
	$tsId = $oPlugin->oPluginEinstellungAssoc_arr['kk_trusted_shops_id'];
	$tsBaseUrl = $oPlugin->oPluginEinstellungAssoc_arr['kk_trusted_shops_base_url'];
	$tsMaxTimeout = $oPlugin->oPluginEinstellungAssoc_arr['kk_trusted_shops_max_timeout'];
	$tsCacheDir = $oPlugin->oPluginEinstellungAssoc_arr['kk_ts_api_cache_dir'];
	$tsCacheLifetime = $oPlugin->oPluginEinstellungAssoc_arr['kk_ts_api_cache_lifetime'];
	$tsRequestUrl = $tsBaseUrl . $tsId; 
		
	if($tsId != "Bitte eingeben")
	{
		// get content of the ts xml file
		$strData =  KkTsApiHelper::getFileContentCached($tsRequestUrl, PFAD_ROOT . $tsCacheDir, $tsCacheLifetime, $tsMaxTimeout);
		
		if($strData != "")
		{
			$oRating = KkTsApiHelper::getAggregatedRating($strData);
			
			if($oRating != false)
			{
				$oRating->tsId = $tsId;
				$smarty->assign("kk_ts_api_oRating", $oRating);  
				
				// generate rating block
				$templateFileName = $oPlugin->oPluginEinstellungAssoc_arr['kk_ts_api_template'];
				$customAggregateFilePath = $oPlugin->cFrontendPfad  . "template/" . $templateFileName . "_custom.tpl";
				$aggregateFilePath = $oPlugin->cFrontendPfad  . "template/" . $templateFileName . ".tpl";
								
				// use users custom template
				if(file_exists($customAggregateFilePath))
					$ratingHtml = $smarty->fetch($customAggregateFilePath);
				else
					$ratingHtml = $smarty->fetch($aggregateFilePath);
		
				// pq
				pq($oPlugin->oPluginEinstellungAssoc_arr['kk_trusted_shops_selektor'])->append($ratingHtml);
			}
			else
			{
				Jtllog::writeLog("kk_ts_api: error parsing rating xml data from string data file" , JTLLOG_LEVEL_ERROR); 
			}
		}
		else
		{
			Jtllog::writeLog("kk_ts_api: error retrieving file data from URL " . $tsRequestUrl , JTLLOG_LEVEL_ERROR); 
		}
	}
	else
	{
		Jtllog::writeLog("kk_ts_api: please enter a Trusted Shops ID in the plugin settings" , JTLLOG_LEVEL_ERROR); 
	}
}
?>
