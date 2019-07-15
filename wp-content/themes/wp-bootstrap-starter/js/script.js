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
    $.ajax({url:site_url+'/ajax/header',type:'post',data:{nonce:$('.nonce').val()}}).done(function(response) { // ajax without loader
        var data = $.parseJSON(response);
        if(data.nonce) $('.nonce').val(data.nonce);
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
        var data = $.parseJSON(response);
        if(data.nonce) $('.nonce').val(data.nonce);
        updateHeaderData(); // Something happened, so it probably costed money or something
        if(!!data.status) $.notify({message:data.status},{type:'info',delay:5000,allow_dismiss:true,newest_on_top:true});
        if(!!cb) cb.call(this, data);
    });
}

jQuery(function($) {

    var d=new Date();
    $('#footerTime').text(d.toLocaleString('nl-NL'));
    $('#footerResolution').text(window.innerWidth +'x'+ window.innerHeight);

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

    $("#receiveFunds").on('submit', function(e) {
        e.preventDefault();
        singleAjax(site_url+'/ajax/devfunds', $(this));
    });

    $("#pickStartingBonus").on('submit', function(e) {
        e.preventDefault();
        $('.bonusSubmit').hide();
        singleAjax(site_url+'/ajax/startingbonus', $(this), function(response) {
            if(response.success) $('.startingBonusPicker').fadeOut('fast');
            else $('.bonusSubmit').show();
        });
    });

    $(".retrieveBonusForm").on('submit', function(e) {
        e.preventDefault();
        if(!confirm('Are you sure you want to receive your bonus?')) return;
        $(this).attr('disabled', true);
        singleAjax(site_url+'/ajax/clanbonus', $(this), function(response) {
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
        $('[name="new_message"]').val(tinymce.activeEditor.getContent());
        singleAjax(site_url+'/ajax/clanmessage', $(this), function(response) {
            if(response.success) {
                $('#savedmsg').html(response.clanmessage);
                $('.message-editor').hide();
                $('#savedmsg').fadeIn(500);
            }
        });
    });

    $('#removeProtection').on('submit', function(e) {
        e.preventDefault();
        if(!confirm('Are you sure you want to remove protection?')) return;
        singleAjax(site_url+'/ajax/removenp', $(this), function(response) {
            if(response.success) $('.npMessage').removeClass('py-0').text('Status: online');
        });
    });

    $('#research').on('submit', function(e) {
        e.preventDefault();
        singleAjax(site_url+'/ajax/research',  $(this), function(response) {
            if(response.success) {
                $('#researchsubmit').val('Queue research');
                $('.researchlabel').html('Queue select');
                $(response.hidebutton).hide();
                if(response.endtime!='queued') {
					$(`<div class="blockHeader fw-row">
						<i class="fa fa-circle-notch fa-spin"></i> Time left:
						<div class="timeLeft" id="countdown_time" data-countdown="`+response.endtime+`"></div>
					</div>`).insertAfter('#research_'+response.started);
					start_countdowns();
					$('#research_'+response.started+' .unitRow').addClass('loader');
				} else {
					$('<div class="blockHeader fw-row"><i class="fa fa-clock"></i> Research queued</div>').insertAfter('#research_'+response.started);
					$('#researchsubmit').remove();
					$('.researchselector').html('<label class="mainSubmit disabled">No Selection Possible</label>');
				}
				$(this).trigger('reset');
            }
        });
    });
});

// Google Tag Manager
(function(w,d,s,l,i){w[l]=w[l]||[];w[l].push({'gtm.start':
    new Date().getTime(),event:'gtm.js'});var f=d.getElementsByTagName(s)[0],
    j=d.createElement(s),dl=l!='dataLayer'?'&l='+l:'';j.async=true;j.src=
    'https://www.googletagmanager.com/gtm.js?id='+i+dl;f.parentNode.insertBefore(j,f);
})(window,document,'script','dataLayer','GTM-TXGKNL3');

// Facebook Pixel Code
!function(f,b,e,v,n,t,s) {
    if(f.fbq)return;n=f.fbq=function(){n.callMethod?
    n.callMethod.apply(n,arguments):n.queue.push(arguments)};
    if(!f._fbq)f._fbq=n;n.push=n;n.loaded=!0;n.version='2.0';
    n.queue=[];t=b.createElement(e);t.async=!0;
    t.src=v;s=b.getElementsByTagName(e)[0];
    s.parentNode.insertBefore(t,s)
}(window, document,'script','https://connect.facebook.net/en_US/fbevents.js');
fbq('init', '1603414756640075');
fbq('track', 'PageView');

// Global site tag (gtag.js) - Google Analytics
window.dataLayer = window.dataLayer || [];
function gtag(){dataLayer.push(arguments);}
gtag('js', new Date());
gtag('config', 'UA-40825301-45');

// Firebase browser notifications
if(typeof firebase != 'undefined') {
    var config = {
        apiKey: "AIzaSyBBkuM6n38eUe5yqw50KjpM7HHAR2RGdOQ",
        authDomain: "assaultonline-21594.firebaseapp.com",
        databaseURL: "https://assaultonline-21594.firebaseio.com",
        projectId: "assaultonline-21594",
        storageBucket: "assaultonline-21594.appspot.com",
        messagingSenderId: "776419312119"
    };
    firebase.initializeApp(config);
    const messaging = firebase.messaging();
    messaging.usePublicVapidKey("BPywnXWNiczMF1nEPWQ6hZOudN81OwAbvcBWQBaDx5FVFUG7Rdl0J9sd1GjqA7KpzDKYtOoWnlx-vY39C9uh3h0");
    messaging.getToken().then(function(currentToken) {
        if (currentToken) {
            jQuery.post("/addtoken.php",{usertoken : currentToken});
        } else {
            // Show permission request.
            //console.log('No Instance ID token available. Request permission to generate one.');
            // Show permission UI.
            updateUIForPushPermissionRequired();
            setTokenSentToServer(false);
        }
    }).catch(function(err) {
        //console.log('An error occurred while retrieving token. ', err);
        if(typeof showToken == 'function') showToken('Error retrieving Instance ID token. ', err);
        if(typeof setTokenSentToServer == 'function') setTokenSentToServer(false);
    });
}
