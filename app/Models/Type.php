<?php

namespace Crater\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Type extends Model
{
    use HasFactory;

    protected $table = 'types';

    protected $fillable = [
        'name', 'description'
    ];

    public function posts()
    {
        return $this->belongsToMany(Post::class, 'type_posts');
    }

    public function scopeWhereSearch($query, $search)
    {
        return $query->where('types.name', 'LIKE', '%'. $search .'$');
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

    public function scopeApplyFilters($query, array $filters)
    {
        $filters = collect($filters);

        if($filters->get('search'))
        {
            $query->whereSearch($filters->get('search'));
        }

        if($filters->get('orderByField') || $filters->get('orderBy'))
        {
            $field = $filters->get('orderByField') ? $filters->get('orderByField') : 'name';

            $orderBy = $filters->get('orderBy') ? $filters->get('orderBy') : 'asc';

            $query->whereOrder($field, $orderBy);
        }
    }


    public static function createType($request)
    {

        $data = $request->validated();

        $type = self::create($data);

        return $type;
    }

    public function updateType($request)
    {
        $this->update($request->validated());

        return $this->find($this->id);
    }
}
