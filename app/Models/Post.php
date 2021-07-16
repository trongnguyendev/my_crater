<?php

namespace Crater\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;

use Illuminate\Database\Eloquent\Model;

class Post extends Model
{
    use HasFactory;

    protected $table = 'posts';

    protected $fillable = [
        'title', 'thumbnail', 'content', 'view'
    ];

    public static function createPost($request)
    {
        $data = $request->validated();
        
        $post = self::create($data);

        return $post;
    }

    public function updatePost($request)
    {
        $this->update($request->validated());

        return $this->find($this->id);
    }
    
}
