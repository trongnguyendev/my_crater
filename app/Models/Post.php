<?php

namespace Crater\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;


class Post extends Model
{
    use HasFactory;

    protected $table = 'posts';

    protected $fillable = [
        'title', 'thumbnail', 'content', 'view'
    ];

    public function comment()
    {
        return $this->hasMany(Comments::class);
    }

    public static function createPost($request)
    {
        $data = $request->validated();

        $name_thumbnail = self::generateNameThumbnail($data['thumbnail']);

        $thumbnail = self::uploadThumbnail($data['thumbnail'], $name_thumbnail);

        $data['thumbnail'] = $name_thumbnail;

        $post = self::create($data);

        return $post;
    }

    public static function generateNameThumbnail($file) 
    {
        $name_file = $file->getClientOriginalName();

        $generate_name_file = date("mdhis") . '-' . str_replace(' ', '-', $name_file);

        return $generate_name_file;
    }

    public static function uploadThumbnail($file, $name)
    {
        $path_thumbnail = $file->storeAs('public/post', $name);
        
        return $path_thumbnail;
    }

    public function updatePost($request)
    {
        $this->update($request->validated());

        return $this->find($this->id);
    }

    public static function updatePostthumbnail($request, $post)
    {

        $data = $request->validated();

        $name_thumbnail = self::generateNameThumbnail($data['thumbnail']);

        $thumbnail = self::uploadThumbnail($data['thumbnail'], $name_thumbnail);

        $data['thumbnail'] = $name_thumbnail;

        $post->update($data);

        return $post;
    }
    
}
