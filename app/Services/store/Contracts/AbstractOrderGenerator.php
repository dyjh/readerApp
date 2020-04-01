<?php

namespace App\Services\store\Contracts;

use App\Models\stores\Contracts\OrderInterface;
use Illuminate\Support\Collection;

abstract class AbstractOrderGenerator implements OrderGeneratorInterface, OrderHandlerInterface
{
    /**
     * @var OrderInterface
     */
    protected $order;

    /**
     * @var bool
     */
    protected $skipHandler = false;

    /**
     * @var Collection
     */
    protected $handlers;

    /**
     * OrderGenerator constructor.
     * @param OrderInterface $order
     */
    public function __construct(OrderInterface $order)
    {
        $this->order    = $order;
        $this->handlers = collect();
    }

    /**
     * @param bool $status
     * @return $this
     */
    public function skipHandler($status = true)
    {
        $this->skipHandler = $status;

        return $this;
    }

    /**
     * @param AbstractOrderHandler $handler
     * @return $this
     */
    public function pushHandler(AbstractOrderHandler $handler)
    {
        $this->getHandlers()->push($handler);

        return $this;
    }

    /**
     * @return \Illuminate\Support\Collection
     */
    public function getHandlers(): Collection
    {
        return $this->handlers;
    }

    /**
     * @return $this
     */
    public function applyBeforeHandler()
    {
        if (!$this->skipHandler) {
            foreach ($this->getHandlers() as $handler)
                if ($handler instanceof AbstractOrderHandler)
                    $handler->beforeHandle($this->order, $this);
        }

        return $this;
    }

    /**
     * @return $this
     */
    public function applyAfterHandler()
    {
        if (!$this->skipHandler) {
            foreach ($this->getHandlers() as $handler)
                if ($handler instanceof AbstractOrderHandler)
                    $handler->afterHandle($this->order, $this);
        }

        return $this;
    }

    /**
     * @return OrderInterface
     * @throws \Exception
     */
    public function generate(): OrderInterface
    {
        $this->applyBeforeHandler();

        $this->order->save();

        $this->applyAfterHandler();

        return $this->order;
    }
}
