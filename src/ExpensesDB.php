<?php

namespace ToshlImporter;

use SQLite3;

class ExpensesDB extends SQLite3
{
    function __construct()
    {
        $this->open('db.sqlite');
    }
}
