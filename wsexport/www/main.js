$(function() {
        $("#search-box").autocomplete({
                source: function(request, response) {
                        $.ajax({
                                url: wsexport.url.booksearch,
                                dataType: "json",
                                data: {
                                        format: "opensearchsuggestions",
                                        limit: 10,
                                        startsWith: true,
                                        q: request.term
                                },
                                success: function(data) {
                                        response( $.map( data[1], function(item, i) {
                                                return {
                                                        label: data[2][i],
                                                        value: data[1][i]
                                                }
                                        }));
                                }
                        });
                },
                minLength: 2,
                select: function(event, ui) {
                        this.value = ui.item.value;
                        $("#quick-search").submit();
                }
        });
});
