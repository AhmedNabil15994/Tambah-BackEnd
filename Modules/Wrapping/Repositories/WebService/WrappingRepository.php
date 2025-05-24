<?php

namespace Modules\Wrapping\Repositories\WebService;

use Modules\Core\Traits\SyncRelationModel;
use Modules\Wrapping\Entities\Card;
use Modules\Wrapping\Entities\Gift;
use Modules\Wrapping\Entities\WrappingAddons;

class WrappingRepository
{
    use SyncRelationModel;

    protected $gift;
    protected $card;
    protected $addons;

    public function __construct(Gift $gift, Card $card, WrappingAddons $addons)
    {
        $this->gift = $gift;
        $this->card = $card;
        $this->addons = $addons;
    }

    public function getAllActiveGifts($order = 'id', $sort = 'desc')
    {
        $gifts = $this->gift->orderBy($order, $sort)->active()->get();
        return $gifts;
    }

    public function getAllActiveCards($order = 'id', $sort = 'desc')
    {
        $cards = $this->card->orderBy($order, $sort)->active()->get();
        return $cards;
    }

    public function getAllActiveAddons($order = 'id', $sort = 'desc')
    {
        $addons = $this->addons->orderBy($order, $sort)->active()->get();
        return $addons;
    }

    public function findGiftById($id)
    {
        return $this->gift->find($id);
    }

    public function findActiveGiftById($id)
    {
        return $this->gift->active()->find($id);
    }

    public function findCardById($id)
    {
        return $this->card->find($id);
    }

    public function findActiveCardById($id)
    {
        return $this->card->active()->find($id);
    }

    public function findAddonsById($id)
    {
        return $this->addons->find($id);
    }

    public function findActiveAddonsById($id)
    {
        return $this->addons->active()->find($id);
    }

}
