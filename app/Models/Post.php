<?php

namespace App\Models;

use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Support\Facades\File;

class Post
{
    public $title;
    public $excerpt;
    public $date;
    public $body;

    public function __construct($title, $excerpt, $date, $body)
    {
        $this->title = $title;
        $this->excerpt = $excerpt;
        $this->date = $date;
        $this->body = $body;
    }

    public static function all()
    {
        $files = File::files(resource_path("views/post/"));
        return array_map(function ($file){
            return $file->getContents();
        },$files);
    }
    public static function find($slug)
    {
        if (!file_exists($path = resource_path("views/post/{$slug}.html"))) {
            throw new ModelNotFoundException();
        }
        return cache()->remember("posts.{$slug}", 5, function () use ($path) {
            return file_get_contents($path);
        });
    }
}
