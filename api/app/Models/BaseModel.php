<?php

namespace App\Models;

use App\Observers\ModelObserver;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * App\Models\BaseModel
 *
 * @method static Builder|BaseModel roleJournal()
 */
class BaseModel extends Model
{
//    use HasFactory, SoftDeletes;

    public $timestamps = true;

    /**
     * 是否记录操作日志
     *
     */
    public $isOperationLog = true;

    /**
     * 忽略的字段
     *
     * @var array
     */
    protected $guarded = [
//        'id',
//        'created_at',
//        'updated_at'
    ];


    protected $casts = [
//        'created_at'     => 'timestamp',
//        'updated_at'     => 'timestamp',
    ];

    protected $hidden = [
//        'deleted_at'
    ];

    protected static function boot()
    {
        parent::boot();

        static::observe(ModelObserver::class);
    }

    /**
     * 操作列表数据
     *
     * @return Attribute
     */
    public function optList(): Attribute
    {
        return Attribute::make(
            get: fn() => ['delete']
        );
    }
}
