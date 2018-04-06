<?php
/*~ tempo.class.php
.---------------------------------------------------------------------------.
|  Software: PrevisaoTempo - PHP previsão do tempo class                    |
| ------------------------------------------------------------------------- |
|   Authors: Diego Augusto Ferraz (Barra3 Web Design) diego@barra3.com.br 	|
| ------------------------------------------------------------------------- |
|   Description: Classe baseada na API do Yahoo Weather    					|
|            	 https://developer.yahoo.com/weather/                    	|
| Define a previsão do tempo e outras constantes metereológinas para a 		|
| cidade informada															|
'---------------------------------------------------------------------------'
*/

/**
 * PrevisaoTempo - classe para previsão de tempo
 * NOTE: Requires SimpleXML PHP extension
 * 		 Código da Cidade: CÓDIGO Yahoo WOEID
 *		 http://woeid.rosselliot.co.nz/
 */

	class PrevisaoTempo {

		var $cod_cidade;
		var $escala = "F";
		var $idioma;

		var $titulo;
		var $link;
		var $descricao;
		var $linguagem;
		var $ultima_modificacao;
		var $tempo_de_vida;
		var $cidade;
		var $regiao;
		var $pais;
		var $un_temperatura; 	// °C ou °F
		var $un_distancia; 		// km ou mi
		var $un_pressao;  		// unidades de pressão barométrica, in ou mb
		var $un_velocidade; 	// mph m/h ou kph km/h
		var $vento_temperatura; // graus °
		var $vento_direcao; 	// em graus °
		var $velocidade; 		// unidade de media -> $un_velocidade
		var $umidade; 			// em %
		var $visibilidade; 		// unidade de medida -> $un_distancia
		var $pressao; 			// unidade de medida -> $un_pressao
		var $estado_da_pressao; // estado da pressão barométrica: constante (0), elevando-se (1), ou em queda (2).
		var $nascer_do_sol;
		var $latitude;
		var $longitude;
	  	var $item_titulo;
		var $item_link;
		var $item_publicacao;
		var $condicao_atual;
		var $temperatura_atual;
		var $codigo_atual;
		var $data_atual;
		var $descricao_atual;
		var $imagem_url;
		var $prox_dia 	   = array();
		var $prox_data 	   = array();
		var $prox_minima   = array();
		var $prox_maxima   = array();
		var $prox_condicao = array();
		var $prox_codigo   = array();

		var $conditions_code = array(0=>"tornado", 1=>"tropical storm", 2=>"hurricane", 3=>"severe thunderstorms", 4=>"thunderstorms", 5=>"mixed rain and snow", 6=>"mixed rain and sleet", 7=>"mixed snow and sleet", 8=>"freezing drizzle", 9=>"drizzle", 10=>"freezing rain", 11=>"showers", 12=>"showers", 13=>"snow flurries", 14=>"light snow showers", 15=>"blowing snow", 16=>"snow", 17=>"hail", 18=>"sleet", 19=>"dust", 20=>"foggy", 21=>"haze", 22=>"smoky", 23=>"blustery", 24=>"windy", 25=>"cold", 26=>"cloudy", 27=>"mostly cloudy (night)", 28=>"mostly cloudy (day)", 29=>"partly cloudy (night)", 30=>"partly cloudy (day)", 31=>"clear (night)", 32=>"sunny", 33=>"fair (night)", 34=>"fair (day)", 35=>"mixed rain and hail", 36=>"hot", 37=>"isolated thunderstorms", 38=>"scattered thunderstorms", 39=>"scattered thunderstorms", 40=>"scattered showers", 41=>"heavy snow", 42=>"scattered snow showers", 43=>"heavy snow", 44=>"partly cloudy", 45=>"thundershowers", 46=>"snow showers", 47=>"isolated thundershowers", 3200=>"not available");

		// tradução não oficial
		var $conditions_code_BR = array(0=>"tornado", 1=>"tempestade tropical", 2=>"furacão", 3=>"tempestades severas", 4=>"tempestades", 5=>"chuva com neve", 6=>"chuva com granizo", 7=>"neve com granizo", 8=>"garoa gelada", 9=>"garoa", 10=>"chuva gelada", 11=>"chuvas", 12=>"chuvas", 13=>"flocos de neve", 14=>"chuvisco com neve", 15=>"sopro de neve", 16=>"neve", 17=>"granizo", 18=>"granizo", 19=>"poeira", 20=>"nebuloso", 21=>"neblina", 22=>"esfumaçado", 23=>"tempestuoso", 24=>"ventoso", 25=>"frio", 26=>"nublado", 27=>"muito nublado (noite)", 28=>"muito nublado (dia)", 29=>"parcialmente nublado (noite)", 30=>"parcialmente nublado (dia)", 31=>"limpo (noite)", 32=>"ensolarado", 33=>"bom (noite)", 34=>"bom (dia)", 35=>"chuva com granizo", 36=>"quente", 37=>"tempestades isoladas", 38=>"tempestades espalhadas", 39=>"tempestades espalhadas", 40=>"chuvas espalhadas", 41=>"nevasca", 42=>"nevascas espalhadas", 43=>"nevasca", 44=>"parcialmente nublado", 45=>"tempestades", 46=>"chuvas de neve", 47=>"tempestades isoladas", 3200=>"indisponível");

		var $dias_EN = array("Sunday", "Monday", "Tuesday", "Wednesday", "Thursday", "Friday", "Saturday");
		var $dias_BR = array("Domindo", "Segunda-feira", "Terça-feira", "Quarta-feira", "Quinta-feira", "Sexta-feira", "Sábado");

		var $mes_EN = array("January", "February", "March", "April", "May", "June", "July", "August", "September", "October", "November", "December");
		var $mes_BR = array("Janeiro", "Fevereiro", "Março", "Abril", "Maio", "Junho", "Julho", "Agosto", "Setembro", "Outubro", "Novembro", "Dezembro");


		public function setLocalidade($local) {

		    return $this->cod_cidade = $local;
		}

		public function setEscala($escala) {

 			return $this->escala = $escala;
		}

		public function setIdioma($idioma) {

			return $this->idioma = $idioma;
		}

		public function converteCelcius($temperatura) {

			$tempC = ($temperatura - 32) / 1.8;
			return round($tempC);
		}

		public function getPrevisao()	{

			// $feed = file_get_contents("http://weather.yahooapis.com/forecastrss?w=".$this->cod_cidade."&u=".$this->escala);
			// $ch 	 = curl_init("http://weather.yahooapis.com/forecastrss?w=".$this->cod_cidade."&u=".$this->escala);
			// $url  = 'https://query.yahooapis.com/v1/public/yql?q=select%20*%20from%20weather.forecast%20where%20woeid%20=%20456331&format=xml&env=store%3A%2F%2Fdatatables.org%2Falltableswithkeys';

			$BASE_URL = "http://query.yahooapis.com/v1/public/yql";

		    $yql_query = "select * from weather.forecast where woeid = " . $this->cod_cidade ;
    		$yql_query_url = $BASE_URL . "?q=" . urlencode($yql_query) . "&format=xml";

    		// Make call with cURL
    		$session = curl_init($yql_query_url);
    		curl_setopt($session, CURLOPT_RETURNTRANSFER,true);
    		$feed = curl_exec($session);

			if ($feed) {

				$xml = new SimpleXmlElement($feed);

				$valor = $xml->results->channel;

				$this->titulo 				= $valor->title;
				$this->link 				= $valor->link;
				$this->descricao 			= $valor->description;
				$this->linguagem			= $valor->language;;
				$this->ultima_modificacao 	= $valor->lastBuildDate;
				$this->tempo_de_vida 		= $valor->ttl;

				$ns1 	  = $valor->getNameSpaces(true);
				$yweather = $valor->children($ns1["yweather"]);

				// UNIDADES DE MEDIDA
				$this->un_temperatura 	= $yweather->units->attributes()->temperature; 	// °C ou °F
				$this->un_distancia 	= $yweather->units->attributes()->distance; 	// km ou mi
				$this->un_pressao 		= $yweather->units->attributes()->pressure;  	// unidades de pressão barométrica, in ou mb
				$this->un_velocidade 	= $yweather->units->attributes()->speed; 		// mph m/h ou kph km/h

				// LOCALIZAÇÃO
				$this->cidade = $yweather->location->attributes()->city;
				$this->regiao = $yweather->location->attributes()->region;
				$this->pais   = $yweather->location->attributes()->country;

				// VENTO
				$this->vento_temperatura = $yweather->wind->attributes()->chill; 		// graus °
				$this->vento_direcao 	 = $yweather->wind->attributes()->direction; 	// em graus °
				$this->vento_velocidade  = $yweather->wind->attributes()->speed; 		// unidade de media -> $un_velocidade

				// ATMOSFERA
				$this->umidade 			 = $yweather->atmosphere->attributes()->humidity; 	// em %
				$this->pressao 			 = $yweather->atmosphere->attributes()->pressure; 	// unidade de medida -> $un_pressao
				$this->estado_da_pressao = $yweather->atmosphere->attributes()->rising; 	// estado da pressão barométrica: constante (0), elevando-se (1), ou em queda (2).
				$this->visibilidade		 = $yweather->atmosphere->attributes()->visibility; // unidade de medida -> $un_distancia

				// ASTRONOMIA
				$this->nascer_do_sol = $yweather->astronomy->attributes()->sunrise;
				$this->por_do_sol 	 = $yweather->astronomy->attributes()->sunset;

			  	// NÓ ITEM
			  	$ITEM = $valor->item;

			  	$this->item_titulo = $ITEM->title;

			  	$ns2 	   = $ITEM->getNameSpaces(true);
				$geo 	   = $ITEM->children($ns2["geo"]);
				$yweather2 = $ITEM->children($ns2["yweather"]);

				$this->latitude  = $geo->lat;
				$this->longitude = $geo->long;

				$this->item_link 		= $ITEM->link;
				$this->item_publicacao 	= $ITEM->pubDate;


				// CONDIÇÕES ATUAIS
				$this->condicao_atual 	 = $yweather2->condition->attributes()->text;
				$this->temperatura_atual = $yweather2->condition->attributes()->temp;
				$this->codigo_atual   	 = $yweather2->condition->attributes()->code;
				$this->data_atual 		 = $yweather2->condition->attributes()->date;
				$this->descricao_atual 	 = $ITEM->description;
				$this->imagem_url		 = "http://l.yimg.com/a/i/us/we/52/".$this->codigo_atual.".gif";


				/*** CONVERTER PARA CELSIUS ***/
				if ($this->escala=="C" AND $this->un_temperatura=="F") $this->temperatura_atual = self::converteCelcius($this->temperatura_atual);

				/* TRADUZIR CONDIÇÃO ATUAL */
				if ($this->idioma=="pt-BR") $this->condicao_atual = $this->conditions_code_BR[ intval($this->codigo_atual) ];

				// PRÓXIMOS DIAS (9 DIAS)
				foreach ($yweather2->forecast as $prox) {

					$this->prox_minima[] = $prox->attributes()->low;
					if ($this->escala=="C" AND $this->un_temperatura=="F") $this->prox_minima[] = self::converteCelcius($prox->attributes()->low);

					$this->prox_maxima[] = $prox->attributes()->high;
					if ($this->escala=="C" AND $this->un_temperatura=="F") $this->prox_maxima[] = self::converteCelcius($prox->attributes()->high);

					$this->prox_codigo[] = $prox->attributes()->code;
					$this->prox_imagem[] = "http://l.yimg.com/a/i/us/we/52/".$prox->attributes()->code.".gif";

					if ($this->idioma=="pt-BR") $this->prox_dia[] = str_ireplace($this->dias_EN, $this->dias_BR, $prox->attributes()->day);
					else 						$this->prox_dia[] = $prox->attributes()->day;

					if ($this->idioma=="pt-BR") $this->prox_data[] = str_ireplace($this->mes_EN, $this->mes_BR, $prox->attributes()->date);
					else 						$this->prox_data[] = $prox->attributes()->date;

					if ($this->idioma=="pt-BR") $this->prox_condicao[] = $this->conditions_code_BR[ intval($prox->attributes()->code) ];
					else 						$this->prox_condicao[] = $prox->attributes()->text;

				}

			}

		}

}