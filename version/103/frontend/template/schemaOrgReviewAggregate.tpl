{if $kk_ts_api_oRating}
	<div id='kk_ts_api_snipet' itemscope itemtype="http://schema.org/WebPage">
		<span itemprop="name">{$cShopName}</span>
		<span itemprop="aggregateRating" itemscope itemtype="http://schema.org/AggregateRating">
			{$oPlugin_kk_ts_api->oPluginSprachvariableAssoc_arr.prefix}
			<span itemprop="ratingValue">{$kk_ts_api_oRating->result}</span> {$oPlugin_kk_ts_api->oPluginSprachvariableAssoc_arr.from} {$kk_ts_api_oRating->max}
			{$oPlugin_kk_ts_api->oPluginSprachvariableAssoc_arr.based_on} <span itemprop="reviewCount">{$kk_ts_api_oRating->count}</span> <a href='https://www.trustedshops.de/bewertung/info_{$kk_ts_api_oRating->tsId}.html'>{$oPlugin_kk_ts_api->oPluginSprachvariableAssoc_arr.ratings}</a>
		</span>
	</div>
{/if}