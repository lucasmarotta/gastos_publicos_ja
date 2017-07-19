jQuery(document).ready(function($) 
{
    $.get("http://ipinfo.io/region", function(response) 
    {
        if(response.length)
            $(".header-form .form-control").attr("placeholder","Um estado (ex: "+response+")");
    });

    $(function() {
        $('body').on('click', '.page-scroll a', function(event) {
            var $anchor = $(this);
            $('html, body').stop().animate({
                scrollTop: $($anchor.attr('href')).offset().top
            }, 1500, 'easeInOutExpo');
            event.preventDefault();
        });
    });

    // Floating label headings for the contact form
    $(function() {
        $("body").on("input propertychange", ".floating-label-form-group", function(e) {
            $(this).toggleClass("floating-label-form-group-with-value", !! $(e.target).val());
        }).on("focus", ".floating-label-form-group", function() {
            $(this).addClass("floating-label-form-group-with-focus");
        }).on("blur", ".floating-label-form-group", function() {
            $(this).removeClass("floating-label-form-group-with-focus");
        });
    });

    // Highlight the top nav as scrolling occurs
    $('body').scrollspy({
        target: '.navbar-fixed-top'
    })

    // Closes the Responsive Menu on Menu Item Click
    $('.navbar-collapse ul li a').click(function() {
        $('.navbar-toggle:visible').click();
    });

    /*if($(".brasil-map").length > 0)
    {
        $.getJSON( "/obras/get_random_obra", function(data) 
        {
            var brasilMap = $(".brasil-map");
            var estado =  brasilMap.find("#uf-"+data.sigla);
            var tweetUrl = "https://api.twitter.com/1.1/search/tweets.json?";
            var tweetSearchUrl = tweetUrl+"q="+data.nome+"&count=1";
            console.log(tweetSearchUrl);
            estado.addClass("highlight-uf");
        }).fail(function() 
        {
            console.log("Falhou");
        });
    }*/

});
