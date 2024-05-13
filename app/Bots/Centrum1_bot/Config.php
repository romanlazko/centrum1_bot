<?php

namespace App\Bots\Centrum1_bot;

use Carbon\Carbon;

class Config
{
    public static function getConfig()
    {
        return [
            'inline_data'       => [
                'question_button_id'=> null,
                'type'              => null,
                'end_of_visa'       => null,
                'applying'          => 12,
                'deadline'          => null,
                'shengen'           => null,
                'criterion'         => null,
                'current_insurance' => null,
                'insurance_name'    => null,
                'temp'              => null,
            ],
            'lang'              => 'ru',
            'admin_ids'         => [
            ],
            'casts'             => [
                'amount' => 3130,
            ]
        ];
    }
}
