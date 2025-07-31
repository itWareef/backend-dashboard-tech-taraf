<?php

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Broadcast;

Broadcast::channel('App.Models.User.{id}', function ($user, $id) {
    return (int) $user->id === (int) $id;
});
Broadcast::channel('chat.conversation.{customerId}', function ($user, $customerId) {
    return auth('customer')->check() && auth('customer')->id() == $customerId;
});

// قناة للأدمن
Broadcast::channel('admin', function ($user) {
    return auth('admin')->check(); // أو تحقق من صلاحياته
});
