<?php

class Storage
{
    public static function session()
    {
        // Start a Session
        if (!session_id()) @session_start();
    }
}