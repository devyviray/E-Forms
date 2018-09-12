<?php
/**
 * User: Demetrio.Vray
 * Date: 08/24/18
 */

namespace App\Types;

class RolesType extends Type {
    protected static $labels = [

        'SUBMITTED' => 'SUBMITTED',

        'MR' => 'MR',

        'APPROVER' => 'APPROVER',

        'REVIEWER' => 'REVIEWER',

        'REQUESTER' => 'REQUESTER',

        'NOTIFIED'  => 'NOTIFIED',

        'MODERATOR' => 'MODERATOR'
    ];

    const ADMINISTRATOR = 1;

    const MR = 2;

    const APPROVER  = 3;

    const REVIEWER = 4;

    const MODERATOR = 5;
    
    const NOTIFIED = 6;

    const REQUESTER = 7;

    

} 