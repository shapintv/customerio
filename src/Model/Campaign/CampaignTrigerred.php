<?php

declare(strict_types=1);

namespace Shapin\CustomerIO\Model\Campaign;

class CampaignTrigerred
{
    private $id;

    public function __construct(array $data)
    {
        $this->id = $data['id'];
    }
}
