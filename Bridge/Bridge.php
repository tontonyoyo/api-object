<?php


namespace TontonYoyo\ApiObjectBundle\Bridge;

use const FILTER_VALIDATE_URL;
use Http\Discovery\HttpClientDiscovery;
use Http\Discovery\Psr17FactoryDiscovery;
use TontonYoyo\ApiObjectBundle\ApiObject\ApiObject;
use function is_null;
use Psr\Http\Message\RequestInterface;
use Psr\Http\Message\ResponseInterface;
use TontonYoyo\ApiObjectBundle\Operation\OperationInterface;

class Bridge implements BridgeInterface
{
    private const METHOD_GET = 'GET';
    private const METHOD_POST = 'POST';
    private const METHOD_PUT = 'PUT';
    private const METHOD_DELETE = 'DELETE';

    /**
     * @var \Http\Client\HttpClient
     * properties used for configuration
     */
    private $client;

    private $msgFactoryDicovery;

    private $apiUrl;

    private $url;

    private $apiName;

    private $apiPort;

    private $tableName;

    /**
     * properties used by requestfactory
     */
    private $final_url;

    private $header = [];

    private $method;

    private $body;

    private $protocolVersion = '1.1';

    private $specialRoute;

    private $postData;

    /**
     * properties conaining response elements
     */
    private $request;

    private $response;

    private $response_content;

    public function __construct(HttpClientDiscovery $clientDiscovery,Psr17FactoryDiscovery $msgFactoryDicovery)
    {
        $this->client = $clientDiscovery->find();
        $this->msgFactoryDicovery = $msgFactoryDicovery;
    }

    /**
    * @author YMR-ImplementsDumbFactory
     * @param array $headerContent
     * @return $this
     */
    public function addHeaderContent(array $headerContent) // OK
    {
        foreach ($headerContent as $item => $value) {
            $this->header[$item] = $value;
        }
        return $this;
    }

    /**
    * @author YMR-ImplementsDumbFactory
     * @param array $header
     * @return $this
     */
    public function setHeader(array $header) //OK
    {
        $this->header = [];
        foreach ($header as $item => $value) {
            $this->header[$item] = $value;
        }
        return $this;
    }

    /**
     * setPostData for insert or update
    * @author YMR-ImplementsDumbFactory
     * @param $data
     */
    public function setPostData($data)
    {
        $this->postData = json_encode($data);
    }

    /**
    * @author YMR-ImplementsDumbFactory
     * Check if method is supported by the bridge operations
     * @param string $method
     */
    public function setMethod(string $method)
    {
        if (!$this->supportsMethod($method)) {
            dump('bad method: ' . $method);
            die;
            // TODO :throw bad method exception
        }
        $this->method = $method;
    }

    /**
    * @author YMR-ImplementsDumbFactory
     * Check if url is valid and Set Final url used by the request
     * @param string $finalUrl
     */
    public function setFinalUrl(string $finalUrl)
    {
        $this->final_url = $finalUrl;
    }

    /**
    * @author YMR-ImplementsDumbFactory
     * SetApiName if api as a name
     * @param string $name
     */
    public function setApiName(string $name)
    {
        $this->apiName = $name;
    }

    /**
    * @author YMR-ImplementsDumbFactory
     * return apiname
     * @return string
     */
    public function getApiName()
    {
        return $this->apiName;
    }

    /**
    * @author YMR-ImplementsDumbFactory
     * SetApiPort used to build url
     * @param mixed $port
     */
    public function setApiPort($port)
    {
        $this->apiPort = $port;
    }

    /**
    * @author YMR-ImplementsDumbFactory
     * SetApiUrl used to build url
     * @param string $apiUrl
     */
    public function setApiUrl(string $apiUrl)
    {
        $this->apiUrl = $apiUrl;
    }

    /**
    * @author YMR-ImplementsDumbFactory
     * Set Table name if needed for url building
     * @param string $tableName
     */
    public function setTableName(string $tableName)
    {
        $this->tableName = $tableName;
    }

    /**
    * @author YMR-ImplementsDumbFactory
     * Make a Post request for insertion on api
     * postdata can be what you want according to what your target can handle
     * @return mixed
     * @throws \Http\Client\Exception
     */
    public function insert() //OK
    {
        $this->setMethod('POST');
        $this->body = $this->postData;
        $this->urlCollectionFormalizer();
        $this->request = $this->makeRequest();
        $this->sendRequest();
        return $this->GetResponse();
    }

    /**
    * @author YMR-ImplementsDumbFactory
     * Make a Put request for insertion on api
     * postdata can be what you want according to what your target can handle
     * $id is the numeric id of the element you want to replace
     * @param int $id
     * @return mixed
     * @throws \Http\Client\Exception
     */
    public function update(int $id) // OK
    {
        $this->setMethod('PUT');
        $this->body = $this->postData;
        $this->urlEntityFormalizer($id);
        $this->request = $this->makeRequest();
        $this->sendRequest();
        return $this->getResponse();
    }

    /**
    * @author YMR-ImplementsDumbFactory
     * Make a DELETE request on the element you pass trough parameters
     * @param ApiObject $entity
     * @return mixed
     * @throws \Http\Client\Exception
     */
    public function delete(ApiObject $entity) //OK
    {
        $this->setMethod('DELETE');
        $this->urlEntityFormalizer($entity->getId());
        $this->request = $this->makeRequest();
        $this->sendRequest();
        return $this->getResponse();
    }

    /**
    * @author YMR-ImplementsDumbFactory
     * Find an element based on is numeric ID
     * @param int $id
     * @return mixed
     * @throws \Http\Client\Exception
     */
    public function findOneById($id) // OK
    {
        $this->setMethod('GET');
        $this->urlEntityFormalizer($id);
        $this->request = $this->makeRequest();
        $this->sendRequest();
        return $this->getResponse();
    }

