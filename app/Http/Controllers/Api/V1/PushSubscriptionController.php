<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Models\PushSubscription;
use Illuminate\Http\Request;

class PushSubscriptionController extends Controller
{
    public function store(Request $request)
    {
        $data = $request->validate([
            'endpoint' => ['required','url','max:2048'],
            'keys.p256dh' => ['nullable','string','max:255'],
            'keys.auth' => ['nullable','string','max:255'],
            'content_encoding' => ['nullable','string','max:30'],
        ]);

        $subscription = PushSubscription::updateOrCreate(
            ['endpoint' => $data['endpoint']],
            ['user_id' => $request->user()->id, 'public_key' => data_get($data, 'keys.p256dh'), 'auth_token' => data_get($data, 'keys.auth'), 'content_encoding' => $data['content_encoding'] ?? 'aes128gcm']
        );
        return response()->json($subscription, 201);
    }

    public function destroy(Request $request)
    {
        $request->validate(['endpoint' => ['required','url','max:2048']]);
        PushSubscription::where('user_id', $request->user()->id)->where('endpoint', $request->endpoint)->delete();
        return response()->noContent();
    }
}
