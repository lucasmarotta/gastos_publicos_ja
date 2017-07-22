<nav class="navbar navbar-default navbar-fixed-top">
    <div class="container">
        <!-- Brand and toggle get grouped for better mobile display -->
        <div class="navbar-header page-scroll">
            <button type="button" class="navbar-toggle" data-toggle="collapse" data-target="#bs-example-navbar-collapse-1">
                <span class="sr-only">Toggle navigation</span>
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
            </button>

            @if(Route::current()->getName() == 'home')
            <a class="navbar-brand" href="#page-top">
            @else
            <a class="navbar-brand" href="/#page-top">
            @endif
            	{{ HTML::image('/assets/img/logo.png', "Obras Públicas Já", ['class' => 'img-responsive', 'width' => 336, 'height' => 35]) }}
            </a>
        </div>

        <!-- Collect the nav links, forms, and other content for toggling -->
        <div class="collapse navbar-collapse" id="bs-example-navbar-collapse-1">
            <ul class="nav navbar-nav navbar-right">
                <li class="hidden">
                    <a href="/#page-top"></a>
                </li>
                <li class="page-scroll">
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
                <li class="page-scroll">
                    <a href="obras">Obras</a>
                </li>
                <!--<li class="page-scroll">
                    <a href="#contact">Contato</a>
                </li>-->
            </ul>
        </div>
        <!-- /.navbar-collapse -->
    </div>
    <!-- /.container-fluid -->
</nav>