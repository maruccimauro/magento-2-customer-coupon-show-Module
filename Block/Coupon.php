<?php
namespace Mauro\CustomerCouponShow\Block;

use Magento\Framework\View\Element\Template;
use Magento\Framework\App\Config\ScopeConfigInterface;
use Magento\Store\Model\ScopeInterface;
use Magento\Framework\Registry;

class Coupon extends Template
{
    const XML_PATH_ENABLED = 'discount_coupons_section/general/enabled';
    const XML_PATH_CATEGORY_ID = 'discount_coupons_section/general/category_id';
    const XML_PATH_DELAY_OPEN = 'discount_coupons_section/general/delay_open';    
    const XML_PATH_DISCOUNT = 'discount_coupons_section/general/discount';
    const XML_PATH_EXPIRATION = 'discount_coupons_section/general/expiration';

    protected $scopeConfig;
    protected $registry;

    public function __construct(
        Template\Context $context,
        ScopeConfigInterface $scopeConfig,
        Registry $registry,
        array $data = []
    ) {
        parent::__construct($context, $data);
        $this->scopeConfig = $scopeConfig;
        $this->registry = $registry;
    }

    public function getEnabled()
    {
        return $this->scopeConfig->getValue(
            self::XML_PATH_ENABLED,
            ScopeInterface::SCOPE_STORE
        );
    }

    public function getCategoryId()
    {
        return $this->scopeConfig->getValue(
            self::XML_PATH_CATEGORY_ID,
            ScopeInterface::SCOPE_STORE
        );
    }

    public function getDelayOpen()
    {
        return $this->scopeConfig->getValue(
            self::XML_PATH_DELAY_OPEN,
            ScopeInterface::SCOPE_STORE
        );
    }

    public function getDiscount()
    {
        return $this->scopeConfig->getValue(
            self::XML_PATH_DISCOUNT,
            ScopeInterface::SCOPE_STORE
        );
    }

    public function getExpiration()
    {
        return $this->scopeConfig->getValue(
            self::XML_PATH_EXPIRATION,
            ScopeInterface::SCOPE_STORE
        );
    }
    
    public function getCurrentCategory()
    {
        $category = $this->registry->registry('current_category');
        return $category ? $category->getId() : null;
    }

    public function shouldRender(): bool
    {
        if (!$this->getEnabled()) return false;
        
        $configCategoryId = $this->getCategoryId();
        $currentCategoryId = $this->getCurrentCategory();

        
        if ($configCategoryId != $currentCategoryId) return false;
        return true;
    }
}