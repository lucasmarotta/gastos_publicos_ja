/*
	Apesar de $ parecer com Jquery, não é JQUERY, mas sim parte da biblioteca JRAW
	criada para este projeto
*/
$(function(){

    var footerYear = $(".footer-year"),
        gastosModel = $(".gastos-model"),
        tweet = $(".tweet"),
        brasilMap = $(".brasil-map"),
        socialSection = $(".social-section");

    setIpInfo();
    loadGastos(loadBrasilMap);

    function loadGastos(callback)
    {
        $.getJSON({ url:"http://localhost:8000/gastos/es",
            data: {
                pg:1,
                ano:2017
            },
            success: function(response) {
                response.gastos.forEach(function(item, index){
                    var newGastos = gastosModel.clone();
                    newGastos.bindValue("url","detalhes.html");
                    newGastos.bindValue("gastos-uf","br-uf-es");
                    newGastos.bindValue("gastos-orgao", item.orgao);
                    newGastos.bindValue("gastos-estado-nome", "Espírio Santo");
                    newGastos.bindValue("gastos-empenhado", "Empenhado R$"+item.empenhado);
                    newGastos.bindValue("gastos-pago", "Pago R$"+item.pago);
                    newGastos.bindValue("gastos-liquido", "Líquido R$"+item.liquido);
                    $(".gastos").append(newGastos.show().get(0));
                });
                callback();
            },
            fail: function (response) {
                console.warn("Não foi possível obter gastos");
            }
        });
    }

    function loadBrasilMap()
    {
        var randomOrgaoRequest = {
            url:"http://localhost:8000/gastos/random",
            data: {ano:"2017"},
            cache: false,
            success: function(response) {
                if(response.data.length > 0) {
                    loadTweet(response.data);
                } else {
                    console.warn("Nenhum orgao randômico retornado");
                }
            },
            fail: function() {
                console.warn("Não foi possível obter orgao randômico");
            }
        };

        setInterval(function(){
            $.ajax(randomOrgaoRequest);
        },15*1000);
    }

    function loadTweet(term)
    {
        $.getJSON({ url:"http://localhost:8000/twitter",
            data: {term:term},
            success: function(response) {
                console.log(response);
                if(response.user) {
                    socialSection.show();
                    var estado =  brasilMap.find("#uf-es");
                    var dim = estado.getDimension();
                    estado.addClass("highlight-uf");
                    tweet.bindValue("tweet-user",response.user);
                    tweet.bindValue("tweet-text",response.text);
                    setTimeout(function(){
                        tweet.css({visibility:'visible'});
                        var tweetDim = tweet.getDimension(),
                            position = {
                                top: dim.top - tweetDim.height + dim.height/2 - 20,
                                left: dim.left - tweetDim.width/2 + dim.width/2
                            };
                        tweet.setPosition(position);
                    },50);
                } else {
                    console.log("Não foi possível obter o tweet com o termo: "+term);
                }
            },
            fail: function () {
                console.log("Não foi possível obter o tweet com o termo: "+term);
            }
        });      
    }

    function setIpInfo()
    {
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
                                var date = formatDate(response.data.time_zone[0].localtime);
                                footerYear.html(footerYear.html()+" - "+date);
                            }
                        }
                    });
                }
            }
        });
    }

    function formatDate(data)
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
         var min = data.getMinutes();
         if(min.toString().length == 1)
            min = "0"+min;

         return dia+"/"+mes+"/"+ano+" "+hora+":"+min;
     }

});