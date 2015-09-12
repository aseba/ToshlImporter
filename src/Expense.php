<?php

namespace ToshlImporter;

use DateTime;

class Expense {
    function __construct(DateTime $date, Array $tags, $amount) {
        $this->id = hexdec(uniqid());
        $this->date = $date;
        $this->tags = $tags;
        $this->amount = $amount;
        $this->currency = 'ARS';
    }

    function getInsertStatement() {
        return vsprintf(
            "(id, date, tags, amount, imported) VALUES (%s, '%s', '%s', %s, 0)",
            [
                $this->id,
                $this->date->format('Y-m-d'),
                implode($this->tags, ','),
                $this->amount
            ]
        );
    }
}
