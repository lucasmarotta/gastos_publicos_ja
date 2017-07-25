<nav class="navbar">
	<div class="container">
		<div class="navbar-header">
			<a class="navbar-brand" href="/#page-top">
				{{ HTML::image('/assets/img/logo.png', "Obras Públicas Já", ['class' => 'img-responsive', 'width' => 336, 'height' => 35]) }}
			</a>
		</div>
        <ul class="navbar-nav">
            <li class="page-scroll {{ Route::current()->getName() == 'home' ? 'active': '' }}">
                <a href="/">Início</a>
            </li>
            <li class="page-scroll">
				@if(Route::current()->getName() == 'home')
					<a href="#mais-vistas">Mais Vistas</a>
				@else
					<a href="/#mais-vistas">Mais Vistas</a>
				@endif
                
            </li>
            <li class="page-scroll">
				@if(Route::current()->getName() == 'home')
					<a href="#nas-redes">Nas Redes</a>
				@else
					<a href="/#nas-redes">Nas Redes</a>
				@endif
            </li>
            <li class="page-scroll {{ Route::current()->getName() == 'obras.index' ? 'active': '' }}">
                <a href="/obras">Obras</a>
            </li>
        </ul>
	</div>
</nav>