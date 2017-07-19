<?php 

use Carbon\Carbon;

class ComentariosController extends BaseController {

	public function __construct() 
	{
	}

	/*
	Updates the total of comments of Disqus
	*/
	public function updateCommentsCount() {
		$comentarios = Comentario::all();

		foreach($comentarios as $comentario) {
			$xml = simplexml_load_file($comentario->disqusUrl);
			$total = count($xml->channel->item);

			if ($comentario->disqusTotal != $total) {
				$comentario->disqusTotal = $total;
				$comentario->save();
			}

			echo "<pre>";
			print_r($comentario->toArray());
			echo "</pre>";
		}
	}

	/*
	Gets all new threads from Disqus once in day and updating the database count
	*/
	public function getNewThreads() {
		$disqus = new \Disqus('VYZnQpcvZ7syRODT296lwR9cKq2pI9QmVYhti2aHzEaOahjJBImCOJX6wmLW4oRj');

		$disqus->setSecure(false);

		$data =  Carbon::now()->subWeek();

		$params = array(
		   'forum'=>'obraspblicasj',
		   'order'=>'asc',
		   'include'=>'approved',
		   'related'=>'thread',
		   'since'=>$data
		);

		$comentarios = $disqus->posts->list($params);

		$comentarios_existentes = [];

		foreach ($comentarios as $comentario) {
			$obra_id = (int)$comentario->thread->identifiers[0];

			if (!isset($comentarios_existentes[$obra_id])) {
				$comentarios_existentes = [$obra_id => true];

				$velho_comentario = Comentario::where('obra_id', '=', $obra_id)->get()->first();
				if ($velho_comentario)
					continue;
				
				$novo_comentario = new Comentario();
				$novo_comentario->fill([
					'obra_id' => (int)$comentario->thread->identifiers[0],
					'disqusUrl' => $comentario->thread->feed,
					'disqusTotal' => $comentario->thread->posts,
				]);

				echo "<pre>";
				var_dump($novo_comentario->toArray());
				echo "</pre>";

				$novo_comentario->save();
			}
		}
	}
}

 ?>