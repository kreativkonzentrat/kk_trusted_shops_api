{if $kk_ts_api_oRating}
	<div id='kk_ts_api_snipet' xmlns:v='http://rdf.data-vocabulary.org/#' typeof='v:Review-aggregate'>
		{$oPlugin_kk_ts_api->oPluginSprachvariableAssoc_arr.prefix}
		<span rel='v:rating'>
			<span property='v:value'>{$kk_ts_api_oRating->result}</span>
		</span>
		{$oPlugin_kk_ts_api->oPluginSprachvariableAssoc_arr.from}
		<span property='v:best'>
			{$kk_ts_api_oRating->max}
		</span>
		{$oPlugin_kk_ts_api->oPluginSprachvariableAssoc_arr.based_on}
		<span property='v:count'>{$kk_ts_api_oRating->count}</span> 
		<a href='https://www.trustedshops.de/bewertung/info_{$kk_ts_api_oRating->tsId}.html'>{$oPlugin_kk_ts_api->oPluginSprachvariableAssoc_arr.ratings}</a>
	</div>
{/if}