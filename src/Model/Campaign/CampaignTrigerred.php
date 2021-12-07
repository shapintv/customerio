<?php

declare(strict_types=1);

namespace Shapin\CustomerIO\Model\Campaign;

class CampaignTrigerred
{
    private int $id;

    /**
     * @param array<int|string, mixed> $data
     */
    public function __construct(array $data)
    {
        if (!\array_key_exists('id', $data)) {
            throw new \RuntimeException('No "id" found in data!');
        }

        /* @phpstan-ignore-next-line */
        $this->id = (int) $data['id'];
    }

    public function getId(): int
    {
        return $this->id;
    }
}
