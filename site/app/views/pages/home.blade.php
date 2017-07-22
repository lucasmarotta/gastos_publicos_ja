@extends('layouts.application')
@section('content')

<header>
	<div class="container">

		<section>
        	{{ HTML::image("/assets/img/logo.png", "Obras Públicas Já", ['class' => "img-responsive"]) }}
            <p>Acompanhe, comente, cobre<br>as obras públicas de seu estado</p>
		</section>

        <form class="state-search" action="/obras" method="GET">
            <div class="input-group">
                <span class="input-group-icon br-brasil"></span>
                <input class="input-group-field" type="text" name="estado" placeholder="Um estado">
            </div>
        </form>

	</div>
</header>


@stop