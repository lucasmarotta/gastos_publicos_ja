@extends('layouts.application')
@section('content')

<section class="obra-section default-section">
    <div class="container">
        <div class="section-title">
            <h2>Dados da Obra</h2>
            <div class="section-title-icon">
                <hr><span class="vue-forklift"></span><hr>
            </div>
        </div>
	
		<div class="row-box">
			<h2><span class="vue-text"></span> {{$obra->nome}}</h2>
			
			<div class="obra-detail">
	            <h4>Detalhes</h4>
	            <ul>
	                <li><span class="br-brasil"></span> Estado: {{$obra->estado->nome}}</li>
	                <li><span class="vue-dollar"></span> Valor: R${{number_format($obra->valor,2,',','.')}}</li>
	                <li><span class="vue-calendar"></span> In&iacute;cio do Projeto: {{$obra->dataInicio->format('m/Y')}}</li>
	                <li><span class="vue-calendar"></span> T&eacute;rmino previsto: {{$obra->dataPrevisao->format('m/Y')}}</li>
	                @if ($obra->situacao == "concluída")
	                    <li><span class="vue-calendar"></span> Finalizado em: {{$obra->dataConclusao->format('m/Y')}}</li>
	                @endif
	            </ul>			
			</div>
		</div>
	</div>
</section>

<section class="obra-timeline-section">
	<div class="container">
	    <div class="section-title">
	        <h2>Evolução da Obra</h2>
	        <div class="section-title-icon">
	            <hr><span class="vue-activity"></span><hr>
	        </div>
	    </div>

	    <div class="row-box">
			<?php 
				$opacity = 1.0;
				if ($obra->situacao == 'concluída') $opacity = 0.40;
			?>      
			
			<div class="progress-bar" style="opacity: {{$opacity}}">
				<div id="foreseen-bar" style="width: {{$progressBar['blue_bar']}}%; height: 100%; background: blue; float: left;"><p>Previsto</p></div>
				<div id="delay-bar" style="width: {{$progressBar['red_bar']}}%; height: 100%; background: red; float: left;"><p>Atraso</p></div>
			</div>
			<p><b><i>{{$progressBar['status']}}</i></b></p>
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
