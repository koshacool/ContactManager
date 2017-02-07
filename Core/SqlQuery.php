<?php
namespace Core;
use Helper;

class SqlQuery
{


    private static function connect()
    {
        //try to connect to DataBase
        try {            
            @$connect = mysqli_connect(HOST, USER, PASSWORD, DB);//try to connect            
            if (!$connect) {
                throw new \Exception("No connect to DB.");//if not connect catch exeption
            }
        }
        //catch exeption and display error message
        catch (\Exception $e) {
            exit('Error: ' . $e->getMessage());
        }
        return $connect;
    }

    /**
     *Makes JOIN and ON parameters for SQL query
     *
     * @param array $arrJoin Array with tables names
     * @param array $arrOn Array with parameters for join tables
     * @return string
     */
    private static function joinToString($arrJoin, $arrOn)
    {
        $join = '';
        if (!empty($arrJoin) && !empty($arrOn)) {
            foreach ($arrJoin as $key => $value) {
                $joinString = ' INNER JOIN ' . $value . ' AS ' . $key;
                $joinArr[] = $joinString;
            }
            foreach ($arrOn as $key => $value) {
                $on = ' ON ' . $key . '=' . $value;
                $onArr[] = $on;
            }
            for ($i = 0; $i < count($joinArr); $i++) {
                $join .= $joinArr[$i] . $onArr[$i];
            }
        }
        return $join;
    }

    /**
     *Makes WHERE options for SQL query
     *
     * @param array $arrWhere Array with parameters for SQL query
     * @return string
     */
    private static function whereToString($arrWhere)
    {
        $where = '';
        if (!empty($arrWhere)) {
            foreach ($arrWhere as $key => $value) {
                if (empty($where)) {
                    if ($value == NULL || $value == '') {
                        $where = 'WHERE ' . $key . ' IS NULL';
                    } else {
                        $where = 'WHERE ' . $key . '=' . "'" . $value . "'";
                    }
                } else {
                    if ($value == NULL || $value == '') {
                        $where .= ' AND ' . $key . ' IS NULL';
                    } else {
                        $where .= ' AND ' . $key . '=' . "'" . $value . "'";
                    }

                }
            }
        }
        return $where;
    }

    /**
     *Makes sort options for SQL query
     *
     * @param array $arrOrderBy Array with parameters for SQL query
     * @return string
     */
    private static function orderToString($arrOrderBy)
    {
        $order = '';
        if (!empty($arrOrderBy)) {
            foreach ($arrOrderBy as $key => $value) {
                if (empty($order)) {
                    $order = 'ORDER BY ' . $order . $key . ' ' . $value;
                } else {
                    $order .= ', ' . $key . ' ' . $value;
                }
            }
        }
        return $order;
    }

    /**
     *Makes options for constrain the number of rows returned in SQL query
     *
     * @param array $arrLimit Array with parameters for SQL query
     * @return string
     */
    private static function limitToString($arrLimit)
    {
        $limit = '';
        if (!empty($arrLimit)) {
            foreach ($arrLimit as $offcet => $rows) {
                if (!empty($offcet) && !empty($rows)) {
                    $limit = 'LIMIT ' . $offcet . ', ' . $rows;
                } elseif (empty($offcet) && !empty($rows)) {
                    $limit = 'LIMIT ' . $rows;
                }
            }
        }
        return $limit;
    }

    /**
     *Do insert SQL query
     *
     * @param $connect Connect to DataBase
     * @param array $array Array with all parameters for SQL query
     * @return string
     */
    public function sqlQueryInsert($array)
    {

        $what = '';
        $sql  = 'INSERT INTO ';
        if (isset($array['arrayTableName']) && !empty($array['arrayTableName'])) {
            $sql = $sql . $array['arrayTableName']['tableName'];
        }
        if (isset($array['arrayWhat']) && !empty($array['arrayWhat'])) {
            foreach ($array['arrayWhat'] as $key => $val) {
                if (empty($what)) {
                    $what = $key;
                    if ($val == NULL || $val == '') {
                        $value = 'NULL';
                    } else {
                        $value = "'" . $val . "'";
                    }
                } else {
                    $what .= ', ' . $key;
                    if ($val == NULL || $val == '') {
                        $value .= ", " . 'NULL';
                    } else {
                        $value .= ", '" . $val . "'";

                    }
                }
            }
            $sql .= ' (' . $what . ') VALUES (' . $value . ')';
        }
        $connect     = self::connect();
        $resultQuery = self::doSQLQuery($connect, $sql);
        $queryId     = mysqli_insert_id($connect);
        return $queryId;
    }

