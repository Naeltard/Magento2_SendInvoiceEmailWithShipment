<?php

namespace NBG\Mailtrigger\Observer;

use Magento\Framework\Event\ObserverInterface;
use Magento\Sales\Model\Order;
use Magento\Sales\Model\Order\Email\Sender\InvoiceSender;

class SendInvoiceWithShipment implements ObserverInterface
{
    protected $_invoiceSender;

    public function __construct(
        InvoiceSender $invoiceSender
    ) {
        $this->_invoiceSender = $invoiceSender;
    }

    public function execute(\Magento\Framework\Event\Observer $observer)
    {
        $order = $observer->getShipment()->getOrder();
        if (!$order) {
            // Dont send invoice if order is not provided 
            return; 
        }

        $invoices = $order->getInvoiceCollection();

        foreach ($invoices as $invoice) {

                try {
                    $this->_invoiceSender->send($invoice);
                } catch (\Exception $e) {
                    // Do something if failed to send                          
                }

        }               

        
    }
}