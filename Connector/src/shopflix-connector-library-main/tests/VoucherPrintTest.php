<?php
/**
 * VoucherPrintTest.php
 *
 * @copyright Copyright © 2022 Onecode  All rights reserved.
 * @author    Spyros Bodinis {spyros@onecode.gr}
 */

namespace Onecode\ShopFlixConnector\Tests;

use Onecode\ShopFlixConnector\Library\Connector;
use PHPUnit\Framework\TestCase;

class VoucherPrintTest extends TestCase
{


    private $connector;
    /**
     * @var string
     */
    private $filepath;

    public function setUp(): void
    {
        parent::setUp();
        $this->connector = new Connector($_ENV['USERNAME'], $_ENV['PASSWORD'], $_ENV["API_URL"],"-7 days");
        $this->filepath = __DIR__ . "/vouchers/print/";
        if (!is_dir($this->filepath)) mkdir($this->filepath, 0777, true);
    }

    public function testPrintPdfVoucher()
    {

        $filename = "format_pdf_shopflix_vouchers-" . $_ENV['TRACKING_NUMBER'] . ".pdf";

        $voucher = $this->connector->printVoucher($_ENV['TRACKING_NUMBER'], "pdf");
        $fileContent = base64_decode($voucher['Voucher']);
        file_put_contents($this->filepath . $filename, $fileContent);
        $this->assertNull($this->fileExists()->evaluate($this->filepath . $filename));

    }

    public function testPrintCleanVoucher()
    {
        $filename = "format_clean_shopflix_vouchers-" . $_ENV['TRACKING_NUMBER'] . ".pdf";
        $voucher = $this->connector->printVoucher($_ENV['TRACKING_NUMBER'], "clean");
        $fileContent = base64_decode($voucher['Voucher']);
        file_put_contents($this->filepath . $filename, $fileContent);
        $this->assertNull($this->fileExists()->evaluate($this->filepath . $filename));
    }


    public function testSinglePdfVoucher()
    {
        $filename = "format_singlepdf_shopflix_vouchers-" . $_ENV['TRACKING_NUMBER'] . ".pdf";
        $voucher = $this->connector->printVoucher($_ENV['TRACKING_NUMBER'], "singlepdf");
        $fileContent = base64_decode($voucher['Voucher']);
        file_put_contents($this->filepath . $filename, $fileContent);
        $this->assertNull($this->fileExists()->evaluate($this->filepath . $filename));
    }

    public function testPrintSingleCleanVoucher()
    {
        $filename = "format_singleclean_shopflix_vouchers-" . $_ENV['TRACKING_NUMBER'] . ".pdf";
        $voucher = $this->connector->printVoucher($_ENV['TRACKING_NUMBER'], "singleclean");
        $fileContent = base64_decode($voucher['Voucher']);
        file_put_contents($this->filepath . $filename, $fileContent);
        $this->assertNull($this->fileExists()->evaluate($this->filepath . $filename));
    }


    public function testPrintSinglePdf100x150Voucher()
    {
        $filename = "format_singlepdf_100x150_shopflix_vouchers-" . $_ENV['TRACKING_NUMBER'] . ".pdf";
        $voucher = $this->connector->printVoucher($_ENV['TRACKING_NUMBER'], "singlepdf_100x150");
        $fileContent = base64_decode($voucher['Voucher']);
        file_put_contents($this->filepath . $filename, $fileContent);
        $this->assertNull($this->fileExists()->evaluate($this->filepath . $filename));
    }

    public function testPrintSinglePdf100x170Voucher()
    {
        $filename = "format_singlepdf_100x170_shopflix_vouchers-" . $_ENV['TRACKING_NUMBER'] . ".pdf";
        $voucher = $this->connector->printVoucher($_ENV['TRACKING_NUMBER'], "singlepdf_100x170");
        $fileContent = base64_decode($voucher['Voucher']);
        file_put_contents($this->filepath . $filename, $fileContent);
        $this->assertNull($this->fileExists()->evaluate($this->filepath . $filename));
    }
}