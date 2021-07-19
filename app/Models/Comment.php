<?php

namespace Crater\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Comment extends Model
{
    use HasFactory;

    protected $fillable = [
        'content', 'post_id'
    ];

    public function Post()
    {
        return $this->belongsTo(Post::class);
    }

    public static function createComment($request)
    {
        $data = $request->validated();

        $comment = self::create($data);

        return $comment;
    }
}
