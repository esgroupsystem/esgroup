<?php

/** for side bar menu active */
function set_active($route) {
    if (is_array($route )){
        return in_array(Request::path(), $route) ? 'active' : '';
    }
    return Request::path() == $route ? 'active' : '';
}

if (!function_exists('privilege_link')) {
    function privilege_link($routeName, $rolesAllowed = ['Maintenance', 'Admin', 'IT', 'HR'], $linkText = '', $extraClasses = '') {
        $userRole = Auth::user()->role_name ?? '';
        if (in_array($userRole, $rolesAllowed)) {
            return '<a class="' . $extraClasses . '" href="' . route($routeName) . '">' . $linkText . '</a>';
        } else {
            return '<a class="' . $extraClasses . '" href="javascript:void(0);" onclick="noPrivilegeAlert()">' . $linkText . '</a>';
        }
    }
}