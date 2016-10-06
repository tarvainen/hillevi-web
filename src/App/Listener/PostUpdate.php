<?php

namespace App\Listener;

use Doctrine\ORM\Event\LifecycleEventArgs;

/**
 * A post update listener class.
 *
 * @package App\Listener
 *
 * @author Atte Tarvainen <atte.tarvainen@pp1.inet.fi>
 */
class PostUpdate
{
    /**
     * This method is fired every time the database value is changed.
     *
     * @param LifecycleEventArgs $args
     *
     * @return void
     */
    public function postUpdate(LifecycleEventArgs $args)
    {
        // TODO: add some post update functionality
    }
}