    /**
     * @param null $parameters
     * @return mixed
     * @throws \Http\Client\Exception
     */
    public function find($parameters = null)
    {
        if (!is_null($this->specialRoute)) {
            $this->setMethod($this->specialRoute['method']);
        } else {
            $this->setMethod('GET');
        }
        $this->urlCollectionFormalizer();
        if (!is_null($parameters)) {
            $this->addGetParameters($parameters);
        }

        $this->request = $this->makeRequest();
        $this->sendRequest();

        return $this->getResponse();
    }

    /**
     * @return mixed
     * @throws \Http\Client\Exception
     */
    public function findAll() //OK
    {
        if (!is_null($this->specialRoute)) {
            $this->setMethod($this->specialRoute['method']);
        } else {
            $this->setMethod('GET');
        }
        $this->urlCollectionFormalizer();
        $this->request = $this->makeRequest();
        $this->sendRequest();
        return $this->getDecodedResponse();
    }

    /**
     *
     * @param array $data
     * @return $this
     * @throws \Http\Client\Exception
     */
    public function query(array $data)
    {
        $this->searchWithFilters($data);
        return $this->getResponse();
    }

    /**
     * @author Yoann MIR - DGL sword-group
     * create a request with optionnal get parameters
     * @param $params
     * @return mixed
     * @throws \Http\Client\Exception
     */
    public function searchWithFilters($params)
    {
        if (!is_null($this->specialRoute)) {
            $this->setMethod($this->specialRoute['method']);
        } else {
            $this->setMethod('GET');
        }
        $this->urlCollectionFormalizer();

        if (!is_null($params)) {
            $this->addGetParameters($params);
        }

        $this->request = $this->makeRequest();
        //dump($this->request);
        if (!$this->request instanceOf RequestInterface) {
            // TODO : exception
            dump($this->request);
            die;
        }
        $this->sendRequest();
        return $this->getResponse();
    }


    /**
    * @author YMR-ImplementsDumbFactory
     * Set Table name if needed for url building
     * @param string $path
     * @param string $method
     * @return $this
     */
    public function setSpecialRoute(string $path, string $method) //OK
    {
        $this->specialRoute['path'] = $path;
        $this->specialRoute['method'] = $method;
        return $this;
    }

    /**
     * Unset special route and method
     * @author MIR Yoann -DGL -sword-group
     * @return $this
     */
    public function unsetSpecialRoute()
    {
        $this->specialRoute = null;
        return $this;
    }

    /**
     * add formalized url get parameters to final url
     * @param array $params
     * @return $this
     */
    public function addGetParameters(array $params)
    {
        $httpQuery = http_build_query($params);
        $url_with_params = $this->final_url . '?' . $httpQuery;
        $this->setFinalUrl($url_with_params);
        return $this;
    }

    /**
     * formalize url for a entity operation request
     * @param null $id
     *
     */
    public function urlEntityFormalizer($id = null)
    {
        if (!is_null($this->specialRoute)) {
            $final_url = $this->url . '/' . $this->tableName.'/'.$id. '/' . $this->specialRoute['path'];
        } else {
            $final_url = $this->url . '/' . $this->tableName.'/'.$id;
        }
        $this->setFinalUrl($final_url);
    }

    /**
     * formalize url for a collection operation request
     *
     */
    public function urlCollectionFormalizer()
    {
        if (!is_null($this->specialRoute)) {
            $final_url = $this->url . '/' . $this->specialRoute['path'];
        } else {
            $final_url = $this->url . '/' . $this->tableName;
        }
        $this->setFinalUrl($final_url);
    }

    /**
     * Build api url (example http://localhost:9000/api
     */
    public function buildUrl()
    {
        $this->url = '';
        $this->url = $this->apiUrl;

        if (!is_null($this->apiPort)) {
            $this->url .= ':' . $this->apiPort;
        }
        if (!is_null($this->apiName)) {
            $this->url .= '/' . $this->apiName;
        }
    }

    /**
     * Create an object request with previously set parameters
     * @return RequestInterface
     */
    public function makeRequest()
    {
        return $this->psr17FactoryDiscovery->findRequestFactory()->createRequest(
            $this->method,
            $this->final_url,
            $this->header,
            $this->body,
            $this->protocolVersion
        );
    }

    /**
     * send the previously built request if request match the PSR-7 requestInterface
     * @throws \Http\Client\Exception
     */
    public function sendRequest()
    {
        if (!$this->request instanceof RequestInterface) {
            //TODO throw exception
            dump($this->request);
            die;
        }
        $this->response = $this->client->sendRequest($this->request);
        if($this->response instanceOf ResponseInterface){
            $this->response_content = $this->response->getBody()->getContents();
        }
    }

    /**
     * check if method is registered as supported method
     * @param $method
     * @return bool
     */
    public function supportsMethod($method)
    {
        return defined('self::METHOD_' . $method);
    }

    /**
     * return response content deflated
     * @return mixed
     */
    public function getResponse()
    {
        return $this->response_content;
    }

    /**
     * @return mixed
     */
    public function getStatusCode()
    {
        return  $this->response instanceof ResponseInterface ? $this->response->getStatusCode() : null;
    }

    /**
     * return decoded response content
     * @return mixed
     */
    public function getDecodedResponse()
    {
        return json_decode($this->response_content,true);
    }

    /**
     * return encoded response content
     * @return mixed
     */
    public function getEncodedResponse()
    {
        return json_encode($this->response_content);
    }


    /**
     * @param string $requestType
     * @param OperationInterface $operatione
     * @return ResponseInterface
     */
    public function execute(string $requestType,OperationInterface $operation) :ResponseInterface
    {

    }



}