<?php

namespace Crater\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Comment extends Model
{
    use HasFactory;

    protected $fillable = [
        'content',
        'user_id',
        'post_id',
    ];

    public $timestamps = false;

    public function Post()
    {
        return $this->belongsTo(Post::class);
    }

    public function User()
    {
        return $this->belongsTo(User::class);
    }

    public function scopeWhereSearch($query, $search)
    {
        return $query->where('comments.content', 'LIKE', '%'. $search .'%');
    }

    public function scopeWhereOrder($query, $field, $orderBy)
    {
        $query->orderBy($field, $orderBy);
    }

    public function scopePaginateData($query, $limit)
    {
        if($limit == 'all')
        {
            return collect(['data' => $query->get()]);
        }
        return $query->paginate($limit);
    }

    public function scopeApplyFilter($query, array $filter)
    {
        $filter = collect($filter);

        if($filter->get('search'))
        {
            $query->whereSearch($filter->get('search'));
        }

        if($filter->get('orderBy') || $filter->get('orderByField'))
        {
            $field = $filter->get('orderByField') ? $filter->get('orderByField') : 'content';
            $orderBy = $filter->get('orderBy') ? $filter->get('orderBy') : 'desc';
            $query->whereOrder($field, $orderBy);
        }
    }

    public static function createComment($request)
    {
        
        $data = $request->validated();

        $comment = self::create($data);

        return $comment;
    }

    public function updateComment($request)
    {
        $this->update($request->validated());

        return $this->find($this->id);
    }
}
