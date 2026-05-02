<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Article extends Model {
    protected $fillable = [
        'title','slug','excerpt','content','image',
        'category','author_id','is_published','read_time'
    ];
    protected $casts = ['is_published' => 'boolean'];

    protected static function boot() {
        parent::boot();
        static::creating(fn($a) => $a->slug = Str::slug($a->title).'-'.time());
    }

    public function author() { return $this->belongsTo(User::class, 'author_id'); }
}