<?php

function qcache($database, $key, $table, $sql, $ot){

$m = new Memcached();
$m->addServer('localhost', 11211);

$bt = $database->select($table, $sql, $ot);
if (!($bt = $m->get($key))) {
    if ($m->getResultCode() == Memcached::RES_NOTFOUND) {
	$bt = $database->select($table, $sql, $ot);
        $m->set($key, $bt, 120);
    } else {
	$bt = $database->select($table, $sql, $ot);
    }
}


return $bt;
}
