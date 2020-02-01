<?php

class HelperCharacters
{
    /**
     * @param string $value
     * @return string
     */
    public static function escape(string $value): string
    {
        return htmlspecialchars(strip_tags($value));
    }
}