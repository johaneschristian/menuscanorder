<?php

namespace App\Utils;
use Ramsey\Uuid\Uuid;

class Utils
{
    public static function generateUUID() {
        return Uuid::uuid4()->toString();
    }
}