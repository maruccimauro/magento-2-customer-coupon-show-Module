<?php
namespace Mauro\CustomerCouponShow\Model\Config\Source;

use Magento\Framework\Option\ArrayInterface;
use Magento\Catalog\Model\ResourceModel\Category\CollectionFactory;

class Category implements ArrayInterface
{
    protected $categoryCollectionFactory;

    public function __construct(
        CollectionFactory $categoryCollectionFactory
    ) {
        $this->categoryCollectionFactory = $categoryCollectionFactory;
    }

    public function toOptionArray()
    {
        $categories = $this->categoryCollectionFactory->create()
            ->addAttributeToSelect('name')
            ->addAttributeToSort('name', 'ASC')
            ->addIsActiveFilter();

        $options = [
            ['value' => '', 'label' => __('-- Please Select --')]
        ];

        foreach ($categories as $category) {
            if ($category->getId() == 1) continue;
            
            $options[] = [
                'value' => $category->getId(),
                'label' => $this->getCategoryPath($category)
            ];
        }

        return $options;
    }

    protected function getCategoryPath($category)
    {
        $path = $category->getName();
        
        if ($category->getLevel() > 2) {
            $parentIds = explode('/', $category->getPath());
            array_shift($parentIds); 
            array_shift($parentIds); 
            array_pop($parentIds); 
            
            if (!empty($parentIds)) {
                $parentCollection = $this->categoryCollectionFactory->create()
                    ->addAttributeToSelect('name')
                    ->addFieldToFilter('entity_id', ['in' => $parentIds]);
                
                $parentNames = [];
                foreach ($parentCollection as $parent) {
                    $parentNames[] = $parent->getName();
                }
                
                $path = implode(' > ', $parentNames) . ' > ' . $category->getName();
            }
        }
        
        return $path . ' (ID: ' . $category->getId() . ')';
    }
}