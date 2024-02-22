<?php
namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;
use Elastic\Elasticsearch\ClientBuilder;
use Elastic\Elasticsearch\Exception\AuthenticationException;
use Elastic\Elasticsearch\Exception\ClientResponseException;
use Elastic\Elasticsearch\Exception\ServerResponseException;
use Elastic\Elasticsearch\Exception\MissingParameterException;

class ElasticsearchSetup extends Command {
    /**
     * The name and signature of the console command.
     * @var string
     */
    protected $signature = 'elasticsearch:setup';
    /**
     * The console command description.
     * @var string
     */
    protected $description = 'Setup elasticsearch';
    /**
     * Execute the console command.
     * @throws AuthenticationException
     */
    public function handle(): void {
        $hosts = config( 'elasticsearch.hosts' );
        $username = config( 'elasticsearch.username' );
        $password = config( 'elasticsearch.password' );
        $caBundle = config( 'elasticsearch.caBundle' );
        $client = ClientBuilder::create()
            ->setHosts( $hosts )
            ->setBasicAuthentication( $username, $password )
            ->setCABundle( $caBundle )
            ->build();
        $files = glob( 'resources/datasets/default/*.json' );
        foreach( $files as $file ) {
            $data = json_decode( file_get_contents( $file ), true );
            $params = [
                'index' => 'test',
                'id' => $data["url"],
                'body' => $data,
            ];
            try {
//                $this->info( $data["url"] );
                $response = $client->index( $params );
            } catch( ClientResponseException $e ) {
                $this->error( "1" . $e );
            } catch( MissingParameterException $e ) {
                $this->error( "2" . $e );
            } catch( ServerResponseException $e ) {
                $this->error( "3" . $e );
            }
            $this->info( "Indexed: {$file}" );
        }
    }
}
