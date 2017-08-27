/*
	Apesar de $ parecer com Jquery, não é JQUERY, mas sim parte da biblioteca JRAW
	criada para este projeto
*/
$(function(){

    var footerYear = $(".footer-year");
    footerYear.html(footerYear.html()+" "+(new Date()).getFullYear());

    $.ajax({ url: "https://ipinfo.io/region",
        success: function(response) {
            if(response.data.length) {
                $(".state-search .input-group-field").attr("placeholder", "Um estado (ex: "+response.data+")");
            }
        }
    });

    $.ajax({url:"https://transparencia.es.gov.br/Api/Despesa/ListarOrgaos?ano=2017&numeroPagina=1",
        type:'xml',
        success:function(response){
            console.log(response);
        },
        fail:function(response) {
            console.log(response);
        }
    });

});

/*
    if($(".brasil-map").length > 0)
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
    }

});*/