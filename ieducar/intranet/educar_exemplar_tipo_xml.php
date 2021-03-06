<?php
/* * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * *
	*																	     *
	*	@author Prefeitura Municipal de Itaja�								 *
	*	@updated 29/03/2007													 *
	*   Pacote: i-PLB Software P�blico Livre e Brasileiro					 *
	*																		 *
	*	Copyright (C) 2006	PMI - Prefeitura Municipal de Itaja�			 *
	*						ctima@itajai.sc.gov.br					    	 *
	*																		 *
	*	Este  programa  �  software livre, voc� pode redistribu�-lo e/ou	 *
	*	modific�-lo sob os termos da Licen�a P�blica Geral GNU, conforme	 *
	*	publicada pela Free  Software  Foundation,  tanto  a vers�o 2 da	 *
	*	Licen�a   como  (a  seu  crit�rio)  qualquer  vers�o  mais  nova.	 *
	*																		 *
	*	Este programa  � distribu�do na expectativa de ser �til, mas SEM	 *
	*	QUALQUER GARANTIA. Sem mesmo a garantia impl�cita de COMERCIALI-	 *
	*	ZA��O  ou  de ADEQUA��O A QUALQUER PROP�SITO EM PARTICULAR. Con-	 *
	*	sulte  a  Licen�a  P�blica  Geral  GNU para obter mais detalhes.	 *
	*																		 *
	*	Voc�  deve  ter  recebido uma c�pia da Licen�a P�blica Geral GNU	 *
	*	junto  com  este  programa. Se n�o, escreva para a Free Software	 *
	*	Foundation,  Inc.,  59  Temple  Place,  Suite  330,  Boston,  MA	 *
	*	02111-1307, USA.													 *
	*																		 *
	* * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * */
	header( 'Content-type: text/xml' );

	require_once( "include/clsBanco.inc.php" );
	require_once( "include/funcoes.inc.php" );
	echo "<?xml version=\"1.0\" encoding=\"ISO-8859-15\"?>\n<query xmlns=\"sugestoes\">\n";

	$cliente_tipo = "";
	if( is_numeric($_GET['cod_tipo_cliente']))
	{
		$cliente_tipo = "AND ref_cod_cliente_tipo = '{$_GET['cod_tipo_cliente']}'";
	}
//			, pmieducar.cliente_tipo ct
	
	if( is_numeric( $_GET["bib"] ) )
	{
		$db = new clsBanco();
		$db2 = new clsBanco();
		/*$db->Consulta( "
		SELECT
			cod_exemplar_tipo
			, nm_tipo
			, dias_emprestimo
		FROM
			pmieducar.exemplar_tipo LEFT OUTER JOIN pmieducar.cliente_tipo_exemplar_tipo ON ( cod_exemplar_tipo = ref_cod_exemplar_tipo )
		WHERE
			et.ativo = 1
			AND et.ref_cod_biblioteca = '{$_GET["bib"]}'
		ORDER BY
			nm_tipo ASC
		");*/
		$db->Consulta("SELECT 
							DISTINCT cod_exemplar_tipo
						FROM
							pmieducar.exemplar_tipo
						WHERE
						    ativo = '1'
						AND
							ref_cod_biblioteca = '{$_GET['bib']}'
					");

		if ($db->numLinhas())
		{
			while ( $db->ProximoRegistro() )
			{
				list($cod) = $db->Tupla();
				$nome = $db2->CampoUnico("SELECT nm_tipo FROM pmieducar.cliente_tipo_exemplar_tipo, pmieducar.exemplar_tipo WHERE ativo = '1' AND ref_cod_biblioteca = '{$_GET['bib']}' AND cod_exemplar_tipo = '$cod'");
				$dias_emprestimo = $db2->CampoUnico("SELECT dias_emprestimo FROM pmieducar.cliente_tipo_exemplar_tipo, pmieducar.exemplar_tipo WHERE ativo = '1' AND cod_exemplar_tipo = ref_cod_exemplar_tipo AND ref_cod_biblioteca = '{$_GET['bib']}' AND cod_exemplar_tipo = '$cod' $cliente_tipo");
				echo "	<exemplar_tipo cod_exemplar_tipo=\"{$cod}\" dias_emprestimo=\"{$dias_emprestimo}\">{$nome}</exemplar_tipo>\n";
			}
		}
	}
	echo "</query>";
?>