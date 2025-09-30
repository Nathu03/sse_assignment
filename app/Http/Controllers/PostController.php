<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Services\WordPressService;
use App\Models\PostPriority;

class PostController extends Controller
{
    protected $wp;

    public function __construct(WordPressService $wp)
    {
        $this->wp = $wp;
    }

    public function index()
    {
        $posts = $this->wp->getPosts() ?? [];
        foreach ($posts as &$post) {
            $priority = PostPriority::where('post_id', $post['id'])->first();
            $post['priority'] = $priority ? $priority->priority : null;
        }

        return response()->json($posts);
    }

    public function store(Request $request)
    {
        return $this->wp->createPost($request->only(['title','content','status']));
    }

    public function update(Request $request, $id)
    {
        return $this->wp->updatePost($id, $request->only(['title','content','status']));
    }

    public function destroy($id)
    {
        return $this->wp->deletePost($id);
    }

    public function setPriority(Request $request, $id)
    {
        $priority = PostPriority::updateOrCreate(
            ['post_id' => $id],
            ['priority' => $request->priority]
        );

        return response()->json($priority);
    }
}
