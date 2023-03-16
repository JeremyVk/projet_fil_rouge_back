<?php

namespace App\Services\Invoice;

use Dompdf\Dompdf;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use App\Entity\Order;

class InvoiceFactory extends AbstractController
{

    public function __construct(
        private string $kernelDir,
    )
    {
    }

    public function generateInvoice(Order $order)
    {
        $domPdf = new Dompdf();

        $html = $this->renderView('Invoice/_invoice.html.twig', ['order' => $order]);
        $domPdf->loadHtml($html);
        $domPdf->setPaper('A4','landscape');
        $domPdf->render();
       
        $output = $domPdf->output();

        $filePath = $this->kernelDir . '/private/files/Invoices/invoices_' . $order->getNumber() . '.pdf';
        file_put_contents($filePath, $output);

        return $filePath;
    }
}