<?php

/*
 * This class defines methods for obtaining points.
 *
 */

/**
 * @author Dustin
 */
class Point_Redemption {
    public $User;

    function __construct($ID)
    {
        $this->User = new User($ID);

    }

}