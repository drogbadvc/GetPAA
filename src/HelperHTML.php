<?php

class HelperHTML
{
    /**
     * @param array $records
     */
    public static function tdRecords(array $records)
    {
        $i = 1;
        foreach ($records as $ppa) {
            foreach ($ppa as $value) {
                $id = $i++;
                echo "<tr><td>$id</td><td>$value</td></tr>";
            }
        }
    }
}