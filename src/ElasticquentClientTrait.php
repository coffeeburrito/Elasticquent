<?php

namespace Elasticquent;

trait ElasticquentClientTrait
{
    use ElasticquentConfigTrait;

    /**
     * Get ElasticSearch Client
     *
     * @return \Elasticsearch\Client
     */
    public function getElasticSearchClient()
    {
        $config = $this->getElasticConfig();

        // elasticsearch v2.0+ using builder
        if (class_exists('\Elasticsearch\ClientBuilder')) {
            $awsConfig = $this->getElasticConfig('aws');
            if (!empty($awsConfig) && array_get($awsConfig, 'enable', false)) {
                $provider = \Aws\Credentials\CredentialProvider::fromCredentials(
                    new \Aws\Credentials\Credentials(
                        array_get($awsConfig, 'key'),
                        array_get($awsConfig, 'secret')
                    )
                );
                $handler = new \Aws\ElasticsearchService\ElasticsearchPhpHandler(array_get($awsConfig, 'region'), $provider);
                array_set($config, 'handler', $handler);
            }
            return \Elasticsearch\ClientBuilder::fromConfig($config);
        }

        // elasticsearch v1
        return new \Elasticsearch\Client($config);
    }

}
