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

        'VERIFIED_VERIFIER'  => 'VERIFIED_VERIFIER',

        'UNVERIFIED_VERIFIER' => 'UNVERIFIED_VERIFIER',

        'CCIR_VALID' => 'CCIR_VALID' , 

        'CCIR_INVALID' => 'CCIR_INVALID' , 

    ];

    const SUBMITTED = 2;

    const APPROVED_REVIEWER = 3;

    const APPROVED_APPROVER  = 4;

    const DISAPPROVED_REVIEWER = 5;

    const DISAPPROVED_APPROVER = 6;
    
    const VERIFIED_VERIFIER = 7;

    const UNVERIFIED_VERIFIER = 8;
    
    const CCIR_VALID = 9;

    const CCIR_INVALID = 10;

    const TRASH_DOCUMENTS = 11;

    const ARCHIVE_DOCUMENTS = 12;

    const CANCEL_DOCUMENTS = 13; 

    const MARK_AS_DISTRIBUTED = 14;

} 