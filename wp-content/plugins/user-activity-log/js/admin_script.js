jQuery(window).load(function () {
    jQuery('#subscribe_thickbox').trigger('click');
    jQuery("#TB_closeWindowButton").click(function () {
        jQuery.post(ajaxurl,
                {
                    'action': 'close_tab'
                });
    });
});
jQuery(document).ready(function() {
    jQuery('script').each(function () {
        var src = jQuery(this).attr('src');
        if (typeof src !== typeof undefined && src !== false) {
            if (src.search('bootstrap.js') !== -1 || src.search('bootstrap.min.js') !== -1) {
                var bootstrapButton = jQuery.fn.button.noConflict();
                jQuery.fn.bootstrapBtn = bootstrapButton;
            }
        }
    });
    
    if (jQuery('form.sol-form input[name="emailEnable"]:checked').val() == 0) {
        jQuery('form.sol-form .ui-button.ui-corner-right').addClass('active');
        jQuery('form.sol-form .ui-button.ui-corner-left').removeClass('active');
    } else {
        jQuery('form.sol-form .ui-button.ui-corner-left').addClass('active');
        jQuery('form.sol-form .ui-button.ui-corner-right').removeClass('active');
    }
    
    jQuery('form.sol-form input[name="emailEnable"]').click(function() {
        if (jQuery('form.sol-form input[name="emailEnable"]:checked').val() == 0) {
            jQuery('form.sol-form .ui-button.ui-corner-right').addClass('active');
            jQuery('form.sol-form .ui-button.ui-corner-left').removeClass('active');
        } else {
            jQuery('form.sol-form .ui-button.ui-corner-left').addClass('active');
            jQuery('form.sol-form .ui-button.ui-corner-right').removeClass('active');
        }
    });
    
    //settings tab script
    if (window.localStorage.getItem("lasttab") == null ||
        (window.localStorage.getItem("lasttab") != 'ualGeneralSettings' &&
            window.localStorage.getItem("lasttab") != 'ualUserSettings' &&
            window.localStorage.getItem("lasttab") != 'ualEmailSettings')) {
        jQuery('.ualParentTabs .nav-tab-wrapper a.nav-tab').removeClass('nav-tab-active');
        jQuery('.ualParentTabs .nav-tab-wrapper a.nav-tab.ualUserSettings').addClass('nav-tab-active');
        jQuery('.ualpContentDiv').hide();
        jQuery('#ualUserSettings.ualpContentDiv').show();
        jQuery('#ualUserSettings.ualpContentDiv').css('display','block');
    } else {
        jQuery('.ualParentTabs .nav-tab-wrapper a').removeClass('nav-tab-active');
        jQuery('.' + window.localStorage.getItem("lasttab")).addClass('nav-tab-active');
        jQuery('.ualpContentDiv').hide();
        jQuery('#' + window.localStorage.getItem("lasttab")).css('display','block');
        jQuery('.ualpContentDiv#' + window.localStorage.getItem("lasttab")).show();
    }
    jQuery('.ualParentTabs .nav-tab-wrapper a').click(function(e) {
        e.preventDefault();
        jQuery('.ualpAdminNotice.is-dismissible').hide();
        var this_tab = jQuery(this);
        var data_href = jQuery(this).attr('data-href');
        jQuery('.ualpContentDiv').hide();
        jQuery('#' + data_href).show();
        jQuery('.nav-tab-wrapper a.nav-tab').removeClass('nav-tab-active');
        this_tab.addClass('nav-tab-active');
        if (window.localStorage) {
            window.localStorage.setItem("lasttab", data_href);
        }
    });
    
    
});