<?php

namespace TontonYoyo\ApiObjectBundle\Bridge;


use Http\Discovery\HttpClientDiscovery;
use Http\Discovery\Psr17FactoryDiscovery;
use Psr\Http\Message\RequestInterface;
use TontonYoyo\ApiObjectBundle\ApiObject\ApiObject;

interface BridgeInterface
{

    public function __construct(HttpClientDiscovery $clientDiscovery,Psr17FactoryDiscovery $psr17FactoryDiscovery);

    /**
    * @author YMR-ImplementsDumbFactory
     * @param array $headerContent
     * @return $this
     */
    public function addHeaderContent(array $headerContent) ;

    /**
    * @author YMR-ImplementsDumbFactory
     * @param array $header
     * @return $this
     */
    public function setHeader(array $header);

    /**
     * setPostData for insert or update
    * @author YMR-ImplementsDumbFactory
     * @param $data
     */
    public function setPostData($data);

    /**
    * @author YMR-ImplementsDumbFactory
     * Check if method is supported by the bridge operations
     * @param string $method
     */
    public function setMethod(string $method);

    /**
    * @author YMR-ImplementsDumbFactory
     * Check if url is valid and Set Final url used by the request
     * @param string $finalUrl
     */
    public function setFinalUrl(string $finalUrl);

    /**
    * @author YMR-ImplementsDumbFactory
     * SetApiName if api as a name
     * @param string $name
     */
    public function setApiName(string $name);

    /**
    * @author YMR-ImplementsDumbFactory
     * return apiname
     * @return string
     */
    public function getApiName();

    /**
    * @author YMR-ImplementsDumbFactory
     * SetApiPort used to build url
     * @param mixed $port
     */
    public function setApiPort($port);

    /**
    * @author YMR-ImplementsDumbFactory
     * SetApiUrl used to build url
     * @param string $apiUrl
     */
    public function setApiUrl(string $apiUrl);

    /**
    * @author YMR-ImplementsDumbFactory
     * Set Table name if needed for url building
     * @param string $tableName
     */
    public function setTableName(string $tableName);

    /**
    * @author YMR-ImplementsDumbFactory
     * Make a Post request for insertion on api
     * postdata can be what you want according to what your target can handle
     * @return mixed
     * @throws \Http\Client\Exception
     */
    public function insert();

    /**
    * @author YMR-ImplementsDumbFactory
     * Make a Put request for insertion on api
     * postdata can be what you want according to what your target can handle
     * $id is the numeric id of the element you want to replace
     * @param int $id
     * @return mixed
     * @throws \Http\Client\Exception
     */
    public function update(int $id);

    /**
    * @author YMR-ImplementsDumbFactory
     * Make a DELETE request on the element you pass trough parameters
     * @param ApiObject $entity
     * @return mixed
     * @throws \Http\Client\Exception
     */
    public function delete(\TontonYoyo\ApiObjectBundle\ApiObject\ApiObject $entity);

    /**
    * @author YMR-ImplementsDumbFactory
     * Find an element based on is numeric ID
     * @param int $id
     * @return mixed
     * @throws \Http\Client\Exception
     */
    public function findOneById($id);

    /**
     * @param null $parameters
     * @return mixed
     * @throws \Http\Client\Exception
     */
    public function find($parameters = null);

    /**
     * @return mixed
     * @throws \Http\Client\Exception
     */
    public function findAll();

    /**
     *
     * @param array $data
     * @return $this
     * @throws \Http\Client\Exception
     */
    public function query(array $data);

    /**
     * @author Yoann MIR - DGL sword-group
     * create a request with optionnal get parameters
     * @param $params
     * @return mixed
     * @throws \Http\Client\Exception
     */
    public function searchWithFilters($params);


    /**
    * @author YMR-ImplementsDumbFactory
     * Set Table name if needed for url building
     * @param string $path
     * @param string $method
     * @return $this
     */
    public function setSpecialRoute(string $path, string $method);

    /**
    * @author YMR-ImplementsDumbFactory
     * Unset special route
     * @return $this
     */
    public function unsetSpecialRoute();

    /**
     * add formalized url get parameters to final url
     * @param array $params
     * @return $this
     */
    public function addGetParameters(array $params);

    /**
     * formalize url for a entity operation request
     * @param null $id
     * @return string
     */
    public function urlEntityFormalizer($id = null);

    /**
     * formalize url for a collection operation request
     * @return string
     */
    public function urlCollectionFormalizer();



    /**
     * send the previously built request if request match the PSR-7 requestInterface
     * @throws \Http\Client\Exception
     */
    public function sendRequest();

    /**
     * check if method is registered as supported method
     * @param $method
     * @return bool
     */
    public function supportsMethod($method);

    /**
     * return response content deflated
     * @return mixed
     */
    public function getResponse();

    /**
     * @return mixed
     */
    public function getStatusCode();

    /**
     * return decoded response content
     * @return mixed
     */
    public function getDecodedResponse();

    /**
     * return encoded response content
     * @return mixed
     */
    public function getEncodedResponse();

}