{if $kk_ts_api_oRating}
	<div id='kk_ts_api_snipet' itemscope itemtype="http://schema.org/WebPage">
		<span itemprop="name">{$cShopName}</span>
		<span itemprop="aggregateRating" itemscope itemtype="http://schema.org/AggregateRating">
			Kundenbewertungen von Trusted Shops:&nbsp;
			<span itemprop="ratingValue">{$kk_ts_api_oRating->result}</span> von {$kk_ts_api_oRating->max}
			aus <span itemprop="reviewCount">{$kk_ts_api_oRating->count}</span> <a href='https://www.trustedshops.de/bewertung/info_{$kk_ts_api_oRating->tsId}.html'>Bewertungen</a>
		</span>
	</div>
{/if}