<?php
namespace App\Services;

use Elastic\Elasticsearch\ClientBuilder;
use Elastic\Elasticsearch\Client;
use Elastic\Elasticsearch\Exception\AuthenticationException;
use Elastic\Elasticsearch\Exception\ClientResponseException;
use Elastic\Elasticsearch\Exception\ServerResponseException;

class ElasticsearchService {
    protected Client $client;
    public function __construct() {
        try {
            $this->client = ClientBuilder::create()
                ->setElasticCloudId(config('elasticsearch.cloudId'))
                ->setApiKey(config('elasticsearch.apiKey'))
//                ->setHosts( config( 'elasticsearch.hosts' ) )
//                ->setBasicAuthentication( config( 'elasticsearch.username' ), config( 'elasticsearch.password' ) )
//                ->setCABundle(config( 'elasticsearch.caBundle' ) )
                ->build();
        } catch( AuthenticationException $e ) {
        }
    }
    public function getClient(): \Elastic\Elasticsearch\Response\Elasticsearch|\Http\Promise\Promise {
        return $this->client->info();
    }
    /**
     * @throws ClientResponseException
     * @throws ServerResponseException
     */
    public function getSearchResult( $searchTerm ): array {
        $params = [
            'index' => 'crawled-pages',
            'body' => [
                'size' => 15,
                'query' => [
                    'function_score' => [
                        'functions' => [
                            [
                                'script_score' => [
                                    'script' => [
                                        "source" => "1/Math.log(doc['url.keyword'].value.length())",
                                    ],
                                ],
                            ],
                        ],
                        'query' => [
                            'bool' => [
                                'should' => [
                                    [ 'match' => [ 'data.title' => [ 'query' => $searchTerm, 'boost' => 9 ] ] ],
                                    [ 'match' => [ 'data.img' => [ 'query' => $searchTerm, 'boost' => 8 ] ] ],
                                    [ 'match' => [ 'data.h1' => [ 'query' => $searchTerm, 'boost' => 8 ] ] ],
                                    [ 'match' => [ 'data.h2' => [ 'query' => $searchTerm, 'boost' => 7 ] ] ],
                                    [ 'match' => [ 'data.h3' => [ 'query' => $searchTerm, 'boost' => 6 ] ] ],
                                    [ 'match' => [ 'data.h4' => [ 'query' => $searchTerm, 'boost' => 5 ] ] ],
                                    [ 'match' => [ 'data.h5' => [ 'query' => $searchTerm, 'boost' => 4 ] ] ],
                                    [ 'match' => [ 'data.h6' => [ 'query' => $searchTerm, 'boost' => 3 ] ] ],
                                    [ 'match' => [ 'data.p' => [ 'query' => $searchTerm, 'boost' => 0 ] ] ],
                                    [ 'match' => [ 'data.div' => [ 'query' => $searchTerm, 'boost' => 0 ] ] ],
                                    [ 'match' => [ 'data.a' => [ 'query' => $searchTerm, 'boost' => 0 ] ] ],
                                ],
                            ],
                        ],
                        'score_mode' => 'multiply',
                    ],
                ],
            ],
        ];
        $response = $this->client->search( $params );
        $urls = [];
        if( isset( $response[ 'hits' ][ 'hits' ] ) ) {
            foreach( $response[ 'hits' ][ 'hits' ] as $hit ) {
                if( isset( $hit[ '_source' ][ 'url' ] ) ) {
                    $urls[] = [
                        "host" => parse_url( $hit[ '_source' ][ 'url' ] )[ "host" ],
                        "url" => $hit[ '_source' ][ 'url' ],
                        "title" => $hit[ '_source' ][ 'data' ][ 'title' ][ 0 ],
                    ];
                }
            }
        }
        return $urls;
    }
    public function test( $searchTerm ) {
        $params = [
            'index' => 'test',
            'body' => [
                'size' => 15,
                'query' => [
                    'bool' => [
                        'should' => [
                            [ 'match' => [ 'data.title' => [ 'query' => $searchTerm, 'boost' => 9 ] ] ],
                        ],
                    ],
                ],
            ],
        ];
        $response = $this->client->search( $params );
        $urls = [];
        if( isset( $response[ 'hits' ][ 'hits' ] ) ) {
            foreach( $response[ 'hits' ][ 'hits' ] as $hit ) {
                if( isset( $hit[ '_source' ][ 'url' ] ) ) {
                    $urls[] = [
                        "host" => parse_url( $hit[ '_source' ][ 'url' ] )[ "host" ],
                        "url" => $hit[ '_source' ][ 'url' ],
                        "title" => $hit[ '_source' ][ 'data' ][ 'title' ][ 0 ],
                    ];
                }
            }
        }
        return $response['hits'];
    }
}


