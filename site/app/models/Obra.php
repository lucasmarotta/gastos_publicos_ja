<?php

use Carbon\Carbon;

class Obra extends Eloquent{

	// Changing defaut table
	protected $table = 'Obra';

	protected $fillable = [
		'nome',
		'estadoId',
		'local',
		'orgao',
		'dataInicio',
		'dataPrevisao',
		'dataConclusao',
		'tipo',
		'situacao',
		'valor'
	];

	public static $situacao = [
		''	=> 'todos',
		'1' => 'em andamento',
		'2' => 'concluída',
		'3' => 'atrasada',
		'4' => 'cancelada',
		'5' => 'suspensa'
	];

	/*
	|---------------------------------------------------------------------
	| Relationship
	|---------------------------------------------------------------------
	*/

	public function estado() {
		return $this->belongsTo('Estado', 'estadoId');
	}
}