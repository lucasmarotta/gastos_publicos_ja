@extends('layouts.application')
@section('content')

<section class="obra-section default-section">
	
    <div class="container">
        
        <div class="section-title">
            <h2>Lista de Obras</h2>
            <div class="section-title-icon">
                <hr><span class="vue-forklift"></span><hr>
            </div>
        </div>
		
		<div class="row-box">
			<form class="obra-filter">
				<div class="input-group">
					<span class="input-group-icon vue-text"></span>
					<input type="text" class="input-group-field" name="nome" id="nome_form" placeholder="Título Da Obra">
				</div>	

				<div class="input-group">
					<span class="input-group-icon br-brasil"></span>
					<select name="estado" class="input-group-field" id="estado_form" title="Estado">
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

				<div class="input-group">
					<span class="input-group-icon vue-tag"></span>
					<select name="situacao" class="input-group-field" id="situacao_form">
						@foreach(Obra::$situacao as $index => $value)
							<option value="{{$value}}" 
								<?php if(isset($input['situacao']) && $input['situacao'] == $value) echo 'selected="selected"' ?>
								>
								{{$value}}
							</option>
						@endforeach
					</select>
				</div>

				<input type="submit" class="btn" value="Filtrar">

			</form>
		</div>


        <div class="row-box">
        	@if(count($obras) > 0)
	            @foreach($obras as $obra)
	                <a href="/obras/{{$obra->id}}">
	                    <div class="obra-container">
	                        <div class="obra-uf">
	                            <span class="br-uf-{{strtolower($obra->estado->sigla)}}"></span>
	                        </div>
	                        <div class="obra-info">
	                            <h4>{{$obra->nome}}</h4>
	                            <p><span class="br-brasil"></span> {{ $obra->estado->nome }}</p>
	                            <p class="obra-value numeric">R${{number_format($obra->valor,2,',','.')}}</p>
	                        </div>
	                    </div>
	                </a>
	            @endforeach
	        @else
				<p>Não há obras cadastradas para o estado</p>
			@endif

        </div>
		
		<div class="row-box">
			{{ $obras->links() }}
		</div>
		

    </div>

</section>
@stop