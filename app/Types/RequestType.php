<?php
/**
 * User: Demetrio.Vray
 * Date: 08/24/18
 */

namespace App\Types;

class RequestType extends Type {
    protected static $labels = [

        'PROPOSAL' => 'PROPOSAL(FOR_PROPOSED)',

        'REVISION' => 'REVISION(FOR_EXISTING_DOCUMENT)',

        'CANCELLATION' => 'CANCELLATION',

    ];

    const PROPOSAL = 2;

    const REVISION = 3;

    const CANCELLATION = 4;

} 