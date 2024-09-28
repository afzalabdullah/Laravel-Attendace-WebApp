<?php
if (!function_exists('myHelperFunction')) {
    function myHelperFunction() {
        // Your function implementation here
        $segments = request()->segments();
        $breadcrumbs = [];
        $url = '';
    
        foreach ($segments as $segment) {
            $url .= '/' . $segment;
            $breadcrumbs[] = [
                'url' => $url,
                'name' => ucwords(str_replace('-', ' ', $segment))
            ];
        }
    
        return $breadcrumbs;
    }
}