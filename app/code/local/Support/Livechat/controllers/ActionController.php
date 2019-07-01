<?php
/**
 * Livechat Module
 *
 * @category   livechat
 * @package    support_livechat
 * @author     Revathi Ganesh revathi.itbtech@gmail.com
 */
 
class Support_Livechat_ActionController extends Mage_Core_Controller_Front_Action
{
    protected function _getSession()
    {
        return Mage::getSingleton('customer/session');
    }
    
    public function preDispatch()
    {
        parent::preDispatch();

        if (!$this->_getSession()->authenticate($this)) {
            $this->setFlag('', 'no-dispatch', true);
        }
    }
    
    public function postAction()
    {
        //Get form (post) data
        $data = $this->getRequest()->getPost();
        
        //Validate form key
        if (!$this->_validateFormKey())
            Mage::throwException('Invalid form key.');
        
        //Set default value for variables
        $livechat_license_number = (!isset($data['livechat_license_number'])) ?  '' : $data['livechat_license_number'];
        $livechat_groups         = (!isset($data['livechat_groups'])) ? '0' : $data['livechat_groups'];
        $livechat_params         = (!isset($data['livechat_params'])) ? '' : $data['livechat_params'];
        
        //Exception handling
        try {
            // Get stored value from store config - system.xml ['livechat/general/license]'
            $path = Mage::getStoreConfig('livechat/path/url'); 
            $id = Mage::getResourceModel('core/config_data_collection')
                    ->addFieldToFilter('path', $path)
                    ->getFirstItem()
                    ->getId();
            $config_table = Mage::getSingleton('core/resource')->getTableName('core_config_data');
            $writeAdapter = Mage::getSingleton('core/resource')->getConnection('core_write');  
            
            //update for existing records
            if (isset($id)) {
                $writeAdapter->update($config_table, array("value" => $livechat_license_number), "config_id=$id");
                $writeAdapter->update($config_table, array("value" => $livechat_groups), "config_id=".++$id);
                $writeAdapter->update($config_table, array("value" => $livechat_params), "config_id=".++$id);  
            } else { //adding new records
                $config = Mage::getConfig();
                $config->saveConfig('livechat/general/license', $livechat_license_number, 'default', 0);
                $config->saveConfig('livechat/advanced/group', 0, 'default', 0);
                $config->saveConfig('livechat/advanced/params', "", 'default', 0);
            }

            $this->_getSession()->addSuccess('Data save successfully.');
            $this->_redirect('*/*/index');
        } catch(Exception $e) {
            $this->_getSession()->addError($e->getMessage());
            $this->_redirect('*/*/index', array('_current' => true));
        }   
    }
}