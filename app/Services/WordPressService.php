<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;

class WordPressService
{
    protected $baseUrl;
    protected $username;
    protected $password;

    public function __construct()
    {
        $this->baseUrl = env('WORDPRESS_BASE_URL');
        $this->username = env('WORDPRESS_USERNAME');
        $this->password = env('WORDPRESS_APP_PASSWORD');
    }

    private function client()
    {
        return Http::withBasicAuth($this->username, $this->password);
    }

    public function getPosts()
    {
        return $this->client()->get($this->baseUrl . '/wp-json/wp/v2/posts')->json();
    }

    public function createPost($data)
    {
        return $this->client()->post($this->baseUrl . '/wp-json/wp/v2/posts', $data)->json();
    }

    public function updatePost($id, $data)
    {
        return $this->client()->post($this->baseUrl . "/wp-json/wp/v2/posts/$id", $data)->json();
    }

    public function deletePost($id)
    {
        return $this->client()->delete($this->baseUrl . "/wp-json/wp/v2/posts/$id")->json();
    }

    public function me()
    {
        return $this->client()->get($this->baseUrl . '/wp-json/wp/v2/users/me')->json();
    }
}
