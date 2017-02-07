<?php
namespace Helper;

class VarDump
{
    /**
     *Display information about a variable
     *
     *Function display information about a variable
     *if constant VAR_DUMP_BREAKER
     *is equal boolean 'TRUE'
     * @param  $value Any data
     * @return void
     */
    public function show($value)
    {
        if (VAR_DUMP_BREAKER) {
            echo '<pre>';
            var_dump($value);
            echo '</pre>';
        }
    }
}
