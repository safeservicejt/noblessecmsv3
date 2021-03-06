<?php

class Session
{

    public static function get($sessionName = '',$defaultValue=false)
    {
        $resultData = $defaultValue;
        
        if (!preg_match('/^(\w+)\.(\w+)$/i', $sessionName, $matches)) {
            $resultData = isset($_SESSION["$sessionName"]) ? $_SESSION["$sessionName"] : $defaultValue;
  
        } else {

            $sessionMain = $matches[1];

            $sessionChild = $matches[2];

            $resultData = isset($_SESSION["$sessionMain"]["$sessionChild"]) ? $_SESSION["$sessionMain"]["$sessionChild"] : $defaultValue;

        }
         
        return $resultData;

    }

    public static function has($sessionName='')
    {

        if (!preg_match('/(\w+)\.(\w+)/i', $sessionName, $matches)) {
            $session = isset($_SESSION[$sessionName]) ? true : false;

            return $session;
        } else {

            $sessionMain = $matches[1];

            $sessionChild = $matches[2];

            if(!isset($_SESSION[$sessionMain][$sessionChild]))
            {
                return false;
            }

        }

        return true;
    }

    public static function make($sessionName = '', $sessionValue = '')
    {
        if(preg_match('/(\w+)\.(\w+)/i', $sessionName,$matches))
        {   
            $s1=$matches[1];

            $s2=$matches[2];

            $_SESSION[$s1][$s2] = $sessionValue;
        }
        else
        {
            $_SESSION[$sessionName] = $sessionValue;
        }
    }

    public static function forget($sessionName = '')
    {
        if (!preg_match('/(\w+)\.(\w+)/i', $sessionName, $matches)) {
            unset($_SESSION[$sessionName]);
        } else {

            $sessionMain = $matches[1];

            $sessionChild = $matches[2];

            unset($_SESSION[$sessionMain][$sessionChild]);

        }
    }

    public static function remove($sessionName = '')
    {
        self::forget($sessionName);
    }

    public static function flush()
    {
        session_unset();
    }

    public static function push($sessionName = '', $sessionValue = '')
    {
        preg_match('/(\w+)\.(\w+)/i', $sessionName, $matches);

        $sessionMain = $matches[1];

        $sessionChild = $matches[2];


        $_SESSION[$sessionMain][$sessionChild] = $sessionValue;
    }

    public static function put($sessionName = '', $sessionValue = '')
    {
        self::make($sessionName, $sessionValue);
    }


}
