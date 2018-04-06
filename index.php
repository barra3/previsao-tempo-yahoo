<?php


	require_once('tempo.class.php');
	$Tempo = new PrevisaoTempo();
	$Tempo->setLocalidade('455827');
	$Tempo->setEscala('C');
	$Tempo->setIdioma('pt-BR');
	$Tempo->getPrevisao();

	echo '<div class="img"><img src="'.$Tempo->imagem_url.'"></div>';
	echo '<div class="dados">';
		echo '<strong>'.$Tempo->cidade.'</strong>';
		echo '<br>'.$Tempo->temperatura_atual.' °'.$Tempo->escala;
		echo '<br><span>'.$Tempo->condicao_atual;
		echo '<br>Vento:'.$Tempo->vento_velocidade.' '.$Tempo->un_velocidade.'</span>';
	echo '</div>';


	for ($i=1; $i<=9; $i++) {

		echo '<div style="margin-bottom:50px;">';
			echo '<div class="img"><img src="'.$Tempo->prox_imagem[$i].'"></div>';
				echo '<br>Min: '.$Tempo->prox_minima[$i].' - Max: '.$Tempo->prox_maxima[$i]. ' '.$Tempo->escala;
				echo '<br><span>'.$Tempo->prox_condicao[$i];
				echo '<br>Data'.$Tempo->prox_data[$i];
		echo '</div>';

	}
