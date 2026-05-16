<?php
/**
 * File: model/Order.php
 * Purpose: Handles order processing, including sending email notifications and updating art availability.
 */

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

/**
 * Class Order
 * 
 * Manages the submission of customer orders, sending details via SMTP using PHPMailer.
 */
class Order {

    /**
     * Sends an order confirmation email and marks art pieces as sold in the database.
     * 
     * @param string $name Customer's name.
     * @param string $email Customer's email address.
     * @param string $phone Customer's phone number.
     * @param string $message Additional message from the customer.
     * @param array $items Array of ordered art piece data.
     * @param array $ids Array of ordered art piece IDs.
     * @return array Returns [true] on success, or [false, error_message] on failure.
     */
    public static function send(
        string $name,
        string $email,
        string $phone,
        string $message,
        array $items,
        array $ids
    ): array {

        try {

            $mail = new PHPMailer(true);

            $mail->isSMTP();

            // SMTP Configuration
            $mail->Host = $_ENV['MAIL_HOST'];
            $mail->SMTPAuth = true;

            $mail->Username = $_ENV['MAIL_USERNAME'];
            $mail->Password = $_ENV['MAIL_PASSWORD'];

            $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
            $mail->Port = (int)$_ENV['MAIL_PORT'];

            $mail->CharSet = 'UTF-8';

            // Sender Information
            $mail->setFrom(
                $_ENV['MAIL_FROM'],
                $_ENV['MAIL_FROM_NAME']
            );

            // Receiver Information
            $mail->addAddress($_ENV['MAIL_TO']);

            // Reply-To Information
            $mail->addReplyTo($email, $name);

            $mail->isHTML(true);

            $mail->Subject = 'Uus tellimus veebilehelt "Vihmart"';

            $total = 0;

            $products = '';

            foreach ($items as $item) {

                $price = (float)$item['art_price'];

                $total += $price;

                $products .= '
                    <tr>
                        <td style="border:1px solid #ccc;padding:8px;">
                            ' . htmlspecialchars($item['art_title']) . '
                        </td>

                        <td style="border:1px solid #ccc;padding:8px;">
                            ' . htmlspecialchars($item['category_title']) . '
                        </td>

                        <td style="border:1px solid #ccc;padding:8px;">
                            ' . number_format($price, 2) . ' €
                        </td>
                    </tr>
                ';
            }

            // Email Body Content
            $mail->Body = '
                <h2>Uus tellimus</h2>

                <p>
                    <strong>Nimi:</strong>
                    ' . htmlspecialchars($name) . '
                </p>

                <p>
                    <strong>Email:</strong>
                    ' . htmlspecialchars($email) . '
                </p>

                <p>
                    <strong>Telefon:</strong>
                    ' . htmlspecialchars($phone) . '
                </p>

                <p>
                    <strong>Sõnum:</strong><br>
                    ' . nl2br(htmlspecialchars($message)) . '
                </p>

                <h3>Tooted</h3>

                <table style="border-collapse:collapse;width:100%;">
                    <tr>
                        <th style="border:1px solid #ccc;padding:8px;">
                            Pealkiri
                        </th>

                        <th style="border:1px solid #ccc;padding:8px;">
                            Kategooria
                        </th>

                        <th style="border:1px solid #ccc;padding:8px;">
                            Hind
                        </th>
                    </tr>

                    ' . $products . '

                </table>

                <h3 style="margin-top:20px;">
                    Kokku: ' . number_format($total, 2) . ' €
                </h3>
            ';

            $mail->send();

            $db = new Database();

            // Mark ordered items as sold (remove from shop)
            if (!empty($ids)) {
                $placeholders = implode(',', array_fill(0, count($ids), '?'));

                $sql = "UPDATE arts SET in_shop = 0 WHERE id IN ($placeholders)";

                $db->executeRun($sql, $ids);
            }

            return [true];

        } catch (Exception $e) {

            return [false, $mail->ErrorInfo];
        }
    }
}
