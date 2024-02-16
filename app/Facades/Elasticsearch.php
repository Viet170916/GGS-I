<?php
namespace App\Facades;

use Illuminate\Support\Facades\Facade;

/**
 * @method static getClient()
 * @method static getSearchResult( $param )
 */
class Elasticsearch extends Facade {
    protected static function getFacadeAccessor(): string {
        return 'ElasticsearchService';
    }
}

