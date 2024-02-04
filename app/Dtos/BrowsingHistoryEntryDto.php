<?php

namespace App\Dtos;

readonly class BrowsingHistoryEntryDto
{
    public function __construct(public string $product_name,
                                public string $product_image_url,
                                public string $product_url)
    {
    }

    public function toArray(): array
    {
        return [
            'product_name' => $this->product_name,
            'product_image_url' => $this->product_image_url,
            'product_url' => $this->product_url,
        ];
    }
}
