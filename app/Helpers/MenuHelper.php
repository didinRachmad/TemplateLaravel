<?php

if (!function_exists('isActiveMenu')) {
    function isActiveMenu($menuRoute)
    {
        $currentRoute = request()->route() ? request()->route()->getName() : '';

        // Periksa apakah nama route saat ini diawali dengan route yang ada di database
        return str_starts_with($currentRoute, $menuRoute);
    }
}
