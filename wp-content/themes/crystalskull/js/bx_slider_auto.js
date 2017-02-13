jQuery(window).load(function() {
    "use strict";
    var t = jQuery(".bxslider");
    if (0 !== t.length) {
        t.bxSlider({
            captions: !0,
            auto: 1,
     
            nextSelector: ".bx-next-out",
            moveSlides: 1,
            breaks: [{
                screen: 0,
                slides: 2,
                pager: !1
            }, {
                screen: 460,
                slides: 2
            }, {
                screen: 768,
                slides: 2
            }]
        });
        var e = jQuery(".slider-wrapper");
        e.css("display", "block").fadeIn("slow"), imagesLoaded(t, function() {
            
            t.reloadSlider();
            
            var e = t.getCurrentSlideElement().attr("data-id"),
                r = jQuery('.slider_text_src ul li[data-id="' + e + '"]'),
                o = jQuery(".slider_text"),
                i = jQuery(".next_slide_text_inner"),
                n = jQuery(document.body);
                
            if (n.on("click", ".bx-pager-item a", function() {
                var e = parseInt(jQuery(this).attr("data-slide-index")) + 1,
                    r = jQuery('.slider_text_src ul li[data-id="' + e + '"]');
                if (o.empty(), o.append(r.html()), t.getCurrentSlideElement().attr("data-id") < t.getSlideCount()) var n = e + 1,
                a = jQuery('.slider_text_src ul li[data-id="' + n + '"]');
                else if (t.getCurrentSlideElement().attr("data-id") == t.getSlideCount()) var n = 1,
                a = jQuery('.slider_text_src ul li[data-id="' + n + '"]');
                i.empty(), i.append(a.clone().children(".slider_com_wrap").remove().end().text());
                t.stopAuto();
            	t.startAuto();
            }), 
            
            t.getCurrentSlideElement().attr("data-id") < t.getSlideCount()) var a = parseInt(e) + 1,
            d = jQuery('.slider_text_src ul li[data-id="' + a + '"]');
            
            o.empty(), o.append(r.html()), i.empty(), i.append(d.clone().children(".slider_com_wrap").remove().end().text()); {
                var c = jQuery(".bx-next");
                jQuery(".bx-caption")
            }
            
            c.click(function() {
                var e = t.getCurrentSlideElement().attr("data-id"),
                    r = jQuery('.slider_text_src ul li[data-id="' + e + '"]'),
                    o = jQuery(".slider_text"),
                    i = jQuery(".next_slide_text_inner");
                if (t.getCurrentSlideElement().attr("data-id") < t.getSlideCount()) var n = parseInt(e) + 1,
                a = jQuery('.slider_text_src ul li[data-id="' + n + '"]');
                else if (t.getCurrentSlideElement().attr("data-id") == t.getSlideCount()) var n = 1,
                a = jQuery('.slider_text_src li[data-id="' + n + '"]');
                i.empty(), i.append(a.clone().children(".slider_com_wrap").remove().end().text()), o.empty(), o.append(r.html());
                t.stopAuto();
            	t.startAuto();
            })
            
            function callFunc(){
  				 c.trigger( "click" );
			}
			
            var interval = null;
            jQuery(function(){
			  interval = setInterval(callFunc, 2500);
			});
        })
    }
})