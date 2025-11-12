<?php
$DB_HOST = getenv('DB_HOST') ?: 'localhost';
$DB_NAME = getenv('DB_NAME') ?: 'minimundos';
$DB_USER = getenv('DB_USER') ?: 'root';
$DB_PASS = getenv('DB_PASS') ?: '';

$TABLE = 'hospital';
$COLUMNS = [
  ['name' => 'nome', 'label' => 'Hospital', 'type' => 'text'],
  ['name' => 'leitos', 'label' => 'Nº de Leitos', 'type' => 'number'],
  ['name' => 'atendimentos', 'label' => 'Nº de Atendimentos', 'type' => 'number'],
  ['name' => 'obitos', 'label' => 'Nº de Óbitos', 'type' => 'number']
];
