jQuery(document).ready(function(){
	jQuery('.sort').click(function(e){
		e.preventDefault();
		var el = jQuery(this);
		var sortSelector = el.data('sort');
		var sortNumber = el.hasClass('sort-number') ? true:false;
		var sortOrder = el.data('sort-order')=='desc' ? 'desc':'asc';

		jQuery('.userRow1').sort(function(a,b){
			var order = sortOrder == 'asc'?1:-1;
			if (sortOrder == 'asc'){
				el.data('sort-order', 'desc');
			}else{
				el.data('sort-order', 'asc');
			}

			if (sortNumber){
				var numA = Number(jQuery(a).find(sortSelector).text().replace(/[^0-9]/g, ''));
				
				var numB = Number(jQuery(b).find(sortSelector).text().replace(/[^0-9]/g, ''));
				return order*((numA < numB) ? -1 : (numA > numB) ? 1 : 0);
			}else{
				return order*(jQuery(a).find(sortSelector).text().localeCompare(jQuery(b).find(sortSelector).text()));
			}

		}).appendTo(jQuery('#values'));
	});
	
	
	jQuery('.sort2').click(function(e){
		e.preventDefault();
		var el = jQuery(this);
		var sortSelector = el.data('sort');
		var sortNumber = el.hasClass('sort-number')?true:false;
		var sortOrder = el.data('sort-order')=='desc'?'desc':'asc';

		jQuery('.userRow2').sort(function(a,b){
			var order = sortOrder == 'asc'?1:-1;
			if (sortOrder == 'asc'){
				el.data('sort-order', 'desc');
			}else{
				el.data('sort-order', 'asc');
			}

			if (sortNumber){
				var numA = Number(jQuery(a).find(sortSelector).text().replace(/[^0-9]/g, ''));
				var numB = Number(jQuery(b).find(sortSelector).text().replace(/[^0-9]/g, ''));
				return order*((numA < numB) ? -1 : (numA > numB) ? 1 : 0);
			}else{
				return order*(jQuery(a).find(sortSelector).text().localeCompare(jQuery(b).find(sortSelector).text()));
			}

		}).appendTo(jQuery('#values2'));
	});
	
	
	jQuery('.sort3').click(function(e){
		e.preventDefault();
		var el = jQuery(this);
		var sortSelector = el.data('sort');
		var sortNumber = el.hasClass('sort-number')?true:false;
		var sortOrder = el.data('sort-order')=='desc'?'desc':'asc';

		jQuery('.userRow3').sort(function(a,b){
			var order = sortOrder == 'asc'?1:-1;
			if (sortOrder == 'asc'){
				el.data('sort-order', 'desc');
			}else{
				el.data('sort-order', 'asc');
			}

			if (sortNumber){
				var numA = Number(jQuery(a).find(sortSelector).text().replace(/[^0-9]/g, ''));
				var numB = Number(jQuery(b).find(sortSelector).text().replace(/[^0-9]/g, ''));
				return order*((numA < numB) ? -1 : (numA > numB) ? 1 : 0);
			}else{
				return order*(jQuery(a).find(sortSelector).text().localeCompare(jQuery(b).find(sortSelector).text()));
			}

		}).appendTo(jQuery('#values3'));
	});
	
	
	jQuery('.sort4').click(function(e){
		e.preventDefault();
		var el = jQuery(this);
		var sortSelector = el.data('sort');
		var sortNumber = el.hasClass('sort-number')?true:false;
		var sortOrder = el.data('sort-order')=='desc'?'desc':'asc';

		jQuery('.clanrow1').sort(function(a,b){
			var order = sortOrder == 'asc'?1:-1;
			if (sortOrder == 'asc'){
				el.data('sort-order', 'desc');
			}else{
				el.data('sort-order', 'asc');
			}

			if (sortNumber){
				var numA = Number(jQuery(a).find(sortSelector).text().replace(/[^0-9]/g, ''));
				var numB = Number(jQuery(b).find(sortSelector).text().replace(/[^0-9]/g, ''));
				return order*((numA < numB) ? -1 : (numA > numB) ? 1 : 0);
			}else{
				return order*(jQuery(a).find(sortSelector).text().localeCompare(jQuery(b).find(sortSelector).text()));
			}

		}).appendTo(jQuery('#values4'));
	});
	
	jQuery('.sort5').click(function(e){
		e.preventDefault();
		var el = jQuery(this);
		var sortSelector = el.data('sort');
		var sortNumber = el.hasClass('sort-number')?true:false;
		var sortOrder = el.data('sort-order')=='desc'?'desc':'asc';

		jQuery('.clanrow2').sort(function(a,b){
			var order = sortOrder == 'asc'?1:-1;
			if (sortOrder == 'asc'){
				el.data('sort-order', 'desc');
			}else{
				el.data('sort-order', 'asc');
			}

			if (sortNumber){
				var numA = Number(jQuery(a).find(sortSelector).text().replace(/[^0-9]/g, ''));
				var numB = Number(jQuery(b).find(sortSelector).text().replace(/[^0-9]/g, ''));
				return order*((numA < numB) ? -1 : (numA > numB) ? 1 : 0);
			}else{
				return order*(jQuery(a).find(sortSelector).text().localeCompare(jQuery(b).find(sortSelector).text()));
			}

		}).appendTo(jQuery('#values5'));
	});
	
});