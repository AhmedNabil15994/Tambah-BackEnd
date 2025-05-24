<?php

namespace Modules\Wrapping\Repositories\FrontEnd;

use Modules\Wrapping\Entities\Gift;
use Modules\Wrapping\Entities\Card;
use Modules\Wrapping\Entities\WrappingAddons;
use Illuminate\Support\Facades\DB;

class WrappingRepository
{
    protected $gift;
    protected $card;
    protected $addons;

    function __construct(Gift $gift, Card $card, WrappingAddons $addons)
    {
        $this->gift = $gift;
        $this->card = $card;
        $this->addons = $addons;
    }

    public function getAllGifts($order = 'id', $sort = 'desc')
    {
        return $this->gift->orderBy($order, $sort)->active()->get();
    }

    public function getAllGiftsByIds($ids, $order = 'id', $sort = 'desc')
    {
        return $this->gift->orderBy($order, $sort)->whereIn('id', $ids)->active()->get();
    }

    public function findGiftById($id)
    {
        return $this->gift->withDeleted()->active()->find($id);
    }

    public function getAllCards($order = 'id', $sort = 'desc')
    {
        return $this->card->orderBy($order, $sort)->active()->get();
    }

    public function findCardById($id)
    {
        return $this->card->withDeleted()->active()->find($id);
    }

    public function getAllAddons($order = 'id', $sort = 'desc')
    {
        return $this->addons->orderBy($order, $sort)->active()->get();
    }

    public function getAllAddonsByIds($ids, $order = 'id', $sort = 'desc')
    {
        return $this->addons->orderBy($order, $sort)->whereIn('id', $ids)->active()->get();
    }

    public function findAddonsById($id)
    {
        return $this->addons->withDeleted()->active()->find($id);
    }

}
