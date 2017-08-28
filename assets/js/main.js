/*
	Apesar de $ parecer com Jquery, não é JQUERY, mas sim parte da biblioteca JRAW
	criada para este projeto
*/
$(function(){

    var footerYear = $(".footer-year");
    $.getJSON({ url: "https://ipinfo.io/json",
        success: function(response) {
            $(".state-search .input-group-field").attr("placeholder", "Um estado (ex: "+response.region+")");
        }
    });

    var ip = null;
    $.ajax({ url: "https://ipinfo.io/ip",
        success: function(response) {
            if(response.data.length) {
                ip = response.data;
                $.getJSON({ url: "http://api.worldweatheronline.com/premium/v1/tz.ashx",
                    data: { 
                        key: 'adc6fbb0fcdd4dcb81f201701172708',
                        q: ip,
                        format: 'json',
                    },
                    success: function(response) {
                        if(response) {
                            var date = dataAtualFormatada(response.data.time_zone[0].localtime);
                            footerYear.html(footerYear.html()+" - "+date);
                        }
                    }
                });
            }
        }
    });

    $.getJSON({ url:"http://localhost:8000/twitter",
        data: {
            term:"Defensoria Publica Espirito Santo"
        },
        success: function(response) {
            console.log(response);
        },
        fail: function () {
            console.log("falhou");
        }
    });

    $.getJSON({ url:"http://localhost:8000/gastos/es",
        data: {
            pg:1,
            ano:2017
        },
        success: function(gastos) {
            var gastosModel = $(".gastos-model");
            gastosModel.bindValue("url","http://localhost");
        },
        fail: function () {
            console.log("falhou");
        }
    });

});


function dataAtualFormatada(data)
{
     var data = new Date(data);
     var dia = data.getDate();
     if (dia.toString().length == 1)
       dia = "0"+dia;
     var mes = data.getMonth()+1;
     if (mes.toString().length == 1)
       mes = "0"+mes;
     var ano = data.getFullYear();
     var hora = data.getHours();
     if(hora.toString().lenght == 1)
     	hora = "0"+hora;
     var min = data.getMinutes();
     if(min.toString().lenght == 1)
     	min = "0"+min;

     return dia+"/"+mes+"/"+ano+" "+hora+":"+min;
 }

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