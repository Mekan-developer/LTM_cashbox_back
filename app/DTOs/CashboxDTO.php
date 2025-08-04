<?php

namespace App\DTOs;


class CashboxDTO
{
    public string $title;
    public int $currency_id;
    public string $description;

    public function __construct($data)
    {
        $this->title = $data['title'];
        $this->currency_id = $data['currency_id'];
        $this->description = $data['description'];
    }
}
