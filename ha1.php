<?php
//Use PHP 7.1.4
//Is using the directed graph lib: sudo pear install Structures_Graph - https://pear.php.net/package/Structures_Graph/download

$input = 'w 1 x r 2 x w 2 y r 3 y w 3 z r 1 z';
//$input = 'r 1 x w 1 y r 2 y w 2 x';

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
    $in[] = ['o' => $inputArr[$i], 't' => intval($inputArr[$i + 1]), 'v' => $inputArr[$i + 2]];
    $transactions[] = $inputArr[$i + 1];
}

//create all nodes in graph - one per transaction
$transactions = array_unique($transactions);
foreach ($transactions as $t) {
    $nodes[$t] = new Structures_Graph_Node();
    $graph->addNode($nodes[$t]);
}

//Check that if two operations are in conflict
function check($curr, $next) {
    if($curr['t'] === $next['t']) {
        return false;
    }
    if($curr['v'] !== $next['v']) {
        return false;
    }
    if($curr['o'] === 'r' && $next['o'] === 'r' ) {
        return false;
    }
    return true;
}

//Go through all operations for each var and add edges to the graph
for ($i = 0; $i < count($in); $i++) {
    for ($j = $i + 1; $j < count($in); $j++) {
        if(check($in[$i], $in[$j])) {
            $nodes[$in[$i]['t']]->connectTo($nodes[$in[$j]['t']]);
        }
    }
}

//Use the manipulator by the lib to finish this task
$t = new Structures_Graph_Manipulator_AcyclicTest();
var_dump($t->isAcyclic($graph));