<?php
namespace Framework\Session;


interface SessionInterface
{


    /**
     * Retrieve information at session
     * @param string $key
     * @param mixed $default
     * @return mixed
     */
    public function get(string $key, $default = null);



    /**
     * Adds information at session
     * @param string $key
     * @param mixed $value
     * @return mixed
     */
    public function set(string $key, $value): void;


    /**
     * Delete session key
     * @param string $key
     */
    public function delete(string $key): void;
}
