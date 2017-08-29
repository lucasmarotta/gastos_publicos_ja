/*
	Apesar de $ parecer com Jquery, não é JQUERY, mas sim parte da biblioteca JRAW
	criada para este projeto
*/
$(function(){

    var footerYear = $(".footer-year"),
        gastosModel = $(".gastos-model"),
        gastosSection = $(".gastos-section"),
        tweet = $(".tweet"),
        gastos = $(".gastos"),
        brasilMap = $(".brasil-map"),
        socialSection = $(".social-section"),
        ufAtual = null,
        inputSearch = $(".state-search input"),
        estados = {
            ac:["acre", "ac"],
            al:["alagoas", "al"],
            ap:["amapá", "amapa", "ap"],
            ba:["bahia", "ba"],
            ce:["ceará", "ceara", "ce"],
            df:["destrito federal", "brasília", "brasilia", "df", "br"],
            es:["espírito santo", "espirito santo", "es"],
            go:["goias", "go"],
            ma:["maranhão", "maranhao"],
            mt:["mato grosso", "mato grosso do norte", "mt"],
            ms:["mato grosso do sul", "ms"],
            mg:["minas gerais", "minas", "mg"],
            pa:["pará", "para", "pa"],
            pb:["paraíba", "paraiba", "pb"],
            pr:["paraná", "parana", "pr"],
            pe:["pernambuco", "pe"],
            pi:["piauí", "piaui", "pi"],
            rj:["rio de janeiro", "rio", "rj"],
            rn:["rio grande do norte", "rio grande", "rn"],
            rs:["rio grande do sul", "rs"],
            ro:["rondônia", "rondonia", "ro"],
            rr:["roraima", "rr"],
            sc:["santa catarina", "sc"],
            sp:["são paulo", "sao paulo", "sp"],
            se:["sergipe", "se"],
            to:["tocantins", "to"]
        };

    setIpInfo();
    loadBrasilMap();

    $(".btn-search").click(function(){
        var uf = checkUf(inputSearch.val().toLowerCase());
        if(uf != null) {
            ufAtual = uf;
            gastosSection.show();
            gastosSection.append("<div class='loading'></div>");
            loadGastos("http://localhost:8000/gastos/"+uf+"?pg=1&ano=2017");
        } else {
            alert("Digite um estado válido");
        }
    });

    function loadGastos(url)
    {
        $.getJSON({url:url,
            success: function(response) {
                gastos.html("").fadeIn();
                gastosSection.find(".loading").remove();
                response.gastos.forEach(function(item, index){
                    var newGastos = gastosModel.clone();
                    newGastos.bindValue("url","detalhes.html");
                    newGastos.bindValue("gastos-uf","br-uf-"+ufAtual);
                    newGastos.bindValue("gastos-orgao", item.orgao);
                    newGastos.bindValue("gastos-estado-nome", estados[ufAtual][0]);
                    newGastos.bindValue("gastos-empenhado", "Empenhado R$"+item.empenhado);
                    newGastos.bindValue("gastos-pago", "Pago R$"+item.pago);
                    newGastos.bindValue("gastos-liquido", "Líquido R$"+item.liquido);
                    gastos.append(newGastos.show().get(0));
                });
                mapLinks(response, "http://localhost:8000/gastos/es");
            },
            fail: function (response) {
                gastosSection.find(".loading").remove();
                console.warn("Não foi possível obter gastos");
            }
        });
    }

    function mapLinks(obj, baseUrl)
    {
        $(".pagination").remove();
        gastosSection.append(createLinks(obj, baseUrl));
        $(".pagination").find("a").click(function(event){
            event.preventDefault();
            gastosSection.append("<div class='loading'></div>");
            loadGastos(this.getAttribute("href"));
        });
    }

    function createLinks (obj, baseUrl)
    {
        var links = "<ul class='pagination'>";
        if(obj.curPg == 1) {
            links += "<li class='disabled'><a href='#' onclick='paginate'><span class='vue-left'></span></a></li>";
        } else {
            links += "<li><a href='"+baseUrl+"?pg="+(obj.curPg-1)+"&ano=2017'><span class='vue-left'></span></a></li>";
        }

        for(var i = 1; i <= obj.totalPg; i++) {
            if(obj.curPg == i) {
                links += "<li class='active'><a href='"+baseUrl+"?pg="+i+"&ano=2017'>"+i+"</a></li>"; 
           } else {
                links += "<li><a href='"+baseUrl+"?pg="+i+"&ano=2017'>"+i+"</a></li>";
           }
        }

        if(obj.curPg == obj.totalPg) {
            links += "<li class='disabled'><a href='#' onclick='paginate'><span class='vue-left'></span></a></li>";
        } else {
            links += "<li><a href='"+baseUrl+"?pg="+(obj.curPg+1)+"&ano=2017'><span class='vue-right'></span></a></li>";
        }

        links += "</ul>";
        return links;
    }

    function loadBrasilMap()
    {
        var randomOrgaoRequest = {
                url:"http://localhost:8000/gastos/randomTweet",
                data: {ano:"2017"},
                cache: false,
                success: function(response) {
                    if(response.user) {
                        console.info("Tweet com termo: "+response.term+" do uf "+response.uf+" foi encontrado!");
                        brasilMap.show().find(".highlight-uf").removeClass("highlight-uf");
                        socialSection.find(".loading").remove();
                        var estado =  brasilMap.find("#uf-"+response.uf);
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
                        console.warn("Não foi possível obter o tweet com o termo: "+response.term+" do uf "+response.uf);
                    }
                },
                fail: function() {
                    socialSection.find(".loading").remove();
                    console.warn("Não foi possível obter tweet randômico");
                }
        };
        $.getJSON(randomOrgaoRequest);
        setInterval(function(){
            $.getJSON(randomOrgaoRequest);
        },15*1000);
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

    function checkUf(uf)
    {
        for (var key in estados) {
            if (estados.hasOwnProperty(key)) {
                if(estados[key].includes(uf)) {
                    return key;
                }
            }
        }
        return null;     
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
