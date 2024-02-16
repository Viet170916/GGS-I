<?php
namespace App\Console\Commands;

use Exception;
use Illuminate\Console\Command;
use Elastic\Elasticsearch\ClientBuilder;
use Elastic\Elasticsearch\Exception\AuthenticationException;

class CreateElasticsearchTemplate extends Command {
    protected $signature = 'create:es-template';
    protected $description = 'Creates an Elasticsearch query template';
    public function handle(): void {
        try {
            $client = ClientBuilder::create()->setHosts( [ 'localhost:9200' ] )->build();
            $params = [
                'id' => 'blog_search_template', // ID của template
                'body' => [
                    'script' => [
                        'lang' => 'mustache',
                        'source' => [
                            'query' => [
                                'bool' => [
                                    'should' => [
                                        [ 'match' => [ 'title' => '{{query}}' ] ],
                                        [ 'match' => [ 'h1' => '{{query}}' ] ],
                                        [ 'match' => [ 'h2' => '{{query}}' ] ],
                                        [ 'match' => [ 'body' => '{{query}}' ] ],
                                    ],
                                    'minimum_should_match' => 1,
                                ],
                            ],
                        ],
                    ],
                ],
            ];
            $response = $client->putScript( $params );
            $this->info( 'Template created successfully.' );
        } catch( AuthenticationException $e ) {
            $this->error( 'Error Authentication Exception: ' . $e->getMessage() );
        } catch( Exception $e ) {
            $this->error( 'Error creating template: ' . $e->getMessage() );
        }
    }
}
