/******************** Woocommerce ***********************/
function shopping_cart_dropdown() {
    !jQuery(".widget_shopping_cart .widget_shopping_cart_content .cart_list .empty").length && jQuery(".widget_shopping_cart .widget_shopping_cart_content .cart_list").length > 0 && jQuery(".cart-menu-wrap").addClass("has_products")
}

function shopping_cart_dropdown_show(t) {
    clearTimeout(timeout), !jQuery(".widget_shopping_cart .widget_shopping_cart_content .cart_list .empty").length && jQuery(".widget_shopping_cart .widget_shopping_cart_content .cart_list").length > 0 && "undefined" != typeof t.type && (jQuery(".container .cart-menu-wrap").hasClass("has_products") ? jQuery(".container .cart-notification").is(":visible") ? jQuery(".container .cart-notification").show() : jQuery(".container .cart-notification").fadeIn(400) : setTimeout(function() {
        jQuery(".container .cart-notification").fadeIn(400)
    }, 400), timeout = setTimeout(hideCart, 2700))
}

function hideCart() {
    jQuery(".container .cart-notification").stop(!0, !0).fadeOut()
}

jQuery.noConflict(), jQuery(document).ready(function() {
    "use strict";
    var t = jQuery(".comment-body .comment-reply-link");
    t.addClass("button-small")
}),

/******************** Menu ***********************/
jQuery(document).ready(function() {
    "use strict";
    var t = jQuery("ul.sub-menu");
    t.parent().addClass("dropdown"), t.addClass("dropdown-menu");
    var e = jQuery("dropdown-menu.children");
    e.parent().addClass("dropdown"), e.addClass("dropdown-menu");
    var r = jQuery("dropdown-menu.children");
    r.parent().addClass("dropdown");
    var o = jQuery(".menu ul");
    o.parent().addClass("dropdown");
    var i = jQuery(".dropdown .dropdown-menu");
    i.removeClass("children");
    var n = jQuery(".dropdown > a br");
    n.before('<b class="caret"></b>'), r.hover(function() {
        jQuery(this).parent().addClass("active")
    }, function() {
        jQuery(this).parent().removeClass("active")
    }), t.hover(function() {
        jQuery(this).parent().addClass("active")
    }, function() {
        jQuery(this).parent().removeClass("active")
    });
    var a = jQuery(".menu ul");
    a.addClass("nav"), e.removeClass("nav")
})

jQuery.noConflict(), jQuery(function() {
    "use strict";
    jQuery("[data-toggle=tooltip]").tooltip()
}),

jQuery.noConflict();
var wgtitle = jQuery(".widget .title-wrapper");
wgtitle.each(function() {
    "use strict";
    var t = jQuery('.widget ul li .bbp-forum-title[href*="topic"]'),
        e = jQuery('.widget ul li .bbp-forum-title:not([href*="topic"])');
    t.prepend('<i class="fa fa-comment"></i>'), e.prepend('<i class="fa fa-comments"></i>');
    var r = jQuery('.footer_widget ul li .bbp-forum-title[href*="topic"]'),
        o = jQuery('.footer_widget ul li .bbp-forum-title:not([href*="topic"])');
    r.prepend('<i class="fa fa-comment"></i>'), o.prepend('<i class="fa fa-comments"></i>')
});


var bbip = jQuery(".bbp-author-ip");
bbip.remove();

/******************** Web ticker ***********************/
var ticker = jQuery("#webticker");
ticker.webTicker();

/******************** Tabs ***********************/
 jQuery(document).ready(function() {
    "use strict";
    var t = jQuery(".tab-inner");
    if (0 !== t.length) {
        var e = jQuery('.tab-inner a[href="#tab-1"]').parent().index();
        t.tabs().tabs("option", "active", e)
    }
})

/******************** Contact form ***********************/
jQuery(document).ready(function(t) {
    "use strict";
    var e = t("#contactForm");
    e.validate();
});

/******************** Search form ***********************/
var src = jQuery("#sform i.fa");
var src_input = jQuery("#sform input[type=search]");
var src_all = jQuery("#sform input[type=search], #sform i.fa");
src_all.click(function() {
src_input.css('width', '130px');
src_input.css('color', '#000');
src_input.css('padding-left', '32px');
src_input.css('border', '0px');
src_input.css('cursor', 'auto');
src_input.css('background-color', '#202126');
});

jQuery('#sform').bind( "clickoutside", function(event){
src_input.css('width', '3px');
src_input.css('padding', '9px 18px 8px 18px');
src_input.css('border', '0px solid transparent');
src_input.css('cursor', 'pointer');
src_input.css('background-color', '');
src_input.val('');
});


