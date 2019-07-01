<?php
/**
 * Livechat Module
 *
 * @category   sales
 * @package    Support_Livechat
 * @author     Revathi Ganesh revathi.itbtech@gmail.com
 */
require_once 'Mage/Adminhtml/controllers/Sales/Order/StatusController.php';
class Support_Livechat_Adminhtml_Sales_Order_StatusController extends Mage_Adminhtml_Sales_Order_StatusController
{    
    /**
     * Export order grid to CSV format
     */
    public function exportCsvAction()
    {
        $fileName   = 'status.csv';
        $grid       = $this->getLayout()->createBlock('livechat/adminhtml_sales_order_status_grid');
        $this->_prepareDownloadResponse($fileName, $grid->getCsvFile());
    }
}