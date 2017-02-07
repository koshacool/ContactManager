<?php
namespace Core;
use Helper, Models;

class Model
{
    protected $query;
    protected $varDump;

    function __construct()
    {	
        $this->query   = new SqlQuery();//Get object for do sqlQuery
        $this->varDump = new Helper\VarDump();//Get object for display variables        
    }
   
}