<?php

class ParamsRequest
{
    /**
     * @param array $method
     * @param string $field
     * @param bool $diff
     * @return bool
     */
    public static function methodIsset(array $method = [], string $field, bool $diff = false): bool
    {
        return $diff ? !isset($method[$field]) : isset($method[$field]);
    }

    /**
     * @param array $method
     * @param string $field
     * @param bool $diff
     * @return bool
     */
    public static function methodEmpty(array $method = [], string $field, bool $diff = false): bool
    {
        return $diff ? !empty($method[$field]) : empty($method[$field]);
    }

    /**
     * @param string $field
     * @return mixed
     */
    public static function post(string $field)
    {
        return $_POST[$field];
    }

    /**
     * @param string $field
     * @return mixed
     */
    public static function get(string $field)
    {
        return $_GET[$field];
    }
}