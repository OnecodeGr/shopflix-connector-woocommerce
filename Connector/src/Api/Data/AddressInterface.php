<?php
/**
 * AddressInterface.php
 *
 * @copyright Copyright © 2021 Onecode  All rights reserved.
 * @author    Spyros Bodinis {spyros@onecode.gr}
 */

namespace Onecode\MarketplaceConnector\Library\Api\Data;

/**
 * @method getData(string $attribute)
 */
interface AddressInterface
{
    /**
     * Entity ID.
     */
    const ENTITY_ID = 'entity_id';

    /**
     * Parent ID.
     */
    const PARENT_ID = 'parent_id';


    /**
     * Region ID.
     */
    const REGION_ID = 'region_id';

    /**
     * Region code.
     */
    const KEY_REGION_CODE = 'region_code';


    /**
     * Fax.
     */
    const FAX = 'fax';

    /**
     * Region.
     */
    const REGION = 'region';

    /**
     * Postal code.
     */
    const POSTCODE = 'postcode';

    /**
     * Last name.
     */
    const LASTNAME = 'lastname';

    /**
     * Street.
     */
    const STREET = 'street';

    /**
     * City.
     */
    const CITY = 'city';

    /**
     * Email address.
     */
    const EMAIL = 'email';

    /**
     * Telephone number.
     */
    const TELEPHONE = 'telephone';

    /**
     * Country ID.
     */
    const COUNTRY_ID = 'country_id';

    /**
     * First name.
     */
    const FIRSTNAME = 'firstname';

    /**
     * Address type.
     */
    const ADDRESS_TYPE = 'address_type';

    /**
     * Prefix.
     */
    const PREFIX = 'prefix';

    /**
     * Middle name.
     */
    const MIDDLENAME = 'middlename';

    /**
     * Suffix.
     */
    const SUFFIX = 'suffix';

    /**
     * Company.
     */
    const COMPANY = 'company';

    const ATTRIBUTES = [
        self::FAX,
        self::REGION,
        self::POSTCODE,
        self::LASTNAME,
        self::FIRSTNAME,
        self::STREET,
        self::CITY,
        self::EMAIL,
        self::TELEPHONE,
        self::COUNTRY_ID,
    ];


    /**
     * Gets the address type for the order address.
     *
     * @return string Address type.
     */
    public function getAddressType();

    /**
     * Gets the city for the order address.
     *
     * @return string City.
     */
    public function getCity();

    /**
     * Gets the company for the order address.
     *
     * @return string|null Company.
     */
    public function getCompany();

    /**
     * Gets the country ID for the order address.
     *
     * @return string Country ID.
     */
    public function getCountryId();

    /**
     * Gets the email address for the order address.
     *
     * @return string|null Email address.
     */
    public function getEmail();

    /**
     * Gets the ID for the order address.
     *
     * @return int|null Order address ID.
     */
    public function getEntityId();

    /**
     * Sets the ID for the order address.
     *
     * @param int $entityId
     * @return $this
     */
    public function setEntityId($entityId);

    /**
     * Gets the fax number for the order address.
     *
     * @return string|null Fax number.
     */
    public function getFax();

    /**
     * Gets the first name for the order address.
     *
     * @return string First name.
     */
    public function getFirstname();

    /**
     * Gets the last name for the order address.
     *
     * @return string Last name.
     */
    public function getLastname();


    /**
     * Gets the parent ID for the order address.
     *
     * @return int|null Parent ID.
     */
    public function getParentId();

    /**
     * Gets the postal code for the order address.
     *
     * @return string Postal code.
     */
    public function getPostcode();


    /**
     * Gets the region for the order address.
     *
     * @return string|null Region.
     */
    public function getRegion();

    /**
     * Gets the region code for the order address
     *
     * @return string|null Region code.
     */
    public function getRegionCode();

    /**
     * Gets the region ID for the order address.
     *
     * @return int|null Region ID.
     */
    public function getRegionId();

    /**
     * Gets the street values, if any, for the order address.
     *
     * @return string[]|null Array of any street values. Otherwise, null.
     */
    public function getStreet();


    /**
     * Gets the telephone number for the order address.
     *
     * @return string Telephone number.
     */
    public function getTelephone();


    /**
     * Sets the parent ID for the order address.
     *
     * @param int $id
     * @return $this
     */
    public function setParentId($id);


    /**
     * Sets the region ID for the order address.
     *
     * @param int $id
     * @return $this
     */
    public function setRegionId($id);


    /**
     * Sets the fax number for the order address.
     *
     * @param string $fax
     * @return $this
     */
    public function setFax($fax);

    /**
     * Sets the region for the order address.
     *
     * @param string $region
     * @return $this
     */
    public function setRegion($region);

    /**
     * Sets the postal code for the order address.
     *
     * @param string $postcode
     * @return $this
     */
    public function setPostcode($postcode);

    /**
     * Sets the last name for the order address.
     *
     * @param string $lastname
     * @return $this
     */
    public function setLastname($lastname);

    /**
     * Sets the street values, if any, for the order address.
     *
     * @param string|string[] $street
     * @return $this
     */
    public function setStreet($street);

    /**
     * Sets the city for the order address.
     *
     * @param string $city
     * @return $this
     */
    public function setCity($city);

    /**
     * Sets the email address for the order address.
     *
     * @param string $email
     * @return $this
     */
    public function setEmail($email);

    /**
     * Sets the telephone number for the order address.
     *
     * @param string $telephone
     * @return $this
     */
    public function setTelephone($telephone);

    /**
     * Sets the country ID for the order address.
     *
     * @param string $id
     * @return $this
     */
    public function setCountryId($id);

    /**
     * Sets the first name for the order address.
     *
     * @param string $firstname
     * @return $this
     */
    public function setFirstname($firstname);

    /**
     * Sets the address type for the order address.
     *
     * @param string $addressType
     * @return $this
     */
    public function setAddressType($addressType);


    /**
     * Sets the company for the order address.
     *
     * @param string $company
     * @return $this
     */
    public function setCompany($company);


    /**
     * Set region code
     *
     * @param string $regionCode
     * @return $this
     */
    public function setRegionCode($regionCode);


    /**
     * @param OrderInterface $order
     * @return mixed
     */
    public function setOrder(OrderInterface $order);
}
