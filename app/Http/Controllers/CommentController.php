<?php

namespace App\Http\Controllers;

use App\Models\Comment;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;

class CommentController extends Controller
{
    public function destroy(Comment $comment)
    {
        /** @var \App\Models\User $user */
        $user = Auth::user();

        if (!$user) abort(403);

        // Creator can delete any comment
        if (! $user->isCreator() && $comment->user_id !== $user->id) {
            abort(403, 'Unauthorized');
        }

        $comment->delete();

        return back()->with('success', 'Comment deleted.');
    }

}
