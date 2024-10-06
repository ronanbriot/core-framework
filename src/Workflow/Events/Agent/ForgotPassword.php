<?php

namespace Webkul\Ronanbriot\CoreFrameworkBundle\Workflow\Events\Agent;

use Webkul\Ronanbriot\AutomationBundle\Workflow\Events\AgentActivity;

// @TODO: Deprecate this workflow event, instead use Events\User\ForgotPassword.
class ForgotPassword extends AgentActivity
{
    public static function getId()
    {
        return 'uvdesk.user.forgot_password';
    }

    public static function getDescription()
    {
        return "Agent Forgot Password";
    }
}
