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

if(isset($oPlugin->oPluginEinstellungAssoc_arr['kk_add_snipet']) && 
	$oPlugin->oPluginEinstellungAssoc_arr['kk_add_snipet'] == "Y")
{ 
	$tsId = $oPlugin->oPluginEinstellungAssoc_arr['kk_trusted_shops_id'];
	if($tsId != "Bitte eingeben")
	{
		if ($xml = simplexml_load_file("https://www.trustedshops.com/bewertung/show_xml.php?tsid=".$tsId)) 
		{
			$result = $xml->ratings->result[1];
			$max = "5.00";
			$count = $xml->ratings["amount"];
			
			// snipet html
			$snipet = "<div id='kk_ts_api_snipet'>Kundenbewertungen von Trusted Shops: ";
			$snipet .= "<span xmlns:v='http://rdf.data-vocabulary.org/#' typeof='v:Review-aggregate'>";
			$snipet .= "<span rel='v:rating'><span property='v:value'>" . $result . "</span>";
			$snipet .= "</span> / <span property='v:best'>" . $max . "</span>";
			$snipet .= " bei <span property='v:count'>" . $count . "</span> <a href='https://www.trustedshops.de/bewertung/info_" . $tsId . ".html'>Bewertungen</a></span></div>";

			// pq
			pq($oPlugin->oPluginEinstellungAssoc_arr['kk_trusted_shops_selektor'])->append($snipet);
		}
	}
}
?>
