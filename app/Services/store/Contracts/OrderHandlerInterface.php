<?php

namespace App\Services\store\Contracts;

use Illuminate\Support\Collection;

interface OrderHandlerInterface
{
    /**
     * @param bool $status
     * @return $this
     */
    public function skipHandler($status = true);

    /**
     * @param AbstractOrderHandler $handler
     * @return $this
     */
    public function pushHandler(AbstractOrderHandler $handler);

    /**
     * @return \Illuminate\Support\Collection
     */
    public function getHandlers(): Collection;

    /**
     * @return $this
     */
    public function applyBeforeHandler();

    /**
     * @return $this
     */
    public function applyAfterHandler();
}
