<?php
/**
 * Created by Xavier de Garay.
 * User: degaray
 * Date: 30/12/15
 * Time: 02:43 PM
 */

namespace Degaray\Openpay\Model\Customer;

use Magento\Customer\Api\Data\CustomerInterface;
use Magento\Framework\Exception\LocalizedException;
use Openpay\Client\Adapter\OpenpayCustomerAdapterInterface;
use Openpay\Client\Exception\OpenpayException;
use Magento\Framework\App\CacheInterface;

class OpenpayCustomerRepository implements OpenpayCustomerRepositoryInterface
{
    const CUSTOMER_REQUIRES_ACCOUNT = true;
    const CUSTOMER_CACHE_PREFIX = 'Customer_';
    const CACHE_TIME_SECONDS = 60;

    /**
     * @var OpenpayCustomerAdapterInterface
     */
    protected $customerAdapter;

    /**
     * @var CacheInterface
     */
    protected $cache;

    /**
     * OpenpayCustomerRepository constructor.
     * @param OpenpayCustomerAdapterInterface $customerAdapter
     * @param CacheInterface $cache
     */
    public function __construct(
        OpenpayCustomerAdapterInterface $customerAdapter,
        CacheInterface $cache
    ) {
        $this->customerAdapter = $customerAdapter;
        $this->cache = $cache;
    }

    /**
     * @param CustomerInterface $customer
     * @return \Openpay\Client\Type\OpenpayCustomerType
     * @throws LocalizedException
     */
    public function save(CustomerInterface $customer)
    {
        $params = [
            'name' => $customer->getFirstname(),
            'last_name' => $customer->getLastname(),
            'email' => $customer->getEmail(),
            'requires_account' => self::CUSTOMER_REQUIRES_ACCOUNT,
            'external_id' => $customer->getId(),
        ];

        try {
            $openpayCustomer = $this->customerAdapter->store($params);
        } catch (OpenpayException $e) {
            throw new LocalizedException(__($e->getDescription()), $e);
        }

        return $openpayCustomer;
    }

    /**
     * @param $openpayCustomerId
     * @return mixed|\Openpay\Client\Type\OpenpayCustomerType
     * @throws LocalizedException
     */
    public function get($openpayCustomerId)
    {
        $cacheIdentifier = $this->getCacheIdentifier($openpayCustomerId);

        $openpayCustomer = unserialize($this->cache->load($cacheIdentifier));

        if ($openpayCustomer === false) {
            try {
                $openpayCustomer = $this->customerAdapter->get($openpayCustomerId);
            } catch (OpenpayException $e) {
                $description = $e->getDescription();
                if (is_null($description)) {
                    $description = 'We could not retrieve your payment gateway information.';
                }
                throw new LocalizedException(__($description), $e);
            }

            $this->cache->save(serialize($openpayCustomer), $cacheIdentifier, [], self::CACHE_TIME_SECONDS);
        }

        return $openpayCustomer;
    }

    /**
     * @param $openpayCustomerId
     * @return string
     */
    protected function getCacheIdentifier($openpayCustomerId)
    {
        $cacheIdentifier = md5(self::CUSTOMER_CACHE_PREFIX . $openpayCustomerId);
        return $cacheIdentifier;
    }

    /**
     * @param string $openpayCustomerId
     * @return bool
     */
    public function clearCustomerCache($openpayCustomerId)
    {
        $cacheIdentifier = $this->getCacheIdentifier($openpayCustomerId);

        return $this->cache->remove($cacheIdentifier);
    }
}
