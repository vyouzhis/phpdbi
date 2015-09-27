/*------------------------------------------------------------------
Project:    Oneline
Author:     Yevgeny S.
URL:        https://twitter.com/YevSim
Version:    1.0
Created:        28/04/2014
Last change:    22/07/2014
-------------------------------------------------------------------*/

/* ===== Lost password form ===== */

$('.pwd-lost > .pwd-lost-q > a').on('click', function() {
    $(".pwd-lost > .pwd-lost-q").toggleClass("show hidden");
    $(".pwd-lost > .pwd-lost-f").toggleClass("hidden show animated fadeIn");
    return false;
});

/* ===== Sign Up popovers ===== */

$(function(){
    $('#name').popover();
});

$(function(){
    $('#username').popover();
});

$(function(){
    $('#email').popover();
});

$(function(){
    $('#password').popover();
});

$(function(){
    $('#repeat-password').popover();
});