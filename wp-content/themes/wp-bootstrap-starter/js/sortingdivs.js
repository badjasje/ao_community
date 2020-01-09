jQuery(function($) {

	function sortValues(nr, sortSelector, sortOrder, sortNumber) {
		$('.userRow'+nr).sort(function(a,b){
			var order = sortOrder == 'asc'?1:-1;
			if (sortNumber){
				var numA = Number($(a).find(sortSelector).text().replace(/[^0-9]/g, ''));
				var numB = Number($(b).find(sortSelector).text().replace(/[^0-9]/g, ''));
				return order * ((numA < numB) ? -1 : (numA > numB) ? 1 : 0);
			} else {
				return order * ($(a).find(sortSelector).text().localeCompare($(b).find(sortSelector).text()));
			}
		}).appendTo($('#values'+(nr>1?nr:'')));

		localStorage.setItem('sort'+nr, JSON.stringify({sortBy:sortSelector,sortOrder:sortOrder}));
	}

	for(var i=1; i<7; i++) {
		var thisSort = JSON.parse(localStorage.getItem('sort'+i));
		if(!!thisSort) {
			sortValues(i, thisSort.sortBy, thisSort.sortOrder);
		}

		$('.sort'+(i>1?i:'')).data('nr', i).on('click', function(e) {
			e.preventDefault();
			var el = $(this);
			if(!!el.data('sort')) {
				var sortNumber = el.hasClass('sort-number') ? true:false;
				var sortOrder = el.data('sort-order')=='desc' ? 'desc':'asc';
				el.data('sort-order', (sortOrder=='desc' ? 'asc' : 'desc'));
				sortValues(el.data('nr'), el.data('sort'), sortOrder, sortNumber);
			}
		});
	}

});