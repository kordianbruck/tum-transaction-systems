<?php
//Use PHP 7.1.4
//Is using the directed graph lib: sudo pear install Structures_Graph - https://pear.php.net/package/Structures_Graph/download

$input = 'w 1 x r 2 x w 2 y r 3 y w 3 z r 1 z';

// ---- Do not edit ----
require_once 'Structures/Graph.php';
require_once 'Structures/Graph/Node.php';
require_once 'Structures/Graph/Manipulator/AcyclicTest.php';

$inputArr = explode(' ', $input);
$in = [];
$transactions = []; // All transactions
$nodes = []; // Transaction nodes in the graph
$graph = new Structures_Graph(true); //True for directed graph

//Build an array for each variable that we want to access
for ($i = 0; $i < count($inputArr); $i += 3) {
    $in[$inputArr[$i + 2]][] = ['o' => $inputArr[$i], 't' => intval($inputArr[$i + 1])];
    $transactions[] = $inputArr[$i + 1];
}

//create all nodes in graph - one per transaction
$transactions = array_unique($transactions);
foreach ($transactions as $t) {
    $nodes[$t] = new Structures_Graph_Node();
    $graph->addNode($nodes[$t]);
}

//Go through all operations for each var and add edges to the graph
$edges = [];
foreach ($in as $ops) {
    $edges[] = checkDependency($ops);
}

//Our magic to create the edges
function checkDependency($ops, $prev = null) {
    global $nodes;
    if ($prev === null) {
        $current = array_shift($ops);
        checkDependency($ops, $current);
    }
    if (count($ops) == 0) {
        return;
    }
    $current = array_shift($ops);
    if ($prev['o'] === 'w' && $current['o'] === 'r') {
        $nodes[$prev['t']]->connectTo($nodes[$current['t']]);
    } elseif ($prev['o'] === 'r' && $current['o'] === 'w') {
        $nodes[$current['t']]->connectTo($nodes[$prev['t']]);
    }
    checkDependency($ops, $current);
}

//Use the manipulator by the lib to finish this task
$t = new Structures_Graph_Manipulator_AcyclicTest();
var_dump($t->isAcyclic($graph));