<?php
/**
 * KK Trusted Shops Rich Snipet
 * File: add_snipet.php, php file
 *
 * @author Niels Baumbach <niels.baumbach@kreativkonzentrat.de>
 * @copyright 2013, Kreativkonzentrat
 * @link http://www.kreativkonzentrat.de
 * @version 1.03
 * ------------------------------------------------------------------------------- */

if($oPlugin->oPluginEinstellungAssoc_arr['kk_add_snipet'] == "Y" && ($oPlugin->oPluginEinstellungAssoc_arr['kk_integration_mode'] == "all" || $smarty->get_template_vars("nSeitenTyp") == "18"))
{ 
	include_once("inc/class.KkTsApiHelper.php");
	require_once(PFAD_ROOT . PFAD_CLASSES . "class.JTL-Shop.Jtllog.php"); 

	// static setting as recommend by TS developer
	$tsBaseUrl = "http://www.trustedshops.com/api/ratings/v1/";

	// get plugin settings
	$tsId = $oPlugin->oPluginEinstellungAssoc_arr['kk_trusted_shops_id'];
	$tsMaxTimeout = $oPlugin->oPluginEinstellungAssoc_arr['kk_trusted_shops_max_timeout'];
	$tsCacheDir = $oPlugin->oPluginEinstellungAssoc_arr['kk_ts_api_cache_dir'];
	$tsCacheLifetime = $oPlugin->oPluginEinstellungAssoc_arr['kk_ts_api_cache_lifetime'];
	$tsRequestUrl = $tsBaseUrl . $tsId . ".xml"; 
		
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
				$content = "";
				if(file_exists($customAggregateFilePath))
					$content = $smarty->fetch($customAggregateFilePath);
				else
					$content = $smarty->fetch($aggregateFilePath);
		
				// pq
				$function = $oPlugin->oPluginEinstellungAssoc_arr['php_query_function'];
				$selector = $oPlugin->oPluginEinstellungAssoc_arr['php_query_selector'];
				call_user_func(array(pq($selector), $function), $content);
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