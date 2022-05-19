<?php
/**
 * VoucherMassPrintTest.php
 *
 * @copyright Copyright © 2022 Onecode  All rights reserved.
 * @author    Spyros Bodinis {spyros@onecode.gr}
 */

namespace Onecode\ShopFlixConnector\Tests;

use Onecode\ShopFlixConnector\Library\Connector;
use PHPUnit\Framework\TestCase;

class VoucherMassPrintTest extends TestCase
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

        $this->filepath = __DIR__ . "/vouchers/mass_print/";
        if (!is_dir($this->filepath)) mkdir($this->filepath, 0777, true);
    }


    public function testMassPrintPdfVouchers()
    {

        $filename = "format_pdf_shopflix_vouchers-" . implode("_", explode(",", $_ENV['TRACKING_NUMBERS'])) . ".pdf";
        $vouchers = $this->connector->printVouchers(explode(",", $_ENV['TRACKING_NUMBERS']), "pdf");
        $fileContent = base64_decode($vouchers[0]['Voucher']);
        file_put_contents($this->filepath . $filename, $fileContent);
        $this->assertNull($this->fileExists()->evaluate($this->filepath . $filename));
    }

    public function testMassPrintCleanVouchers()
    {
        $filename = "format_clean_shopflix_vouchers-" . implode("_", explode(",", $_ENV['TRACKING_NUMBERS'])) . ".pdf";
        $vouchers = $this->connector->printVouchers(explode(",", $_ENV['TRACKING_NUMBERS']), "clean");
        $fileContent = base64_decode($vouchers[0]['Voucher']);
        file_put_contents($this->filepath . $filename, $fileContent);
        $this->assertNull($this->fileExists()->evaluate($this->filepath . $filename));
    }

    public function testMassPrintSinglePdfVouchers()
    {
        $filename = "format_single_pdf_shopflix_vouchers-" . implode("_", explode(",", $_ENV['TRACKING_NUMBERS'])) . ".pdf";
        $vouchers = $this->connector->printVouchers(explode(",", $_ENV['TRACKING_NUMBERS']), "singlepdf");
        $fileContent = base64_decode($vouchers[0]['Voucher']);
        file_put_contents($this->filepath . $filename, $fileContent);
        $this->assertNull($this->fileExists()->evaluate($this->filepath . $filename));
    }

    public function testMassPrintSingleCleanVouchers()
    {
        $filename = "format_single_clean_shopflix_vouchers-" . implode("_", explode(",", $_ENV['TRACKING_NUMBERS'])) . ".pdf";
        $vouchers = $this->connector->printVouchers(explode(",", $_ENV['TRACKING_NUMBERS']), "singleclean");
        $fileContent = base64_decode($vouchers[0]['Voucher']);
        file_put_contents($this->filepath . $filename, $fileContent);
        $this->assertNull($this->fileExists()->evaluate($this->filepath . $filename));
    }

    public function testMassPrintSinglePdf100x150Vouchers()
    {
        $filename = "format_singlepdf_100x150_shopflix_vouchers-" . implode("_", explode(",", $_ENV['TRACKING_NUMBERS'])) . ".pdf";
        $vouchers = $this->connector->printVouchers(explode(",", $_ENV['TRACKING_NUMBERS']), "singlepdf_100x150");
        $fileContent = base64_decode($vouchers[0]['Voucher']);
        file_put_contents($this->filepath . $filename, $fileContent);
        $this->assertNull($this->fileExists()->evaluate($this->filepath . $filename));
    }
    public function testMassPrintSinglePdf100x170Vouchers()
    {
        $filename = "format_singlepdf_100x170_shopflix_vouchers-" . implode("_", explode(",", $_ENV['TRACKING_NUMBERS'])) . ".pdf";
        $vouchers = $this->connector->printVouchers(explode(",", $_ENV['TRACKING_NUMBERS']), "singlepdf_100x170");
        $fileContent = base64_decode($vouchers[0]['Voucher']);
        file_put_contents($this->filepath . $filename, $fileContent);
        $this->assertNull($this->fileExists()->evaluate($this->filepath . $filename));
    }
}