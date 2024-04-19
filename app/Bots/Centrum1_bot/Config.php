<?php

namespace App\Bots\Centrum1_bot;


class Config
{
    public static function getConfig()
    {
        return [
            'inline_data'       => [
                'type'              => null,
                'shengen'           => null,
                'count_of_month'    => 12,
                'criterion'         => null,
                'current_insurance' => null,
            ],
            'lang'              => 'ru',
            'admin_ids'         => [
            ],
        ];
    }
}
