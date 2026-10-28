<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Food extends Model
{
    protected $table = 'foods';

    protected $fillable = [
        'name',
        'unit',
        'category_id',
        'min_qty',
    ];

    public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class);
    }

    public function stockLogs(): HasMany
    {
        return $this->hasMany(StockLog::class);
    }

    public function casts(): array
    {
        return [
            'min_qty' => 'decimal:2',
        ];
    }

    /**
     * 現在庫を習得する
     */
    public function getCurrentQty(): float
    {
        // withSum()で計算済みの場合は、その値を利用する
        if (array_key_exists('in_qty', $this->getAttributes())
            && array_key_exists('out_qty', $this->getAttributes())) {
        
        return (float) ($this->in_qty ?? 0)
                -(float) ($this->out_qty ?? 0);
        }

        // 入出庫履歴から現在庫を計算する
        $logs = $this->stockLogs;

        $inQty = $logs->where('type', 'in')->sum('qty');
        $outQty = $logs->where('type', 'out')->sum('qty');

        return (float) ($inQty - $outQty);
    }

    /**
     * 在庫状況を取得する
     */
    public function getStockStatus(): string
    {
        $currentQty = $this->getCurrentQty();

        if ($currentQty <= 0) {
            return '在庫なし';
        } elseif ($currentQty <= $this->min_qty) {
            return '残りわずか';
        } else {
            return '在庫あり';
        }
    }
}
