<?php
require_once './dompdf/autoload.inc.php';

use Dompdf\Dompdf;

// Initialize Dompdf
$dompdf = new Dompdf();

// Load CSS files
$bootstrap_css = file_get_contents('./bootstrap/css/bootstrap.min.css');
$font_awesome_css = file_get_contents('./font-awesome/css/font-awesome.min.css');

// Generate current date and a random invoice number
$created_date = date('F j, Y');
$invoice_number = rand(1000, 9999);

// Convert logo image to base64
$logo_path = './assets/img/3.png';
$logo_data = base64_encode(file_get_contents($logo_path));
$logo_src = 'data:image/png;base64,' . $logo_data;

// HTML content with inline CSS
$html = '
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <style>
        ' . $bootstrap_css . '
        ' . $font_awesome_css . '
        .invoice-box {
            max-width: 800px;
            margin: auto;
            padding: 30px;
            border: none;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.15);
            font-size: 16px;
            line-height: 24px;
            color: #555;
        }

        .invoice-box table {
            width: 100%;
            line-height: inherit;
            text-align: left;
        }

        .invoice-box table td {
            padding: 5px;
            vertical-align: top;
        }

        .invoice-box table tr td:nth-child(2) {
            text-align: right;
        }

        .invoice-box table tr.top table td {
            padding-bottom: 20px;
        }

        .invoice-box table tr.top table td.title {
            font-size: 45px;
            line-height: 45px;
            color: #333;
        }

        .invoice-box table tr.information table td {
            padding-bottom: 40px;
        }

        .invoice-box table tr.heading td {
            background: #049;
            color: #fff;
            border-bottom: 1px solid #004;
            font-weight: bold;
        }

        .invoice-box table tr.details td {
            padding-bottom: 20px;
        }

        .invoice-box table tr.item td {
            border-bottom: 1px solid #ddd;
        }

        .invoice-box table tr.item.last td {
            border-bottom: none;
        }

        .invoice-box table tr.total td {
            border-top: 2px solid #049;
            font-weight: bold;
        }

        @media only screen and (max-width: 600px) {
            .invoice-box table tr.top table td {
                width: 100%;
                display: block;
                text-align: center;
            }

            .invoice-box table tr.information table td {
                width: 100%;
                display: block;
                text-align: center;
            }
        }

        /** RTL **/
        .invoice-box.rtl {
            direction: rtl;
        }

        .invoice-box.rtl table {
            text-align: right;
        }

        .invoice-box.rtl table tr td:nth-child(2) {
            text-align: left;
        }
    </style>
</head>
<body>
    <div class="invoice-box">
        <table cellpadding="0" cellspacing="0">
            <tr class="top">
                <td colspan="5">
                    <table>
                        <tr>
                            <td class="title">
                                <img
                                    src="' . $logo_src . '"
                                    style="width: 150px; width: 150px"
                                />
                            </td>
                            <td>
								<p style="background: #ddff; color: #000; font-weight: 900; padding: 3px;">
									Invoice #: ' . $invoice_number . '<br />
									Created: ' . $created_date . '<br />
									Due: July 1, 2023
								</p>
                            </td>
                        </tr>
                    </table>
                </td>
            </tr>

            <tr class="information">
                <td colspan="5">
                    <table>
                        <tr>
                            <td>
                                Invoice to:<br />
								Fun-trial company
                                P.O Box 50408-90 <br />
                                Mombasa, Kenya. 
                            </td>
                            <td>
								Finesse Bigwings Limited
                                Acme Corp.<br />
                                John Doe<br />
                                chemiat@finessebigwings.com<br />
								+2547123458549
                            </td>
                        </tr>
                    </table>
                </td>
            </tr>

            <tr class="heading">
                <td>Payment Method</td>
                <td>Check #</td>
            </tr>

            <tr class="details">
                <td>Check</td>
                <td>1000</td>
            </tr>

            <tr class="heading">
				<td>No.</td>
                <td>Item</td>
                <td>Price</td>
                <td>Quantity</td>
                <td>Total</td>
            </tr>

            <tr class="item">
				<td style="background: #0003; font-weight: 900; width: 20px; color: #000; text-align: center;">1</td>
                <td>Website design</td>
                <td>Kshs. 300.00</td>
                <td>1</td>
                <td>Kshs. 300.00</td>
            </tr>

            <tr class="item">
				<td style="background: #0003; font-weight: 900; width: 20px; color: #000; text-align: center;">2</td>
                <td>Hosting (3 months)</td>
                <td>Kshs. 75.00</td>
                <td>1</td>
                <td>Kshs. 75.00</td>
            </tr>

            <tr class="item last">
				<td style="background: #0003; font-weight: 900; width: 20px; color: #000; text-align: center;">3</td>
                <td>Domain name (1 year)</td>
                <td>Kshs. 10.00</td>
                <td>1</td>
                <td>Kshs. 10.00</td>
            </tr>

            <tr class="total">
                <td></td>
				<td></td>
				<td></td>
                <td>Total: </td>
                <td>Kshs. 385.00</td>
            </tr>
        </table>
    </div>
</body>
</html>';

// Load HTML content
$dompdf->loadHtml($html);

// Set paper size and orientation
$dompdf->setPaper('A4', 'portrait');

// Render the HTML as PDF
$dompdf->render();

// Output the generated PDF (1 = download and 0 = preview)
$dompdf->stream("invoice.pdf", ["Attachment" => 1]);
?>
