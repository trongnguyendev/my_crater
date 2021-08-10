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

    public function comments()
    {
        return $this->hasMany(Comment::class);
    }

    public function scopeWhereOrder($query, $field, $orderBy)
    {
        $query->orderBy($field, $orderBy);
    }

    public function scopeWhereSearch($query, $search)
    {
        return $query->where('posts.title', 'LIKE', '%'. $search .'%');
    }

    public function scopeWhereContent($query, $content)
    {
        return $query->where('posts.content', 'LIKE', '%'. $content .'%');
    }

    public function scopePaginateData($query, $limit)
    {
        if($limit == 'all')
        {
            return collect(['data' => $query->get()]);
        }
        
        return $query->paginate($limit);
    }

    public function scopeApplyFilters($query, array $filters)
    {
        $filters = collect($filters);

        if($filters->get('search')) {
            $query->whereSearch($filters->get('search'));
        }

        if($filters->get('content'))
        {
            $query->whereContent($filters->get('content'));
        }

        if($filters->get('orderByField') || $filters->get('orderBy') )
        {
            $field = $filters->get('orderByField') ? $filters->get('orderByField') : 'title';
            $orderBy = $filters->get('orderBy') ? $filters->get('orderBy') : 'asc';
            $query->whereOrder($field, $orderBy);
        }

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
