@extends('layouts.application')

@section('content')

<section class="obra-section default-section">
    <div class="container">

        <div class="row">
            <div class="col-lg-12 title-style text-center">
                <h2>Listas de Obras</h2>
                <div class="title-icon">
                    <hr><span class="icon-marker"></span><hr>
                </div>
            </div>
        </div>

		<div class="row">
			<div class="col-lg-12">
				<form class="form-inline obra-filter">

					<div class="form-group">
						<label for="nome_form">Nome</label>
						<input type="text" class="form-control" name="nome" id="nome_form">
					</div>

					<div class="form-group">
						<label for="estado_form">Estado</label>
						<select name="estado" class="form-control" id="estado_form">
							<option value="todos">todos</option>
							@foreach($estados as $estado)
								<option value="{{$estado->nome}}" 
									<?php if(isset($input['estado']) && $input['estado'] == $estado->nome) echo 'selected="selected"' ?>
									>
									{{$estado->nome}}
								</option>
							@endforeach
						</select>
					</div>

					<div class="form-group">
						<label for="situacao">Situa&ccedil;&atilde;o</label>
						<select name="situacao" class="form-control" id="situacao_form">
							@foreach(Obra::$situacao as $index => $value)
								<option value="{{$value}}" 
									<?php if(isset($input['situacao']) && $input['situacao'] == $value) echo 'selected="selected"' ?>
									>
									{{$value}}
								</option>
							@endforeach
						</select>
					</div>

					<input type="submit" class="btn btn-default" value="Filtrar">
				</form>
			<div>
		</div>

		<div class="row">
			
			@if(count($obras) > 0)
	            @foreach($obras as $obra)
	                <a href="/obras/{{$obra->id}}" class="obra-container">
	                    <div class="col-sm-6">
	                        <div class="obra-uf">
	                            <span class="icon-uf-{{strtolower($obra->estado->sigla)}}"></span>
	                        </div>
	                        <div class="obra-info">
	                            <h4>{{$obra->nome}}</h4>
	                            <p><span class="icon-brasil"></span> {{ $obra->estado->nome }}</p>
	                            <p class="obra-value numeric"><span class="icon-money"></span> R${{number_format($obra->valor,2,',','.')}}</p>
	                        </div>
	                    </div>
	                </a>
	            @endforeach
			@else
				<p>N&atilde;o h&aacute; obras cadastradas para o seu estado</p>
			@endif
			<div class="pagination"> {{ $obras->links() }} </div>
        </div>
    </div>
</section>
@stop