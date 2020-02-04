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

    /**
     * @param string $value
     */
    public static function selected(string $value = '', string $search = '')
    {
        if ($value === 'en') {
            $valueUppercase = 'EN (US)';
        } else {
            $valueUppercase = mb_strtoupper($value);
        }

        if ($value === $search) {
            echo "<option value='$value' selected>$valueUppercase</option>";
        } else {
            echo "<option value='$value'>$valueUppercase</option>";
        }
    }
}