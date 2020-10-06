<?php


namespace Trustenterprises\LaravelHashgraph\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;

class HashgraphTopic extends Model
{
    protected $guarded = [];

    public function scopeFromName($query, String $name): Builder
    {
        return $query->where('name', $name);
    }

    public function scopeFromTopic($query, String $topic_id): Builder
    {
        return $query->where('topic_id', $topic_id);
    }
}
