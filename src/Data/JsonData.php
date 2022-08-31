<?php

namespace App\Data;

class JsonData
{
    public function getContent(): string
    {
        return '{
            "validity_period": "1 Month",
            "action_types": {
                "delivery": "1",
                "rideshare": "1",
                "rent": "2"
            },
            "actions": {
                "delivery": {
                    "number": "10",
                    "duration": "4"
                },
                "rideshare": {
                    "number": "16",
                    "duration": "1"
                },
                "rent": {
                    "number": "1",
                    "duration": "3"
                }
            },
            "boosters": {
                "delivery": {
                    "number": "5",
                    "duration": "2",
                    "points": "5",
                    "expiry_date": "2022-10-23"
                },
                "rideshare": {
                    "number": "5",
                    "duration": "8",
                    "points": "10",
                    "expiry_date": "2022-09-23"
                },
                "rent": {
                    "number": "3",
                    "duration": "1",
                    "points": "0",
                    "expiry_date": "2022-09-23"
                }
            },
            "all_points": {
                "action_points": "0",
                "bonus_points": "0",
                "total_points": "0"
            },
            "booster_points": [
                {
                    "points": "10",
                    "expiry_date": "2022-08-29"
                },
                {
                    "points": "20",
                    "expiry_date": "2022-10-10"
                }
            ]
        }';
    }
}