<?php

namespace App\Http\Controllers\News;

use App\Http\Controllers\Controller;
use App\Models\News;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class LikeController extends Controller
{
    public function toggle(Request $request, News $news): RedirectResponse
    {
        $user = $request->user();

        $existing = $news->likes()->where('user_id', $user->id)->first();

        if ($existing) {
            $existing->delete();
        } else {
            $news->likes()->create(['user_id' => $user->id]);
        }

        return back();
    }
}
