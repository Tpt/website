$(function() {
	$('input#search-box').typeahead({
		minLength: 2,
		source: function(query, process) {
			$.getJSON(
				wsexport.url.booksearch,
				{
					lang: wsexport.lang,
					q: query
				},
				function(data) {
					process(data[1]);
				}
			);
			process(query);
		}
	});
});

