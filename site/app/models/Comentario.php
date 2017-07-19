<?php

use Carbon\Carbon;

class Comentario extends Eloquent{

	protected $fillable = [
		'obra_id',
		'disqusUrl',
		'disqusTotal',
		'twitterUrl',
		'twitterTotal',
	];

	/*
	|---------------------------------------------------------------------
	| Relationship
	|---------------------------------------------------------------------
	*/

	public function obra() {
		return $this->belongsTo('Obra');
	}
}