    /**
     *Do delete SQL query
     *
     * @param $connect Connect to DataBase
     * @param array $array Array with all parameters for SQL query
     * @return string
     */
    public function sqlQueryDelete($array)
    {
        $what = '';
        $sql  = 'DELETE FROM ';
        if (isset($array['arrayWhat']) && !empty($array['arrayWhat'])) {
            foreach ($array['arrayWhat'] as $key => $value) {
                if (empty($what)) {
                    $what = "'" . $value . "'";
                } else {
                    $what .= ', ' . $value . "'";
                }
            }
            $sql .= ' ' . $what;
        }
        if (isset($array['arrayTableName']) && !empty($array['arrayTableName'])) {
            $sql .= $array['arrayTableName']['tableName'];
        }
        if (isset($array['arrayWhere']) && !empty($array['arrayWhere'])) {
            $where = self::whereToString($array['arrayWhere']);
            $sql .= ' ' . $where;
        }
        $resultQuery = self::doSQLQuery(self::connect(), $sql);
        return $resultQuery;
    }

    /**
     *Do update SQL query
     *
     * @param $connect Connect to DataBase
     * @param array $array Array with all parameters for SQL query
     * @return string
     */
    public function sqlQueryUpdate($array)
    {
        $what = '';
        $sql  = 'UPDATE ';
        if (isset($array['arrayTableName']) && !empty($array['arrayTableName'])) {
            $sql .= $array['arrayTableName']['tableName'] . ' SET';
        }
        if (isset($array['arrayWhat']) && !empty($array['arrayWhat'])) {
            foreach ($array['arrayWhat'] as $key => $value) {
                if (empty($what)) {
                    $what = $key . " ='" . $value . "'";
                } else {
                    $what .= ', ' . $key . "='" . $value . "'";
                }
            }
            $sql .= ' ' . $what;
        }
        if (isset($array['arrayWhere']) && !empty($array['arrayWhere'])) {
            $where = self::whereToString($array['arrayWhere']);
            $sql .= ' ' . $where;
        }

        $resultQuery = self::doSQLQuery(self::connect(), $sql);
        return $resultQuery;
    }


    /**
     *Do select SQL query
     *
     * @param $connect Connect to DataBase
     * @param array $array Array with all parameters for SQL query
     * @return string
     */
    public function sqlQuerySelect($array)
    {
        $what = '';
        $sql  = 'SELECT ';
        if (isset($array['arrayWhat']) && !empty($array['arrayWhat'])) {
            if (isset($array['arrayWhat']['selectAll'])) {
                $what = '*';
            } elseif (isset($array['arrayWhat']['count'])) {
                $what = 'count(*)';
            } else {
                foreach ($array['arrayWhat'] as $key => $value) {
                    if (empty($what)) {
                        $what = $key . ' AS ' . $value;
                    } else {
                        $what .= ', ' . $key . ' AS ' . $value;
                    }
                }
            }
            $sql .= $what;
        }
        if (isset($array['arrayTableName']) && !empty($array['arrayTableName'])) {
            $sql .= ' FROM ' . $array['arrayTableName']['tableName'];
        }
        if (isset($array['arrayJoin']) && !empty($array['arrayJoin'])) {
            $join = self::joinToString($array['arrayJoin'], $array['arrayOn']);
            $sql .= ' ' . $join;
        }
        if (isset($array['arrayWhere']) && !empty($array['arrayWhere'])) {
            $where = self::whereToString($array['arrayWhere']);
            $sql .= ' ' . $where;
        }
        if (isset($array['arrayOrderBy']) && !empty($array['arrayOrderBy'])) {
            $orderBy = self::orderToString($array['arrayOrderBy']);
            $sql .= ' ' . $orderBy;
        }
        if (isset($array['arrayLimit']) && !empty($array['arrayLimit'])) {
            $limit = self::limitToString($array['arrayLimit']);
            $sql .= ' ' . $limit;
        }
        $resultQuery = self::doSQLQuery(self::connect(), $sql);
        return $resultQuery;
    }

    /**
     *Executes SQL query
     *
     *If can't executes query, catch exeption
     *stop script and display error message
     *
     * @param $connect Connect to DataBase
     * @param string $sqlQuery Array with all parameters for SQL query
     * @return object
     */
    private static function doSQLQuery($connect, $sqlQuery)
    {
        self::displaySQLQuery($sqlQuery);
        try {
            $resultQuery = mysqli_query($connect, $sqlQuery);
            if (!$resultQuery) {
                throw new \Exception("Bad query. " . mysqli_error($connect));
            }
        } catch (Exception $e) {
            exit('Error: ' . $e->getMessage());
        }
        return $resultQuery;
    }

    /**
     *Display SQL query
     *
     * @param string $sqlQuery SQL query string
     * @return void
     */
    private static function displaySQLQuery($value)
    {
        if (DISPLAY_SQL_QUERY) {
            $varDump = new Helper\VarDump();
            $varDump->show($value);
        }
    }

}
