<?php

date_default_timezone_set('America/Argentina/Cordoba');

require 'vendor/autoload.php';

use ToshlImporter\Expense;
use ToshlImporter\ExpensesDB;

function csv_to_array($filename='', $delimiter=',')
{
    $header = null;
    $data = array();
    $i = 0;
    if (($handle = fopen($filename, 'r')) !== FALSE) {
        while (($row = fgetcsv($handle)) !== FALSE) {
            if(!$header)
                $header = $row;
            else
                $data[] = array_combine($header, $row);
            $i++;
        }
        fclose($handle);
    }
    return $data;
}

$expenses_payload = csv_to_array('expenses.csv');
$expenses = [];

foreach($expenses_payload as $expense_payload) {
    $expense = new Expense(
        DateTime::createFromFormat('Y-m-d', $expense_payload['date']),
        array_map(
            'strtolower',
            explode(' ', $expense_payload['tag'])
        ),
        floatval($expense_payload['amount'])
    );
    array_push($expenses, $expense);
//    echo $expense->getInsertStatement() . "\n";
}

unset($expenses_payload);
unset($expense_payload);

$database = new ExpensesDB;

foreach($expenses as $expense) {
    if($expense->amount > 0) {
        $database->exec(
            sprintf("INSERT INTO expenses %s", $expense->getInsertStatement())
        );
        echo ".";
    }
}
