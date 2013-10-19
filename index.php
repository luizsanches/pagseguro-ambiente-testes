<?php
ini_set('display_errors','On');
error_reporting(E_ALL);

include 'PagSeguroServer.php';
$server = new PagSeguroServer();
$data = $server->loadState();
$notification = $server->loadNotification();
?>
<html>
	<head>
		<title>PagSeguro - Ambiente de Testes</title>
		<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
		<link rel="stylesheet" type="text/css" href="style.css">
	</head>
	<body>
		<div id="wrap">
			<div id="main">
				<div id="header">Ambiente de Testes PagSeguro <span id="header-github"><a href="https://github.com/bcarneiro/pagseguro-ambiente-testes">https://github.com/bcarneiro/pagseguro-ambiente-testes</a></span></div>
				<div>
				<?php if ($data) { ?>
					<h3>Par�metros Recebidos</h3>
					<ul>
					<?php
					foreach($data as $key => $value) {
						echo "<li>$key = '$value'</li>";
					}
					?>
					<p class="warning">
					<?php if (!$server->isDataConsistent()) { ?>
					Aten��o: os seguintes param�tros s�o obrigat�rios mas n�o foram enviados: <?php echo $server->getMissingParameters(); ?>
					<?php } ?>
					</p>
					</ul>
					<?php if ($notification) { ?>
					<h3 class="yellow">Notifica��o enviada</h3>
					<p>Notifica��o enviada para <a href="<?php echo $server->getNotificationUrl(); ?>"><?php echo $server->getNotificationUrl(); ?></a>. Dados enviados:</p>
					<ul>
						<li>notificationCode: <?php echo $notification['notificationCode']; ?></li>
						<li>notificationType: <?php echo $notification['notificationType']; ?></li>
					</ul>
					<p>Resposta do seu servidor:</p>
					<div id="notification-response"><?php echo $notification['response']; ?></div>
					<p>Para ler a notifica��o envie um pedido GET para o endere�o <a href="http://<?php echo $server->getCurrentHost(); ?>/notifications.php"><?php echo $server->getCurrentHost(); ?>/notifications.php</a> com os campos notificationCode, email (ignorado) e token (ignorado).</p>
					<p>Exemplo: <a href="http://<?php echo $server->getCurrentHost(); ?>/notifications.php?notificationCode=<?php echo $notification['notificationCode']; ?>&email=teste@test.com&token=MEU-TOKEN-FALSO"><?php echo $server->getCurrentHost(); ?>/notifications.php?notificationCode=<?php echo $notification['notificationCode']; ?>&email=teste@test.com&token=MEU-TOKEN-FALSO</a></p><br>
					<p>Dados da transa��o:</p>
					<ul>
						<li>Status: <?php echo $notification['transactionStatus']; ?> (<?php echo $notification['transactionTextStatus']; ?>)</li>
					</ul>
					<?php } ?>
					<h3>Enviar nova Notifica��o</h3>
					<p>Uma notifica��o ser� enviada para o endere�o <a href="<?php echo $server->getNotificationUrl(); ?>"><?php echo $server->getNotificationUrl(); ?></a>.</p>
					<form method="post" action="notifications.php">
						<p>
							<label>Status:</label>
							<select name="notificationStatus">
								<option value="1">Aguardando pagamento</option>
								<option value="2">Em an�lise</option>
								<option value="3">Paga</option>
								<option value="4">Dispon�vel</option>
								<option value="5">Em disputa</option>
								<option value="6">Devolvida</option>
								<option value="7">Cancelada</option>
							</select>
						</p>
						<p>
							<label>Status:</label>
							<select name="notificationType">
								<option value="transaction">transaction</option>
							</select>
						</p>
						<p>
							<input type="submit" value="Enviar">
						</p>
					</form>
					<h3 class="red">Apagar dados e notifica��o</h3>
					<p>Ir� apagar os dados guardados como os param�tros de ordem e notifica��es.</p>
					<form method="post" action="wipe.php">
						<p>
							<input type="submit" value="Limpar todos os dados">
						</p>
					</form>
				<?php } else { ?>
					<h2>Tutorial - pagseguro-ambiente-testes</h2>
					<p>Este software tem o objetivo de auxiliar o desenvolvedor a testar sua implementa��o PagSeguro de forma pr�tica. � poss�vel enviar o seu carrinho de compras do PagSeguro e simular o sistema de notifica��es. Atualmente, o <a href="http://blogpagseguro.com.br/2012/05/testando-o-recebimento-de-notificacoes/">jeito atual recomendado pelo PagSeguro</a> � que se crie um vendedor falso e que ent�o que o desenvolvedor compre manualmente produtos, via boleto ou cart�o de cr�dito, o que se torna impr�tico na maioria das vezes. Este m�todo impede o desenvolvedor de usar sua m�quina local para testar o sistema de notifica��es, pois obviamente n�o � poss�vel enviar uma notifica��o para um endere�o local como 127.0.0.1. Al�m disso n�o � poss�vel simular uma venda bem sucedida (a n�o ser que voc� realmente compre o produto). Com este sistema voc� conseguir� simular todos os tipos de notifica��es do PagSeguro de forma r�pida e pr�tica.</p>
					<br>
					<p>Para iniciar o ambiente de testes � necess�rio que voc� primeiro envie os dados do seu carrinho de compras. Portanto em vez de enviar os dados para o PagSeguro, voc� enviar� para est� pagina <a href="http://<?php echo $server->getCurrentHost(); ?>/checkout.php"><?php echo $server->getCurrentHost(); ?>/checkout.php</a>. Mais informa��es de quais dados s�o esperados e como funciona o carrinho de compras do PagSeguro voc� poder ler <a href="https://pagseguro.uol.com.br/desenvolvedor/carrinho_proprio.jhtml#rmcl">aqui</a>. N�o se esque�a tamb�m de alterar o arquivo PagSeguroServer.php e configurar as vari�veis $notification_domain e $notification_page (seu endere�o para receber notifica��es).</p>
					<br>
					<p>Exemplo: <a href="http://<?php echo $server->getCurrentHost(); ?>/checkout.php?tipo=CP&moeda=BRL&email_cobranca=turm@test.com&item_id_1=1&item_descr_1=Computador%20bacana&item_quant_1=1&item_valor_1=100.00&item_id_2=2&item_descr_2=Mais%20um%20computador&item_quant_2=2&item_valor_2=150.00"><?php echo $server->getCurrentHost(); ?>/checkout.php?tipo=CP&moeda=BRL&ema...&item_valor_2=150.00</a></p><br>
					<p>Tem alguma d�vida, problema ou sugest�o? Quer contribuir? Estamos no Github, envie seu feedback para <a href="https://github.com/bcarneiro/pagseguro-ambiente-testes">pagseguro-ambiente-testes</a>!</p>

					<h3>Links para documenta��o</h3>
					<ul>
						<li><a href="https://github.com/bcarneiro/pagseguro-ambiente-testes">pagseguro-ambiente-testes</a></li>
						<li><a href="https://pagseguro.uol.com.br/desenvolvedor/carrinho_proprio.jhtml#rmcl">Carrinho de Compras do PagSeguro</a></li>
						<li><a href="https://pagseguro.uol.com.br/v2/guia-de-integracao/api-de-notificacoes.html">API de Notifica��es do PagSeguro</a></li>
					</ul>
				<?php } ?>
				</div>
			</div>
		</div>

		<div id="footer">
			<span>Este software � gratuito e n�o est� associado com o PagSeguro. PagSeguro � uma marca registrada da empresa UOL. Este ambiente de testes n�o � afiliado com a UOL e portanto n�o � um produto oficial do PagSeguro.</span>
		</div>
	</body>
</html>
