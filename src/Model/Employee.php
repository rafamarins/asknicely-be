<?php

namespace App\Model;

class Employee extends DataObject {
    protected $db = [
        'CompanyName' => 'Varchar(255)',
        'EmployeeName' => 'Varchar(255)',
        'Email' => 'Varchar(255)',
        'Salary' => 'Int'
    ];
}