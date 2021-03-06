<?php

namespace App;

use App\Libraries\Traits\SearchAndRelationShip;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Libraries\Traits\NewestStatistic;

class Order extends Model
{
    use SearchAndRelationShip;
    use SoftDeletes;
    use NewestStatistic;

    const ITEMS_PER_PAGE = 10;
    const STATUS_CANCELED = 0;
    const STATUS_PENDING = 1;
    const STATUS_APPROVED = 2;
    const STATUS_FINISHED = 3;

    protected $fillable = ['id', 'user_id', 'trans_at', 'custom_address', 'status'];

    /**
     * Searchable rules.
     *
     * @var array
     */
    protected $searchable = [

        'columns' => [
            'users' => ['full_name'],
            'trans_at',
            'custom_address',
            'total_price',
            'status',
        ],
        'joins' => [
            'users' => ['user_id' => 'id']
        ]
    ];

    /**
     * Order has many order item.
     *
     * @return array
     */
    public function orderItems()
    {
        return $this->hasMany(OrderItem::class);
    }


    /**
     * Order belongsTo User
     *
     * @return mixed
     */
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id', 'id');
    }

    /**
     * Update Total Price of Order
     *
     * @return bool
     */
    public function updateTotalPrice()
    {
        $this->load('orderItems', 'orderItems.itemtable');
        $this->total_price = $this->orderItems->sum(function ($item) {
            return $item->quantity * $item->itemtable->price;
        });
        return $this->save();
    }

    /**
     * This is a recommended way to declare event handlers
     *
     * @return void
     */
    protected static function boot()
    {
        parent::boot();

        static::deleting(function ($order) {
            $order->orderItems()->delete();
        });
    }
}
