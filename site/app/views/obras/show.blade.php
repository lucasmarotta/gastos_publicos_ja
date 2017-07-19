@extends('layouts.application')

@section('facebook-opengraph')
    <meta property="fb:app_id"          content="278225112566275" /> 
    <meta property="og:type"            content="article" /> 
    <meta property="og:url"             content="{{Request::url()}}" /> 
    <meta property="og:title"           content="{{$obra->nome}}" /> 
@stop


@section('content')
<section class="default-section">
    <div class="container">

        <div class="row">
            <div class="col-lg-12 title-style text-center">
                <h2>Dados da Obra</h2>
                <div class="title-icon">
                    <hr><span class="icon-marker"></span><hr>
                </div>
            </div>
        </div>

        <div class="row">

            <div class="col-lg-12">
                <h2><span class="icon-text"></span> {{$obra->nome}}</h2>
            </div>

            <div class="col-lg-12 obra-detail">
                <h4>Detalhes</h4>
                <ul>
                    <li><span class="icon-brasil"></span> Estado: {{$obra->estado->nome}}</li>
                    <li><span class="icon-money"></span> Valor: R${{number_format($obra->valor,2,',','.')}}</li>
                    <li><span class="icon-calendar"></span> In&iacute;cio do Projeto: {{$obra->dataInicio->format('m/Y')}}</li>
                    <li><span class="icon-calendar"></span> T&eacute;rmino previsto: {{$obra->dataPrevisao->format('m/Y')}}</li>
                    @if ($obra->situacao == "concluída")
                        <li><span class="icon-calendar"></span> Finalizado em: {{$obra->dataConclusao->format('m/Y')}}</li>
                    @endif
                </ul>
            </div>
        </div>
		
		<!-- progress bar -->
        <div class="row">
        	<div class="col-lg-12">
                
			<?php 

			$opacity = 1.0;
			if ($obra->situacao == 'concluída')
				$opacity = 0.40;
			?>
            
             <div class="title-style text-center">
                <h2>Evolução da Obra</h2>
                <div class="title-icon">
                    <hr><span class="icon-hourglass"></span><hr>
                </div>
            </div>           
			
			<div id="barra_de_progresso" style="width: 100%; height: 50px; background:gray; padding: 5px; opacity: {{$opacity}}">
				<div id="barra_previsto" style="width: {{$barra_de_progresso['blue_bar']}}%; height: 100%; background: blue; float: left;"></div>
				<div id="barra_atraso" style="width: {{$barra_de_progresso['red_bar']}}%; height: 100%; background: red; float: left;"></div>
			</div>
			<p>{{$barra_de_progresso['status']}}</p>

            </div>
        </div>
        
        <div class='project-social-links'>
            <div class="fb-share-button" data-href="{{Request::url()}}" data-layout="button_count" data-mobile-iframe="true"></div>

            <a href="https://twitter.com/share" class="twitter-share-button" data-lang="pt-br"></a>
        </div>

        <!-- Conserta isso ai, Caju!!! -->
        <br><br><br><br><br>

        <div class="row">
        	<div class="col-lg-12">
            	<div id="disqus_thread"></div>
        	</div>
        </div>        
    </div>
</section>

<!-- Facebook -->
<script>
  window.fbAsyncInit = function() {
    FB.init({
      appId      : '278225112566275',
      xfbml      : true,
      version    : 'v2.6'
    });
  };

  (function(d, s, id){
     var js, fjs = d.getElementsByTagName(s)[0];
     if (d.getElementById(id)) {return;}
     js = d.createElement(s); js.id = id;
     js.src = "//connect.facebook.net/en_US/sdk.js";
     fjs.parentNode.insertBefore(js, fjs);
   }(document, 'script', 'facebook-jssdk'));
</script>

<!-- Twitter -->

<script>
    !function(d,s,id){
        var js,fjs=d.getElementsByTagName(s)[0];
        p=/^http:/.test(d.location)?'http':'https';
        if(!d.getElementById(id)){
            js=d.createElement(s);
            js.id=id;
            js.src=p+'://platform.twitter.com/widgets.js';
            fjs.parentNode.insertBefore(js,fjs);
        }
    }(document, 'script', 'twitter-wjs');
</script>

<!-- Disqus -->
<script>
var disqus_config = function () {
this.page.url = '<?php echo url() . "/obras/". $obra->id; ?>';
this.page.identifier = '<?php echo $obra->id; ?>';
};

(function() { // DON'T EDIT BELOW THIS LINE
var d = document, s = d.createElement('script');

s.src = '//obraspblicasj.disqus.com/embed.js';

s.setAttribute('data-timestamp', +new Date());
(d.head || d.body).appendChild(s);
})();
</script>
<noscript>Please enable JavaScript to view the <a href="https://disqus.com/?ref_noscript" rel="nofollow">comments powered by Disqus.</a></noscript>
@stop