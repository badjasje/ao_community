function setCookie(name,value,days) {
    var expires = "";
    if (days) {
        var date = new Date();
        date.setTime(date.getTime() + (days*24*60*60*1000));
        expires = "; expires=" + date.toUTCString();
    }
    document.cookie = name + "=" + (value || "")  + expires + "; path=/";
}
function getCookie(name) {
    var nameEQ = name + "=";
    var ca = document.cookie.split(';');
    for(var i=0;i < ca.length;i++) {
        var c = ca[i];
        while (c.charAt(0)==' ') c = c.substring(1,c.length);
        if (c.indexOf(nameEQ) == 0) return c.substring(nameEQ.length,c.length);
    }
    return null;
}

function updateHeaderData() {
    var $ = jQuery;
    $.getJSON(site_url+'/ajax/header', function(data) { // ajax without loader
        for(var i in data) {
            if($('.'+i+'header').length) $('.'+i+'header').html(data[i]);
        }
        $('.globalsBadge').text(data.globals).toggle((data.globals>0));
        $('.localsBadge').text(data.locals).toggle((data.locals>0));
        $('.inboxBadge').text(data.messages).toggle((data.messages>0));
    });
}

var countdown_int, countdown_objs;
function start_countdowns() {
    countdown_objs = jQuery('[data-countdown]');
    if(!!countdown_int) clearInterval(countdown_int);
    countdown_int = setInterval(update_countdowns, 1000);
    update_countdowns();
}
function update_countdowns() {
    countdown_objs.each(function() {
        var $this = jQuery(this), diff = (typeof $this.data('diff') != 'undefined' ? $this.data('diff') : parseInt($this.data('countdown'))*1000);
        if(diff < 0) { $this.html('<em>none</em>'); return; }
        var days = Math.floor( diff / (1000*60*60*24) ),
        hours = Math.floor( diff / (1000*60*60) ),
        mins = Math.floor( diff / (1000*60) ),
        secs = Math.floor( diff / 1000 ),
        dd = days,
        hh = hours - days * 24,
        mm = mins - hours * 60,
        ss = secs - mins * 60;
        var t = ''+(days > 0 ? days+' '+(days==1?'day':'days')+' and ' : '');
        t+=('00'+hh).slice(-2) + ':' + ('00'+mm).slice(-2) + ':' + ('00'+ss).slice(-2);
        $this.text(t).data('diff', (diff-1000));
    });
}

var requests={};
function singleAjax(url,post,cb) {
    var $ = jQuery;
    if(!!requests[url]) requests[url].abort();
    var serializedData = (post instanceof jQuery ? post.serialize() : post);
    $('.pageLoader, #page-cover').show();
    requests[url] = $.ajax({url:url,type:'post',data:serializedData}).done(function(response) {
        $('.pageLoader, #page-cover').fadeOut("fast");
        var response = $.parseJSON(response);
        updateHeaderData(); // Something happened, so it probably costed money or something
        if(!!response.status) $.notify({message:response.status},{type:'info',delay:5000,allow_dismiss:true,newest_on_top:true});
        if(!!cb) cb.call(this, response);
    });
}

jQuery(function($) {

    // Help in icon menu
    $('.menuRow').each(function(i1) {
        var t = $('.menuText>a',this).html();
        if(!!t) {
            $('.buttonItem>a', this).wrapInner('<div data-toggle="tooltip" data-html="true" data-placement="right" title="'+t.replace(/"/g, "'")+'"></div>');
        }
    });

    if(getCookie('menuOpen') === null) setCookie('menuOpen', 0, 256);
    else { setCookie('menuOpen', getCookie('menuOpen'), 256); } // Remember forever

    $("#nextbt, #nextbt2").on('click', function(e) {
        e.preventDefault();
        if($('body').hasClass('menuOpen')) {
            $('body').removeClass('menuOpen');
            $('.menuText').hide(500);
            $(".hamburger").removeClass("is-active");
            $('[data-toggle=tooltip]').tooltip('enable');
            setCookie('menuOpen', 0, 256);
        } else {
            $('body').addClass('menuOpen');
            $('.menuText').show(750);
            $(".hamburger").addClass("is-active");
            $('[data-toggle=tooltip]').tooltip('disable');
            if($(window).width() >= 1400) setCookie('menuOpen', 1, 256);
        }
    });

    $('[data-toggle="tooltip"]').tooltip();

    $(document).on('keyup paste change', '.buy_spyplane, .buy_spy', function() {
        if(''+$(this).val() === '007') {
            $.notify({message:'The name is Bond, James Bond'},{type:'info', allow_dismiss:true, newest_on_top:true, delay:5000});
        }
    });

    var i = setInterval(function() { updateHeaderData(); }, 10000);
    updateHeaderData();

    start_countdowns();

    if($('.pageTitle').hasClass('.deadback')) {
        $(".splashmessage").html('You died');
        $("#splashback").addClass("failsplash");
        $("#splashback,.splashmessage").show();
        $("#splashback,.splashmessage").delay(1500).fadeOut("slow");
    }

    $(document).on('click','.receiveFunds',function(){
        singleAjax(site_url+'/ajax/devfunds')
    });

    $("#pickStartingBonus").on('submit', function(e) {
        e.preventDefault();
        $('.bonusSubmit').hide();
        singleAjax(site_url+'/ajax/startingbonus', $(this), function(response) {
            if(response.success) $('.startingBonusPicker').fadeOut('fast');
            else $('.bonusSubmit').show();
        });
    });

    $('.retrieveBonus').on('click', function(e) {
        e.preventDefault();
        if(!confirm('Are you sure you want to receive your bonus?')) return;
        $(this).attr('disabled', true);
        singleAjax(site_url+'/ajax/clanbonus', {id:$(this).data('id')}, function(response) {
            if(response.success) $(this).parent().hide(300);
            $(this).attr('disabled', false);
        }.bind(this));
    });

    $('#editClanMessage').on('click', function() {
        $('.message-editor').fadeIn(500);
        $('#savedmsg').hide();
    });
    $('#dismissEditClanMessage').on('click', function() {
        $('.message-editor').hide();
        $('#savedmsg').fadeIn(500);
    });
    $("#edit_clan_message").on('submit', function(e) {
        e.preventDefault();
        var content = tinymce.activeEditor.getContent();
        singleAjax(site_url+'/ajax/clanmessage', {'new_message':content}, function(response) {
            if(response.success) {
                $('#savedmsg').html(response.clanmessage);
                $('.message-editor').hide();
                $('#savedmsg').fadeIn(500);
            }
        });
    });

    $('.removeProtection').on('click', function(e) {
        e.preventDefault();
        singleAjax(site_url+'/ajax/removenp', null, function(response) {
            if(response.success) $('.npMessage').removeClass('py-0').text('Status: online');
        });
    });
});

