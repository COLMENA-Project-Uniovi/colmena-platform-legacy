<?php
/* 
Script realizado en Mayo de 2019 con objeto de preparar listados para el paper de SIGCSE. 
El objetivo de este escript es preparar un array donde saque todos los errores o warnings
que un usuario tuvo en cada sesión de una asignatuar. 

Para ello, prepara un array donde el indice es el user_id (recuperando todos los de la asignatura)
y lo va completando para cada sesión con los totales que corresponda.

NO se tiene en cuenta participación minima.
*/

require_once("functions.php");

$table_al_2015 = "colmena_marker_al_2015";
$table_al_2014 = "colmena_marker_al_2014";
$table_al_2013 = "colmena_marker_al_2013";
$table_mp_2015 = "colmena_marker_mp_2015";
$table_mp_2014 = "colmena_marker_mp_2014";
$table_mp_2013 = "colmena_marker_mp_2013";

$sessions_al_2015 = Array("61", "62", "63", "64", "65", "66", "67", "79", "80", "85", "86", "87", "88");
$names_al_2015 = Array("Vectores y ficheros", "Ordenación", "Divide y Vencerás", "Algoritmos Voraces", "Programación Dinámica", "Backtracking", "Ramifica y Poda", "Medidas de tiempos", "Tiempos de bucles", "Divide y Vencerás 2", "Algoritmos Voraces 2", "Backtracking 2", "Ramifica y Poda 2");

$sessions_al_2014 = Array("42", "43", "44", "45", "46", "47", "48");
$names_al_2014 = Array("Medidas de tiempos", "Ordenación", "Divide y Vencerás", "Algoritmos Voraces", "Programación Dinámica", "Backtracking", "Ramifica y Poda");

$sessions_al_2013 = Array("1", "2", "3", "4", "5", "6", "7");
$names_al_2013 = Array("Ordenación", "Medidas de tiempos", "Divide y vencerás", "Algoritmos Voraces", "Programación Dinámica", "Backtracking", "Ramifica y Poda");

$sessions_mp_2015 = Array("68", "69", "70", "71", "72", "73", "74", "75", "76", "77", "78", "81", "82", "89");
$names_mp_2015 = Array("IDE Eclipse", "Depuración y pruebas", "Herencia, polimorfismo", "Métodos abstractos, static", "Interfaces", "Herencia, polimorfismo e interfaces", "Pilas y Colas", "Estructuras Dinámicas", "Excepciones", "Ficheros", "Examen 2", "Simulacro Ex1 - Herencia e interfaces", "Control 1 - Herencia e interfaces", "Listas");

$sessions_mp_2014 = Array("49", "50", "51", "52", "53", "54", "55", "57", "58", "59", "60", "83");
$names_mp_2014 = Array("IDE Eclipse", "Depuración y pruebas", "Herencia, polimorfismo", "Métodos abstractos, interfaces", "Interfaces", "Herencia e interfaces", "Listas, Pilas y Colas", "Dynamic structures", "Excepciones", "Ficheros", "Examen 2", "Control 1 - Herencia e interfaces");

$sessions_mp_2013 = Array("8", "9", "10", "11", "13", "14", "15", "16", "17", "18");
$names_mp_2013 = Array("Listas", "Herencia, polimorfismo", "Listas Ordenadas", "Pilas y Colas", "Excepciones", "Estructuras dinámicas", "Ficheros", "Colas Concurrentes", "PrintNumbers (a2)", "Robot (a2)");

//parameters to change
$table = $table_mp_2013;
$sessions = $sessions_mp_2013;
$sessions_names = $names_mp_2013;
$type = "WARNING";


$users = initialize_users($table, $sessions);

foreach($sessions as $session_id){
	$session_totals = get_user_errors_section($table, $session_id, $type);

	foreach($session_totals as $st){
		$user_id = strtoupper($st['user_id']); 
		$users[$user_id][$session_id] = $st['total'];
	}
}

//print_r($users);



$keys = array_keys($users);
echo "<table>";

echo "<tr><td>Session Name</td>";
echo "<td>";
echo implode($sessions_names, "</td><td>");
echo "</td></tr>";

echo "<tr><td>User / Session ID</td>";
echo "<td>";
echo implode($sessions, "</td><td>");
echo "</td></tr>";

foreach ($keys as $k){
	echo "<tr>";
	echo "<td>". $k. "</td>";
	echo "<td>";
	echo implode($users[$k], "</td><td>");
	echo "</td>";
	echo "</tr>";
}
echo "</table>";