/******************** Isotope blog ***********************/
var blog = jQuery(".page-template-tmp-blog-isotope");
if(blog.length != 0){
// Modified Isotope methods for gutters in masonry

jQuery.Isotope.prototype._getMasonryGutterColumns = function() {
	var gutter = this.options.masonry && this.options.masonry.gutterWidth || 0;
	containerWidth = this.element.width();

	this.masonry.columnWidth = this.options.masonry && this.options.masonry.columnWidth ||
	// Or use the size of the first item
	this.jQueryfilteredAtoms.outerWidth(true) ||
	// If there's no items, use size of container
	containerWidth;

	this.masonry.columnWidth += gutter;

	this.masonry.cols = Math.floor((containerWidth + gutter) / this.masonry.columnWidth);
	this.masonry.cols = Math.max(this.masonry.cols, 1);
};

jQuery.Isotope.prototype._masonryReset = function() {
	// Layout-specific props
	this.masonry = {};
	// FIXME shouldn't have to call this again
	this._getMasonryGutterColumns();
	var i = this.masonry.cols;
	this.masonry.colYs = [];
	while (i--) {
		this.masonry.colYs.push(0);
	}
};

jQuery.Isotope.prototype._masonryResizeChanged = function() {
	var prevSegments = this.masonry.cols;
	// Update cols/rows
	this._getMasonryGutterColumns();
	// Return if updated cols/rows is not equal to previous
	return (this.masonry.cols !== prevSegments);
};
// modified Isotope methods for gutters in masonry
if(jQuery.isFunction(jQuery.fn.imagesLoaded)){

	//isotope
	var containerblog = jQuery('.isoblog');

	containerblog.imagesLoaded( function(){
	// initialize Isotope
	containerblog.isotope({
		// options...
		layoutMode : 'masonry',
		resizable: false, // disable normal resizing
		// set columnWidth to a percentage of container width
		masonry: {
			columnWidth: (containerblog.width() - 40) / 3,
			gutterWidth: 20
		}
	});
	});

	// start new block
	jQuery('.cat a').click(function(){
		var selector = jQuery(this).attr('href');
		containerblog.isotope({ filter: selector });
		return false;
	});
	// end new block

	// update columnWidth on window resize
	jQuery(window).smartresize(function(){
		//console.log(container.width());
		// set the widths on resize
		setWidthsBlog();
		containerblog.isotope({
			// update columnWidth to a percentage of container width
			masonry: {
				columnWidth: getUnitWidthBlog(),
				gutterWidth: 20
			}
		});
	}).resize();


/*  Isotope utility GetUnitWidth
    ========================================================================== */
function getUnitWidthBlog() {
	var width;
	if (containerblog.width() <= 320) {
		//console.log("320");
		width = Math.floor((containerblog.width() - 20)  / 1);
	} else if (containerblog.width() >= 321 && containerblog.width() <= 480) {
		//console.log("321 - 480");
		width = Math.floor((containerblog.width() - 20)  / 1);
	} else if (containerblog.width() >= 481 && containerblog.width() <= 662) {
		//console.log("481 - 768");
		width = Math.floor((containerblog.width() - 20) / 1);
	} else if (containerblog.width() >= 663 && containerblog.width() <= 768) {
		//console.log("663 - 768");
		width = Math.floor((containerblog.width() - 20)  / 2);
	} else if (containerblog.width() >= 769 && containerblog.width() <= 979) {
		//console.log("769 - 979");
		width = Math.floor((containerblog.width() - 40)  / 3);
	} else if (containerblog.width() >= 980 && containerblog.width() <= 1200) {
		//console.log("980 - 1200");
		width = Math.floor((containerblog.width() - 40)  / 3);
	} else if (containerblog.width() >= 1201 && containerblog.width() <= 1600) {
		//console.log("1201 - 1600");
		width = Math.floor((containerblog.width() - 20)  / 5);
	} else if (containerblog.width() >= 1601 && containerblog.width() <= 1824) {
		//console.log("1601 - 1824");
		width = Math.floor((containerblog.width() - 20)  / 8);
	} else if (containerblog.width() >= 1825) {
		//console.log("1825");
		width = Math.floor((containerblog.width() - 20)  / 10);
	}
	return width;
}


/*  Isotope utility SetWidths
    ========================================================================== */
function setWidthsBlog() {
	var unitWidth = getUnitWidthBlog() - 0;
	containerblog.children(":not(.width2)").css({
		width: unitWidth
	});

	if (containerblog.width() >= 321 && containerblog.width() <= 480) {
		 //console.log("eccoci 321");
		containerblog.children(".width2").css({
			width: unitWidth * 1
		});
		containerblog.children(".width4").css({
			width: unitWidth * 2
		});
		containerblog.children(".width6").css({
			width: unitWidth * 3
		});
	}
	if (containerblog.width() >= 481) {
		 //console.log("480");
		containerblog.children(".width6").css({
			width: unitWidth * 4
		});
		containerblog.children(".width4").css({
			width: unitWidth * 3
		});
		containerblog.children(".width2").css({
			width: unitWidth * 2
		});
	} else {
		containerblog.children(".width2").css({
			width: unitWidth
		});
	}
}

}}