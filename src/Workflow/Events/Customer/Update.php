<?php

namespace Webkul\Ronanbriot\CoreFrameworkBundle\Workflow\Events\Customer;

use Webkul\Ronanbriot\AutomationBundle\Workflow\Events\CustomerActivity;

class Update extends CustomerActivity
{
    public static function getId()
    {
        return 'uvdesk.customer.updated';
    }

    public static function getDescription()
    {
        return "Customer Update";
    }
}
