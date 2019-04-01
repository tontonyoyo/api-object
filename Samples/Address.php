<?php


namespace TontonYoyo\ApiObjectBundle\Samples;

use TontonYoyo\ApiObjectBundle\ApiObject\AbstractApiObject;
use TontonYoyo\ApiObjectBundle\Annotation\ApiObject as AOM;
use TontonYoyo\ApiObjectBundle\Annotation\ApiObjectField as AOMField;


/**
 * @AOM(
 *     name="Address",
 *     api_table_name="addresses",
 *     use_yaml_schema=false,
 *     schema="Address.yml"
 *     )
 */
class Address extends AbstractApiObject
{
    /**
     * @AOMField(
     *     type="integer",
     *     type_out="integer"
     * )
     */
    private $id;

    /**
     * @AOMField()
     */
    private $label;

    /**
     * @AOMField()
     * @var string
     */
    private $address;

    /**
     * @AOMField()
     */
    private $address2;

    /**
     * @AOMField()
     */
    private $lieudit;

    /**
     * @Assert\Length(
     *      max = 10,
     *      maxMessage = "form.address.maxlength"
     * )
     * @AOMField()
     */
    private $zipCode;

    /**
     * @AOMField()
     */
    private $cedex;

    /**
     * @AOMField(
     *   type = "entity",
     *   type_out = "iri",
     *   entity="City"
     * )
     */
    private $city;

    /**
     * @AOMField()
     */
    private $otherCity;

    /**
     * @AOMField()
     */
    private $state;

    /**
     * @AOMField(
     *   type = "entity",
     *   type_out = "iri",
     *   entity="Country"
     * )
     */
    private $country;

    /**
     * @AOMField()
     */
    private $organization;


    public function __toString()
    {
        return $this->address;
    }

    /**
     * @return mixed
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @param mixed $id
     */
    public function setId($id)
    {
        $this->id = $id;
    }

    /**
     * @return mixed
     */
    public function getLabel()
    {
        return $this->label;
    }

    /**
     * @param mixed $internLabel
     */
    public function setLabel($label)
    {
        $this->label = $label;
    }

    /**
     * @return mixed
     */
    public function getAddress()
    {
        return $this->address;
    }

    /**
     * @param mixed $address
     */
    public function setAddress($address)
    {
        $this->address = $address;
    }

    public function getCompleteAddress(){
        $address = [];
        $address[] = $this->address;
        $address[] = $this->address2;
        $address[] = $this->zipCode;
        $address[] = $this->cedex;
        $address[] = $this->lieudit;
        $address[] = $this->state;
        $address[] = $this->city;
        $address[] = $this->otherCity;
        $address[] = $this->country;
        foreach($address as $k => $part){
            if(empty($part)) unset($address[$k]);
        }

        return implode("\n", $address);
    }

    /**
     * @return mixed
     */
    public function getAddress2()
    {
        return $this->address2;
    }

    /**
     * @param mixed $address2
     */
    public function setAddress2($address2)
    {
        $this->address2 = $address2;
    }

    /**
     * @return mixed
     */
    public function getLieudit()
    {
        return $this->lieudit;
    }

    /**
     * @param mixed $lieudit
     */
    public function setLieudit($lieudit)
    {
        $this->lieudit = $lieudit;
    }

    /**
     * @return mixed
     */
    public function getZipCode()
    {
        return $this->zipCode;
    }

    /**
     * @param mixed $zipCode
     */
    public function setZipCode($zipCode)
    {
        $this->zipCode = $zipCode;
    }

    /**
     * @return mixed
     */
    public function getCedex()
    {
        return $this->cedex;
    }

    /**
     * @param mixed $cedex
     */
    public function setCedex($cedex)
    {
        $this->cedex = $cedex;
    }

    /**
     * @return City|null
     */
    public function getCity()
    {

        return $this->city;
    }

    /**
     * @param mixed $city
     */
    public function setCity($city)
    {
        $this->city = $city;
    }

    /**
     * @return mixed
     */
    public function getOtherCity()
    {
        return $this->otherCity;
    }

    /**
     * @param mixed $otherCity
     */
    public function setOtherCity($otherCity)
    {
        $this->otherCity = $otherCity;
    }

    /**
     * @return mixed
     */
    public function getState()
    {
        return $this->state;
    }

    /**
     * @param mixed $state
     */
    public function setState($state)
    {
        $this->state = $state;
    }

    /**
     * @return Country|null
     */
    public function getCountry()
    {
        return $this->country;
    }

    /**
     * @param mixed $country
     */
    public function setCountry($country)
    {
        $this->country = $country;
    }
    /**
     * Constructor
     */
    public function __construct()
    {
        $this->organization = new \Doctrine\Common\Collections\ArrayCollection();
    }


    /**
     * Add organization.
     *
     *
     * @return Address
     */
    public function addOrganization(Organization $organization)
    {
        $this->organization[] = $organization;

        return $this;
    }

    /**
     * Remove organization.
     *
     *
     * @return boolean TRUE if this collection contained the specified element, FALSE otherwise.
     */
    public function removeOrganization(Organization $organization)
    {
        return $this->organization->removeElement($organization);
    }

    /**
     * Get organization.
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getOrganization()
    {
        return $this->organization;
    }

    public function getApiRequestFields()
    {
        return [
            'id',
            'label',
            'address',
            'address2',
            'lieudit',
            'zipCode',
            'city',
            'otherCity',
            'state',
            'country',
            'organization',
        ];
    }


    public function getUnserializedData()
    {
        foreach($this->getApiRequestFields() as $param ){
            if(!is_null($this->$param)){
                $unserializedData[$param]=$this->$param;
            }
        }
        return $unserializedData;
    }

    public function getObjectVars()
    {
        return get_object_vars($this);
    }

}

