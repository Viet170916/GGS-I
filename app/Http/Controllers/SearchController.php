<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Facades\Elasticsearch;
use Illuminate\Http\JsonResponse;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller as BaseController;

class SearchController extends BaseController {
    use AuthorizesRequests, ValidatesRequests;

    public function search( Request $request ): JsonResponse {
        $client = Elasticsearch::getSearchResult( $request->input( "q" ) );
        return response()->json( $client );
    }
}
