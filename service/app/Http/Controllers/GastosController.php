<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Abraham\TwitterOAuth\TwitterOAuth;

class GastosController extends Controller
{
	const ES_URL = "https://transparencia.es.gov.br/Api/Despesa/ListarOrgaos";

    private $twiiterApp = [
        'key' => 'dLDm9kp0I9raYSUhFraWugjA6',
        'secret' => 'UwaVvzZj1BoXNMmBEtebmDZwOfrBeNSUteA2bELHoM3oePl9to',
        'oauth_token' => '737760914825482240-gNw7HOp1tZJZniy83rtf8atzWJl8Ywn',
        'oauth_token_secret' => 'NvsvSzepJg0XuAocSa7O5tUamBXSufRqNg8wE73VNvjpx'
    ];


	public function index()
	{
		set_time_limit(60);
		echo "<pre>";
		$url = "https://servicos.goias.gov.br/ptg-api/rest/execorcamentarianaturezadespesa?ano=2017&mes=1";
		$header = ["accept: application/gov.go.ptg.service.entidade.v1.despesas.execorcamentarianaturezadespesa-v1+json", "content-type:*/*"];
		print_r($this->requestGastos($url, $this->createContext($header)));
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

	public function randomTweet(Request $request)
	{
		$ano = $request->input("ano") ? : "2017";
		$pg = $request->input("pg") ? : 1;
		$url = self::ES_URL."?ano=$ano&numeroPagina=1";
		$context = $this->createContext(["content-type:application/json"]);
		if($context) {
			$response = $this->requestGastos($url, $context);
			if($response) {
				return $this->getTweet($this->getRandomOrgaoEs($response->Orgaos));
			}
		}
		return '{}';		
	}

	private function getRandomOrgaoEs($gastos)
	{
		$total = count($gastos);
		$randomKey = rand(0, $total-1);
		return $gastos[$randomKey]->strNomeUnidadeGestora ? : "";
	}

    private function getTweet($term)
    {
    	if($term) {
	        $tweet = $this->searchTwitter($term);
	        return ($tweet) ? $tweet:"{}";
    	}
    	return "{}";
    }

    private function searchTwitter($term)
    {    	
    	$tweet["term"] = $term;
        try {
            $conn = new TwitterOAuth($this->twiiterApp["key"], $this->twiiterApp["secret"], $this->twiiterApp["oauth_token"], $this->twiiterApp["oauth_token_secret"]);
            $params = ['q' => $term,
                'count' => 1,
                'tweet_mode'=>'extended',
                'result_type'=>'recent',
                'locale' => 'pt'
            ];
            if($conn) {
                $response = $conn->get('search/tweets', $params);
                if($response && count($response->statuses) > 0) {
                    $tweet["text"] = $response->statuses[0]->full_text;
                    $tweet["user"] = "@".$response->statuses[0]->user->name;
                }
            }
            return $tweet;
        } catch(Exception $e) {
        	return $tweet;
        }  
    }

	private function paginateEs($gastos, $pg, $ano)
	{
		$gastosNew = [];
		$total = count($gastos);
		$totalPg = ceil($total/10);
		if($pg <= $totalPg) {
			$gastosNew["total"] = $total;
			$gastosNew["ano"] = $ano;
			$gastosNew["totalPg"] = $totalPg;
			$gastosNew["curPg"] = intval($pg);
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