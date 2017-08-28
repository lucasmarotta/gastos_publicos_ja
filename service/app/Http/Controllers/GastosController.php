<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class GastosController extends Controller
{
	const ES_URL = "https://transparencia.es.gov.br/Api/Despesa/ListarOrgaos";

	public function index()
	{
		echo "<pre>";

		$url = "https://servicos.goias.gov.br/ptg-api/rest/execorcamentarianaturezadespesa?ano=2017&mes=1";
		$opts = [
		  'http'=>[
		    'method'=>"GET",
		    'header'=>	"content-type: application/gov.go.ptg.service.entidade.v1.despesas.execorcamentarianaturezadespesa-v1+json\r\n" .
		    			"transfer-encoding: chunked\r\n" .
		    			"content-range: null/340\r\n"
		  ]
		];
		$context = stream_context_create($opts);
		$result = file_get_contents($url,false,$context);
	}

	public function es(Request $request)
	{
		$ano = $request->input("ano") ? : "2017";
		$pg = $request->input("pg") ? : 1;
		$url = self::ES_URL."?ano=$ano&numeroPagina=1";
		$context = $this->createContext(["content-type:application/json"]);
		if($context) {
			$response = $this->requestGastos($url, $context);
			if($response) {
				$gastos = $this->paginateEs($response->Orgaos, $pg, $ano);
				return ($gastos) ? $gastos:"";
			}
		}
		return '';
	}

	public function random(Request $request)
	{
		$ano = $request->input("ano") ? : "2017";
		$pg = $request->input("pg") ? : 1;
		$url = self::ES_URL."?ano=$ano&numeroPagina=1";
		$context = $this->createContext(["content-type:application/json"]);
		if($context) {
			$response = $this->requestGastos($url, $context);
			if($response) {
				return $this->getRandomOrgaoEs($response->Orgaos);
			}
		}
		return '';		
	}

	public function getRandomOrgaoEs($gastos)
	{
		$total = count($gastos);
		$randomKey = rand(0, $total-1);
		return $gastos[$randomKey]->strNomeUnidadeGestora ? : "";
	}

	private function paginateEs($gastos, $pg, $ano)
	{
		$gastosNew = [];
		$total = count($gastos);
		$totalPg = ceil($total/10);
		if($pg <= $totalPg) {
			$gastosNew["total"] = $total;
			$gastosNew["ano"] = "$ano";
			$gastosNew["totalPg"] = $totalPg;
			$gastosNew["curPg"] = "$pg";
			$offset = ($pg-1)*10;
			$length = 10;
			$orgaos = array_slice($gastos, $offset, $length, true);
			$gastosNew["gastos"] = [];

			foreach ($orgaos as $key => $value) {
				$gastosNew["gastos"][] = [
					"orgao"=>$value->strNomeUnidadeGestora,
					"empenhado"=>number_format($value->total_emp, 2, ',', '.'),
					"liquido"=>number_format($value->total_liq, 2, ',', '.'),
					"pago"=>number_format($value->total_pg, 2, ',', '.')
				];
			}

			return json_encode($gastosNew);
		}
		return null;
	}

	private function requestGastos($url,$context)
	{
		try {
			$result = file_get_contents($url,false,$context);
			if($result) return json_decode($result);
			return null;
		} catch(Exception $e) {
			return null;
		}
	}

	private function createContext($headers)
	{
		$opts = ['http'=>['method'=>'GET', 'header'=>'']];
		foreach ($headers as $key => $value) {
			$opts["http"]['header'] .= $value."\r\n";
		}
		try {
			return stream_context_create($opts);
		} catch(Exception $e) {
			return null;
		}
	}

}