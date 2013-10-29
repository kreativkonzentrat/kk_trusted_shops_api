<?php
/**
 * JTL News Thumb Extension
 * File: add_thumbs.php, php file
 *
 * @author Niels Baumbach <niels.baumbach@kreativkonzentrat.de>
 * @copyright 2011, Kreativkonzentrat
 * @link http://www.kreativkonzentrat.de
 * @version 1.0
 * ------------------------------------------------------------------------------- */

global $oNewsUebersicht_arr;

if(oNewsUebersicht_arr !== null && 
	isset($oPlugin->oPluginEinstellungAssoc_arr['kk_add_thumbs']) && 
	$oPlugin->oPluginEinstellungAssoc_arr['kk_add_thumbs'] == "Y")
{ 
	// include simple html if needed
	if(!class_exists("simple_html_dom_node"))
	{		
		require_once("inc/simple_html_dom.php");
	}
	
	// loop the news items
	foreach($oNewsUebersicht_arr as $newsItem)
	{
	
		// 
		// Extract Thumbs 
		//
	
		if($newsItem->cText != "")
		{
			// parse html for image
			$htmlDom = str_get_html($newsItem->cText);
						
			// use only the first image
			$firstImg = $htmlDom->find('img',0);
			if($firstImg !== null) 
			{
				// image url
				if(isset($firstImg->src))
					$newsItem->cThumbUrl = $firstImg->src;
				// image alt
				if(isset($firstImg->alt))
					$newsItem->cThumbAlt = $firstImg->alt;
			}
		}
		
		//
		// Extract Categories
		//
		
		$query = "SELECT DISTINCT kNewsKategorie FROM tnewskategorienews WHERE kNews = " .  $newsItem->kNews;
		$newsItemCategories = $GLOBALS['DB']->executeQuery($query,2);
		$newsItem->newsItemCategories = $newsItemCategories;
	
	}
	
	
	
	
	// finally reset the news overview array
	$smarty->assign("oNewsUebersicht_arr", $oNewsUebersicht_arr);
}
?>
