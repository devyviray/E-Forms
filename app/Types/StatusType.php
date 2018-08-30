<?php
/**
 * User: Demetrio.Vray
 * Date: 08/24/18
 */

namespace App\Types;

class StatusType extends Type {
    protected static $labels = [

        'SUBMITTED' => 'REQUESTER_SUBMIT',

        'APPROVED_REVIEWER' => 'REVIEWER_APPROVED',

        'APPROVED_APPROVER' => 'APPROVER_APPROVED',

        'DISAPPROVED_REVIEWER' => 'REVIEWER_DISAPPROVED',

        'DISAPPROVED_APPROVER' => 'APPROVER_DISAPPROVED',

    ];

    const SUBMITTED = 2;

    const APPROVED_REVIEWER = 3;

    const APPROVED_APPROVER  = 4;

    const DISAPPROVED_REVIEWER = 5;

    const DISAPPROVED_APPROVER = 6;

} 