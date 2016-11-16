/*
 * The following .js overrides the change handler attached to the filter box
 * into a more genereic handler that manages all filter box from the groups
 * page.
 */

var jq = jQuery;

jq(document).ready( function() {

	/* First we unbind the previous handler */
	jq('li.filter select').off('change');

	/* When the filter select box is changed re-query */
	jq('li.filter select').change( function() {
		var el, css_id, object, scope, filter, search_terms, template, $gm_search;

		/* We first recover the currently selected tab (all or mine) */
		if ( jq('.item-list-tabs li.selected').length ) {
			el = jq('.item-list-tabs li.selected');
		} else {
			el = jq(this);
		}

		css_id = el.attr('id').split('-');
		object = css_id[0];
		scope = css_id[1];

		/* Not sure what this it */
		template = null;

		/* Filter is the first select box (original one) */
		filter = jq('#groups-order-by').val();

		/* We also pickup the year and category values */
		year     = jq('#groups-year').val();
		category = jq('#groups-category').val();

		/* If a search term is used, we take it into account */
		search_terms = false;
		if ( jq('.dir-search input').length ) {
			search_terms = jq('.dir-search input').val();
		}

		/* Bundle the extra data. */
		extra = {
			'year': year,
			'category': category
		}

		/* Send the AJAX request */
		bp_filter_request( object, filter, scope, 'div.' + object, search_terms, 1, extra , null, template );

		return false;
	});
});
