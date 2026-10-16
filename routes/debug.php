<?php

use Illuminate\Support\Facades\Route;
use App\Models\User;

Route::get('/debug/user/{id}', function ($id) {
    $user = User::find($id);
    
    if (!$user) {
        return response()->json(['error' => 'User not found'], 404);
    }
    
    return response()->json([
        'id' => $user->id,
        'name' => $user->name,
        'email' => $user->email,
        'created_at_type' => gettype($user->created_at),
        'created_at_value' => $user->created_at ? $user->created_at->toDateTimeString() : null,
        'created_at_is_null' => $user->created_at === null,
        'updated_at_type' => gettype($user->updated_at),
        'updated_at_value' => $user->updated_at ? $user->updated_at->toDateTimeString() : null,
        'timestamps_enabled' => $user->timestamps,
        'attributes' => $user->getAttributes(),
    ], 200, [], JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES);
});
