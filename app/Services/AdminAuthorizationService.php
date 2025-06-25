<?php
// app/Service/AdminAuthorizationService.php

namespace App\Services;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;

class AdminAuthorizationService
{
    public function authorizeAdmin($ability, $model)
    {
        $admin = Auth::guard('admin')->user();
        if (! $admin || ! Gate::forUser($admin)->allows($ability, $model)) {
            abort(403, 'Unauthorized action.');
        }
    }
    public static function policyCheck($ability, $model)
    {
        $admin = Auth::guard('admin')->user();
        return Gate::forUser($admin)->allows($ability, $model);
    }
}
