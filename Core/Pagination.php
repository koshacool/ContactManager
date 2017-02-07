<?php
namespace Core;
class Pagination extends Model
{
    /**
     *Defines first and last page for pagination
     *
     * @param integer $currentPage Page which use user
     * @param integer $numberOfPages All available pages
     * @return array
     */
    public function pagination($currentPage, $numberOfPages)
    {
        if ($numberOfPages > 1) {
            if ($numberOfPages < NUMBER_DISPLAYED_PAGES_LINKS) {
                $firstShowPage = 1;
                $lastShowPage  = $numberOfPages;
            } else {
                if ($currentPage == 1) {
                    $firstShowPage = $currentPage;
                    $lastShowPage  = $firstShowPage + (NUMBER_DISPLAYED_PAGES_LINKS - 1);
                } elseif ($currentPage == $numberOfPages) {
                    $firstShowPage = $numberOfPages - (NUMBER_DISPLAYED_PAGES_LINKS - 1);
                    $lastShowPage  = $numberOfPages;
                } else {
                    if (($numberOfPages - $currentPage) < NUMBER_DISPLAYED_PAGES_LINKS - 1) {
                        $firstShowPage = $currentPage - (NUMBER_DISPLAYED_PAGES_LINKS - (($numberOfPages - $currentPage) + 1));
                        $lastShowPage  = $numberOfPages;
                    } else {
                        $firstShowPage = $currentPage;
                        $lastShowPage  = $firstShowPage + (NUMBER_DISPLAYED_PAGES_LINKS - 1);
                    }
                }
            }
        } else {
            $firstShowPage = 1;
            $lastShowPage = 1;
        }

        return compact('firstShowPage', 'lastShowPage');
    }

    /**
     *Count all available pages
     *
     * @param $sqlQueryResult  Result SQL query
     * @return integer
     */
    public function countPages($userId)
    {
        //Find out number of rows for pagination
        $selectNumRows = $this->query->sqlQuerySelect(array(
            'arrayTableName' => array('tableName' => 'contact'),
            'arrayWhat'      => array('count' => 'count(*)'),
            'arrayWhere'     => array('user_id' => $userId)));
                    
        $array         = mysqli_fetch_row($selectNumRows);//Save data from query result to array        
        $numberRows    = $array[0];//save data from array to value        
        $numberOfPages = ceil($numberRows / ROWS_ON_PAGE);//Defines number of pages and round it in a big way

        return $numberOfPages;
    }
}