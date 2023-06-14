<?php

namespace App\Helpers;

class RequestHelper
{
    public static function doesQueryParamsHasValue($queryString, $parameterValue)
    {
        if (!$queryString) {
            return false;
        }

        foreach (explode(',', $queryString) as $value) {
            if ($value == $parameterValue) {
                return true;
            }
        }

        return false;
    }
}
