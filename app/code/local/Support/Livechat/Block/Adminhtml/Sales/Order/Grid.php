<?php
/**
 * Livechat Module
 *
 * @category   sales
 * @package    Support_Livechat
 * @author     Revathi Ganesh revathi.itbtech@gmail.com
 */

class Support_Livechat_Block_Adminhtml_Sales_Order_Grid extends Mage_Adminhtml_Block_Sales_Order_Grid
{              
    protected function _prepareColumns()
    {       
        $this->addExportType('*/*/exportCsv', Mage::helper('sales')->__('CSV'));
        return parent::_prepareColumns();      
    }
}