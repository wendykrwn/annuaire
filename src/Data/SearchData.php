<?php

namespace App\Data;

use App\Entity\Group;

class SearchData
{


    /**
     * @var int
     */
    public $page = 1;

    /**
     * @var string
     */
    public $qFirstName;

    /**
     * @var string
     */
    public $qLastName;

    /**
     * @var Group
     */
    public $qGroupName;

}