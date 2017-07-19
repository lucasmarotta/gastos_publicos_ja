<?php

use Carbon\Carbon;

class Estado extends Eloquent{

	// Changing defaut table
	protected $table = 'Estado';

	/*
	|---------------------------------------------------------------------
	| Relationship
	|---------------------------------------------------------------------
	*/

	public function obras() {
		return $this->hasMany('Obra', 'estadoId', 'id');
	}
}