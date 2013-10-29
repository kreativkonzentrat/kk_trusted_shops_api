{if $kk_ts_api_oRating}
	<div id='kk_ts_api_snipet' xmlns:v='http://rdf.data-vocabulary.org/#' typeof='v:Review-aggregate'>
		Kundenbewertungen von Trusted Shops:&nbsp;
		<span rel='v:rating'>
			<span property='v:value'>{$kk_ts_api_oRating->result}</span>
		</span>
		&nbsp;/&nbsp; 
		<span property='v:best'>
			{$kk_ts_api_oRating->max}
		</span>
		&nbsp;bei&nbsp;
		<span property='v:count'>{$kk_ts_api_oRating->count}</span> 
		<a href='https://www.trustedshops.de/bewertung/info_{$kk_ts_api_oRating->tsId}.html'>Bewertungen</a>
	</div>
{/if}