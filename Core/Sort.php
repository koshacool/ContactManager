<?php
namespace Core;
class Sort
{
    /**
     *Defines options for sort data in SQL query
     *
     * @param array $array Array with data received from POST
     * @return array
     */
    public function sortTable($array = '')
    {
        if (!empty($array)) {
            //If validation is successful - save value, else take default value
            if (isset($array['mainSortColumn']) && ($array['mainSortColumn'] == 'last' || $array['mainSortColumn'] == 'first')) {
                $mainSortColumn = $array['mainSortColumn'];
            } else {
                $mainSortColumn = MAIN_COLUMN_FOR_SORT;
            }

            //If validation is successful - save value, else take default value
            if (isset($array['sortDirectionMainColumn']) && ($array['sortDirectionMainColumn'] == 'ASC' || $array['sortDirectionMainColumn'] == 'DESC')) {
                $sortDirectionMainColumn = $array['sortDirectionMainColumn'];
            } else {
                $sortDirectionMainColumn = MAIN_COLUMN_SORT_DIRECTION;
            }

            //If validation is successful - save value, else take default value
            if (isset($array['sortDirectionSecondaryColumn']) && ($array['sortDirectionSecondaryColumn'] == 'ASC' || $array['sortDirectionSecondaryColumn'] == 'DESC')) {
                $sortDirectionSecondaryColumn = $array['sortDirectionSecondaryColumn'];
            } else {
                $sortDirectionSecondaryColumn = SECONDARY_COLUMN_SORT_DIRECTION;
            }

            //Defines which page to display
            if (isset($array['showPage']) && !empty($array['showPage']) && is_numeric($array['showPage'])) {
                $currentPage = $array['showPage'];
            } else {
                if (isset($array['currentPage']) && !empty($array['currentPage']) && is_numeric($array['currentPage'])) {
                    $currentPage = $array['currentPage'];
                } else {
                    $currentPage = DEFAULT_PAGE;
                }
            }
            
            $offset = ($currentPage * ROWS_ON_PAGE) - ROWS_ON_PAGE;//Defines offset for SQL query

            //Defines main column and sort direction for SQL query
            if (isset($array['last']) || isset($array['first'])) {
                if (isset($array['last'])) {
                    if ($mainSortColumn == 'last') {
                        $secondarySortColumn = 'first';
                        if ($sortDirectionMainColumn == 'ASC') {
                            $sortDirectionMainColumn = 'DESC';
                        } else {
                            $sortDirectionMainColumn = 'ASC';
                        }
                    } else {
                        $mainSortColumn               = 'last';
                        $secondarySortColumn          = 'first';
                        $temporatySortDirection       = $sortDirectionSecondaryColumn;
                        $sortDirectionSecondaryColumn = $sortDirectionMainColumn;
                        $sortDirectionMainColumn      = $temporatySortDirection;
                    }
                } else {
                    if ($mainSortColumn == 'first') {
                        $secondarySortColumn = 'last';
                        if ($sortDirectionMainColumn == 'ASC') {
                            $sortDirectionMainColumn = 'DESC';
                        } else {
                            $sortDirectionMainColumn = 'ASC';
                        }
                    } else {
                        $mainSortColumn               = 'first';
                        $secondarySortColumn          = 'last';
                        $temporatySortDirection       = $sortDirectionSecondaryColumn;
                        $sortDirectionSecondaryColumn = $sortDirectionMainColumn;
                        $sortDirectionMainColumn      = $temporatySortDirection;
                    }
                }
            } else {
                if ($mainSortColumn == 'last') {
                    $secondarySortColumn = 'first';
                } else {
                    $secondarySortColumn = 'last';
                }
            }
        } else {
            //Default options for sort data in SQL query
            $mainSortColumn               = MAIN_COLUMN_FOR_SORT;
            $secondarySortColumn          = SECONDARY_COLUMN_FOR_SORT;
            $sortDirectionMainColumn      = MAIN_COLUMN_SORT_DIRECTION;
            $sortDirectionSecondaryColumn = SECONDARY_COLUMN_SORT_DIRECTION;
            $currentPage                  = DEFAULT_PAGE;
            $offset                       = OFFSET;
        }
        $arrayValues = compact('mainSortColumn', 'secondarySortColumn', 'sortDirectionMainColumn', 'sortDirectionSecondaryColumn', 'currentPage', 'offset');
        return $arrayValues;
    }

    /**
     *Defines images for display column sort direction
     *
     * @param string $sortDirectionMainColumn String with name main column for sort data in SQL query
     * @param string $sortDirectionSecondaryColumn String with name secondary column for sort data in SQL query
     * @return array
     */
    public function defineImageForSortingButton($sortDirectionMainColumn, $sortDirectionSecondaryColumn)
    {
        if ($sortDirectionMainColumn == 'ASC') {
            $mainColumnImage = "imageArrowUp";
        } else {
            $mainColumnImage = "imageArrowDown";
        }
        if ($sortDirectionSecondaryColumn == 'ASC') {
            $secondaryColumnImage = "imageArrowUp";
        } else {
            $secondaryColumnImage = "imageArrowDown";
        }
        $arrayValues = compact('mainColumnImage', 'secondaryColumnImage');
        return $arrayValues;
    }
}