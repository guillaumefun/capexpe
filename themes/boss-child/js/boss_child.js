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
		extra = new Array();

		/* Filter is the first select box (original one) */
		filter = jq('#groups-order-by').val();

		/* We pickup the year value if selected*/
		if (jq('#groups-year').prop('selectedIndex') >0 ) {
			extra['year'] = jq('#groups-year').val();
		}

		/* We pickup the category value if selected*/
		if (jq('#groups-category').prop('selectedIndex') >0 ) {
			extra['category'] = jq('#groups-category').val();
		}

		/* If a search term is used, we take it into account */
		search_terms = false;
		if ( jq('.dir-search input').length ) {
			search_terms = jq('.dir-search input').val();
		}

		/* Send the AJAX request */
		bp_filter_request( object, filter, scope, 'div.' + object, search_terms, 1, arrayToQueryString(extra) , null, template );

		return false;
	});

	bp_init_dropdowns();

});

function bp_init_dropdowns(objects) {
	if ( null != jq.cookie('bp-groups-extras')) {
		extra = parseQuery(jq.cookie('bp-groups-extras'));
		if (null != extra['year']) {
			jq('li#groups-year-select select option[value="' + extra['year'] + '"]').prop( 'selected', true );
		}
		if (null != extra['category']) {
			jq('li#groups-category-select select option[value="' + extra['category'] + '"]').prop( 'selected', true );
		}
	}
}

function arrayToQueryString(array_in){
	var out = new Array();
	for(var key in array_in){
		out.push(key + '=' + encodeURIComponent(array_in[key]));
	}
	return out.join('&');
}

function parseQuery(qstr) {
	var query = {};
	var a = qstr.split('&');
	for (var i = 0; i < a.length; i++) {
		var b = a[i].split('=');
		query[decodeURIComponent(b[0])] = decodeURIComponent(b[1] || '');
	}
	return query;
}
