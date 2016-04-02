$body = $("body");
$(document).on({
    ajaxStart: function() { console.warn('holamundo');$body.addClass("loading");    },
    ajaxStop: function() { $body.removeClass("loading"); }
});

$(document).ready(function() {
    $('a.procesar').click(function (event){
        console.warn('asdasd');
         event.preventDefault();
         $.ajax({
            url: $(this).attr('href'),
            success: function(response) {
                console.log(response);
            }
        });
        //return false; //for good measure
    });
});
