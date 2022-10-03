<?php

namespace App\Models;

use Illuminate\Contracts\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\DB;

class Product extends Model {
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'shop_id',
        'name',
        'information',
        'price',
        'is_selling',
        'sort_order',
        'secondary_category_id',
        'image1',
        'image2',
        'image3',
        'image4',
    ];

    public function shop() {
        return $this->belongsTo(Shop::class);
    }

    public function category() {
        return $this->belongsTo(SecondaryCategory::class, 'secondary_category_id');
    }

    public function imageFirst() {
        return $this->belongsTo(Image::class, 'image1', 'id');
    }

    public function imageSecond() {
        return $this->belongsTo(Image::class, 'image2', 'id');
    }

    public function imageThird() {
        return $this->belongsTo(Image::class, 'image3', 'id');
    }

    public function imageForth() {
        return $this->belongsTo(Image::class, 'image4', 'id');
    }

    public function stocks() {
        return $this->hasMany(Stock::class);
    }

    public function users() {
        return $this->belongsToMany(User::class, 'carts')
            ->withPivot(['id', 'quantity']);
    }

    public function scopeAvailableItems($query) {
        $stocks = DB::table('t_stocks')
            ->select(
                'product_id',
                DB::raw('sum(quantity) as quantity')
            )
            ->groupBy('product_id')
            ->having('quantity', '>=', 1);

        return $query->joinSub($stocks, 'stock', function ($join) {
            $join->on('products.id', '=', 'stock.product_id');
        })
            ->join('shops', 'products.shop_id', '=', 'shops.id')
            ->join(
                'secondary_categories',
                'products.secondary_category_id',
                '=',
                'secondary_categories.id'
            )
            ->join('images as image1', 'products.image1', '=', 'image1.id')
            ->where('shops.is_selling', true)
            ->where('products.is_selling', true)
            ->select(
                'products.id as id',
                'products.name as name',
                'products.price',
                'products.sort_order as sort_order',
                'products.information',
                'products.created_at',
                'secondary_categories.name as category',
                'image1.filename as filename'
            );
    }

    public function scopeSortOrder($query, $sortOrder) {
        if (is_null($sortOrder) || $sortOrder === \SortOrderConstant::SORT_ORDER['recommend']['value']) {
            return $query->orderBy('sort_order', 'asc');
        }
        if ($sortOrder === \SortOrderConstant::SORT_ORDER['higherPrice']['value']) {
            return $query->orderBy('price', 'desc');
        }
        if ($sortOrder === \SortOrderConstant::SORT_ORDER['lowerPrice']['value']) {
            return $query->orderBy('price', 'asc');
        }
        if ($sortOrder === \SortOrderConstant::SORT_ORDER['newst']['value']) {
            return $query->orderBy('products.created_at', 'desc');
        }
        if ($sortOrder === \SortOrderConstant::SORT_ORDER['oldest']['value']) {
            return $query->orderBy('products.created_at', 'asc');
        }
    }
    /**
     * scopeOrderBySortOrderASC function
     * 指定無しまたはおすすめ順
     * @param Builder $query
     * @return Builder
     */
    public function scopeOrderBySortOrderASC(Builder $query): Builder {
        return $query->orderBy('sort_order', 'asc');
    }

    /**
     * scopeOrderByPriceDESC function
     * 価格の高い順
     * @param Builder $query
     * @return Builder
     */
    public function scopeOrderByPriceDESC(Builder $query): Builder {
        return $query->orderBy('price', 'desc');
    }

    /**
     * scopeOrderByPriceASC function
     * 価格の低い順
     * @param Builder $query
     * @return Builder
     */
    public function scopeOrderByPriceASC(Builder $query): Builder {
        return $query->orderBy('price', 'asc');
    }

    /**
     * scopeOrderByCreatedDESC function
     * 新しい順
     * @param Builder $query
     * @return Builder
     */
    public function scopeOrderByCreatedDESC(Builder $query): Builder {
        return $query->orderBy('products.created_at', 'desc');
    }

    /**
     * scopeOrderCreatedASC function
     * 古い順
     * @param Builder $query
     * @return Builder
     */
    public function scopeOrderCreatedASC(Builder $query): Builder {
        return $query->orderBy('products.created_at', 'asc');
    }



    /**
     * scopeSelectCategory function
     * カテゴリーを絞る
     * @param Builder $query
     * @param string $categoryId
     * @return Builder
     */
    public function scopeSelectCategory(Builder $query, string $categoryId): Builder {
        //全てのカテゴリーを検索する
        if ($categoryId === '0') {
            return $query;
        }
        return $query->where('secondary_category_id', $categoryId);
    }


    /**
     * scopeSearchKeyword function
     * キーワードで検索する
     * @param Builder $query
     * @param string|null $keyword
     * @return Builder
     */
    public function scopeSearchKeyword(Builder $query, string|null $keyword): Builder {
        if (is_null($keyword)) {
            return $query;
        }

        //全角スペースを半角に
        // $spaceConvert = mb_convert_kana($keyword, 's');
        //空白で区切る
        // $keywords = preg_split('/[\s]+/', $spaceConvert, -1, PREG_SPLIT_NO_EMPTY);

        $keywords = app()->make('extractKeywords', ['keyword' => $keyword]);

        //単語をループで回す
        foreach ($keywords as $word) {
            $query->where('products.name', 'like', '%' . $word . '%');
        }
        return $query;
    }
}
