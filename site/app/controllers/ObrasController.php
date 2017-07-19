<?php 

use Carbon\Carbon;

class ObrasController extends BaseController {

	public function __construct() 
	{
	}

	public function index() {

		$input = Input::except('page');
		$estados = Estado::all();

		$obras = [];

		$obras = Obra::whereRaw("1");

		if (isset($input['estado']) && $input['estado'] != 'todos') {
			$estado = Estado::where('nome', '=', $input['estado'])->get()->first();

			if ($estado != NULL)
				$obras = $obras->where("estadoId", '=', $estado->id);
		}

		if (isset($input['nome']) && trim($input['nome']) != '') {
			$obras = $obras->where("nome", 'like', "%$input[nome]%");
		}

		if (isset($input['situacao']) && $input['situacao'] != 'todos') {
			$obras = $obras->where('situacao', 'like', $input['situacao']);
		}

		$obras = $obras->paginate(10);

		$obras->appends($input);

		return View::make('obras.index', compact('input', 'obras', 'estados'));
	}

	public function show($id) {
		$obra = Obra::find($id);

		if ($obra == NULL) {
			return View::make('pages.not_found');
		}

		$obra->dataInicio = $start_date = Carbon::createFromFormat('Y-m-d', $obra->dataInicio);
		$obra->dataPrevisao = $estimate_date = Carbon::createFromFormat('Y-m-d', $obra->dataPrevisao);
		$today = Carbon::today();

		if ($obra->dataConclusao != NULL && $obra->dataConclusao != "0000-00-00")
			$obra->dataConclusao = $finished_date = Carbon::createFromFormat('Y-m-d', $obra->dataConclusao);

		$barra_de_progresso = [
			'status' => "",
			'blue_bar' => "0",
			'red_bar' => "0",
		];

		// melhorar esse checagem
		// utilizar enum
		if ($obra->situacao == "concluída") {
			if ($estimate_date >= $finished_date) {
				// concluida em tempo
				$totalInDays = $start_date->diff($finished_date)->days;
				$estimated = $start_date->diff($estimate_date)->days;
				
				$barra_de_progresso['status'] = "Concluída dentro do tempo previsto";
				$barra_de_progresso['blue_bar'] = round($totalInDays / $estimated * 100);

			} else {

				$totalInDays = $start_date->diff($finished_date)->days;
				$estimated = $start_date->diff($estimate_date)->days;

				$barra_de_progresso['status'] = "Concluída com atrasos";
				$barra_de_progresso['blue_bar'] = round($estimated / $totalInDays * 100);
				$barra_de_progresso['red_bar'] = 100 - $barra_de_progresso['blue_bar'];

			}
		} else {
			if ($estimate_date > $today) {

				$totalInDays = $start_date->diff($today)->days;
				$estimated = $start_date->diff($estimate_date)->days;
				
				$barra_de_progresso['status'] = "Em andamento";
				$barra_de_progresso['blue_bar'] = round($totalInDays / $estimated * 100);
			} else {

				$totalInDays = $start_date->diff($today)->days;
				$estimated = $start_date->diff($estimate_date)->days;

				$barra_de_progresso['status'] = "Atrasada";
				$barra_de_progresso['blue_bar'] = round($estimated / $totalInDays * 100);
				$barra_de_progresso['red_bar'] = 100 - $barra_de_progresso['blue_bar'];
			}
		}

		return View::make('obras.show', compact('obra', 'barra_de_progresso'));
	}

	public function getRandomObra()
	{
		$obra = DB::table("Obra")->join("Estado","Obra.estadoId","=","Estado.id")
					->select("Obra.nome",DB::raw("LOWER(sigla) sigla"))->orderBy(DB::raw("RAND()"))->take(1)->get();

		if($obra && isset($obra[0]))
		{
			$obra[0]->nome = urlencode($obra[0]->nome);
			echo json_encode($obra[0]);
		}
	}

}

 ?>