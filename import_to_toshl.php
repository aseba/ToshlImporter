<?php

require "vendor/autoload.php";

use GuzzleHttp\Client;
use GuzzleHttp\Psr7\Request;
use ToshlImporter\Expense;
use ToshlImporter\ExpensesDB;

$client = new Client([
    // Base URI is used with relative requests
    'base_uri' => 'https://api.toshl.com',
]);

$token = '732fb6f8-4ea4-411f-b663-575fd9e01fa71470a18792ed4b0a7dc4034226df8e0d';

function getAllExpenses() {
    $database = new ExpensesDB;
    $results = $database->query('SELECT * FROM expenses');
    $expenses = [];
    while ($row = $results->fetchArray()) {
        array_push($expenses, $row);
    }
    return $expenses;
}

function getRemaningExpenses() {
    $database = new ExpensesDB;
    $results = $database->query('SELECT * FROM expenses WHERE imported = 0');
    $expenses = [];
    while ($row = $results->fetchArray()) {
        array_push($expenses, $row);
    }
    return $expenses;
}

function setExpenseAsImported($id) {
    $database = new ExpensesDB;
    $database->exec(sprintf("UPDATE expenses SET imported = 1 WHERE id = %s", $id));
}

$expenses = getRemaningExpenses();
$count = 0;
foreach($expenses as $expense) {
    $payload = [
        'headers' => ['Authorization' => sprintf('Bearer %s', $token)],
        'form_params' => [
            "currency" => "ARS",
            "amount" => $expense['amount'],
            "date" => $expense['date'],
            "tags" => explode(',', $expense['tags']),
            "extra" => ['imported'=>true]
        ]
    ];
    try {
        $response = $client->request(
            'POST',
            'expenses',
            $payload
        );
        if($response->getStatusCode() == 200 or $response->getStatusCode() == 201) {
            setExpenseAsImported($expense['id']);
            $count++;
        }
        echo ".";
    } catch (Exception $e) {
        var_dump($expense);
        echo sprintf("\nStoped after %d with %s", $count, $e->getmessage());
        exit;
    }
}
