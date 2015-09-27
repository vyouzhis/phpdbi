/*------------------------------------------------------------------
Project:    Oneline
Author:     Yevgeny S.
URL:        https://twitter.com/YevSim
Version:    1.0
Created:        28/04/2014
Last change:    22/07/2014
-------------------------------------------------------------------*/

/* ===== Tooltips ===== */

$('#tooltip').tooltip();

/* ===== Feedback ===== */

$('.feedback-author').hover (function() {
    var quote = $(this).data('quote');
    $('.feedback-author').removeClass("active");
    $(this).addClass("active");
    $('.feedback-quote').removeClass("show animated fadeIn");
    $('.feedback-quote').addClass("hidden");
    $('.feedback-quote' + quote).toggleClass("hidden show");
    $('.feedback-quote' + quote).addClass("animated fadeIn");
    return false;
});


$('#myphone').on('keyup', function(event) {

	var val = this.value.match(/\d/gi) || [];
	var pla = this.getAttribute('placeholder') || '';
	var res = '';
	
	for ( var i = 0, k = 0, l = val.length; i < l; i++, k++) {
		(function() {
			if (/\D/.test(pla.substr(k, 1))) {
				res += pla.substr(k, 1);
				k++
				arguments.callee();
			}
		})();
		res += val[i];
	}
	this.value = res.substr(0, 13);
});


$('#submit-contact-form').click( function() {
    var error = false;

    var name = $('#name').val();
    if(name == "" || name == " ") {
        $('#name').css('background-color', '#f2dede');
        $('#name').parent().addClass('has-error');
        error = true;
    } else {
        $('#name').css('background-color', '#fff');
        $('#name').parent().removeClass('has-error');
    }

    var checkEmail = /^([\w-\.]+@([\w-]+\.)+[\w-]{2,4})?$/;
    var email = $('#email').val();
    if (email == "" || email == " ") {
        $('#email').css('background-color', '#f2dede');
        $('#email').parent().addClass('has-error');
        error = true;
    } else if (!checkEmail.test(email)) {
        $('#email').css('background-color', '#f2dede');
        $('#email').parent().addClass('has-error');
        error = true;
    } else {
        $('#email').css('background-color', '#fff');
        $('#email').parent().removeClass('has-error');
    }

    var message = $('#message').val();
    if(message == "" || message == " ") {
        $('#message').css('background-color', '#f2dede');
        $('#message').parent().addClass('has-error');
        error = true;
    } else {
        $('#message').css('background-color', '#fff');
        $('#message').parent().removeClass('has-error');
    }
    
    var myphone = $('#myphone').val();
    if(myphone == "" || myphone == " " || myphone.length !=13) {
        $('#myphone').css('background-color', '#f2dede');
        $('#myphone').parent().addClass('has-error');
        error = true;
    } else {
        $('#myphone').css('background-color', '#fff');
        $('#myphone').parent().removeClass('has-error');
    }
    
    var company = $('#company').val();
    if(company == "" || company == " ") {
        $('#company').css('background-color', '#f2dede');
        $('#company').parent().addClass('has-error');
        error = true;
    } else {
        $('#company').css('background-color', '#fff');
        $('#company').parent().removeClass('has-error');
    }
    /*
    var qq = $('#qq').val();
    if(qq == "" || qq == " " || parseInt(qq)!=qq) {
        $('#qq').css('background-color', '#f2dede');
        $('#qq').parent().addClass('has-error');
        error = true;
    } else {
        $('#qq').css('background-color', '#fff');
        $('#qq').parent().removeClass('has-error');
    }
  */
      
    if (error == false) {
        $.ajax({
            type: "POST",
            url: "/ajax",
            dataType: 'json',
            data: {"name":name,"email":email,"message":message,"myphone":myphone,"company":company},
            timeout: 6000,
            error: function(request,error) {
               /* if (error == "timeout") {
                    $('#contact-error').slideDown('slow');
                    $('#contact-error span').text('Timed out when contacting server.');
                    setTimeout(function() {
                        $('#contact-error').slideUp('slow');
                    }, 10000);
                }
                else {
                    $('#contact-error').slideDown('slow');
                    $('#contact-error span').text('Something is not working. Please try again.');
                    setTimeout(function() {
                        $('#contact-error').slideUp('slow');
                    }, 10000);
                }*/
               
            },
            success: function() {
            	console.log("return success");
                $('#contact-success').slideDown('slow');
                $('#contact-success span').text('已保存，多谢您的意见.');
                setTimeout(function() {
                    $('#contact-success').slideUp('slow');
                }, 10000);
                $('#name').val('');
                $('#email').val('');
                $('#myphone').val('');
                $('#company').val('');
                $('#message').val('');
            },
            complete: function(request, textStatus) { //for additional info
				  		var option = request.responseText;	
				
			  	  }
        });
    } else {
    	// console.log("error---");
        $('#contact-error').hide();
        $('#contact-success').hide();
    }
});