<?php

namespace LT\UserBundle;

use Symfony\Component\HttpKernel\Bundle\Bundle;

class LTUserBundle extends Bundle
{
    public function getParent() {
	return 'FOSUserBundle';
    }
}
