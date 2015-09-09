<?php
 $var = $_GET;
 
 //this block is to receive the GCM regId from external (mobile apps)
 if(isset($_GET["op"])) {
	switch(strtolower($_GET["op"])) {
		case 'regid':
			escrever("GCMRegId.txt",$_GET['cmd']);
			break;
		default:
			escrever("other.txt", date('d/m/Y H:i:s') . ' - ' . $_GET["op"] . ':' . $_GET['cmd']);
			break;
	}
 }

$var['status'] = 'ok';
if(isset($var['callback'])) {
	echo $var['callback'] . '(' . json_encode($var) . ')';
} else {
	echo json_encode($var);
}

function escrever($arquivo, $texto) {
	$f=fopen($arquivo,"a+",0);
	$linha=$texto."\n";
	fwrite($f,$linha,strlen($linha));
	fclose($f);
}