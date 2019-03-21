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
        $awsConfig = $this->getElasticConfig('aws');
        if (!empty($awsConfig) && array_get($awsConfig, 'iam', false)) {
            $provider = \Aws\Credentials\CredentialProvider::defaultProvider();
            $handler = new \Aws\ElasticsearchService\ElasticsearchPhpHandler(array_get($awsConfig, 'region'), $provider);
            array_set($config, 'handler', $handler);
        }
        return \Elasticsearch\ClientBuilder::fromConfig($config);
    }
}
