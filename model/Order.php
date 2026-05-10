<?php

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

class Order {

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

            // SMTP
            $mail->Host = $_ENV['MAIL_HOST'];
            $mail->SMTPAuth = true;

            $mail->Username = $_ENV['MAIL_USERNAME'];
            $mail->Password = $_ENV['MAIL_PASSWORD'];

            $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
            $mail->Port = (int)$_ENV['MAIL_PORT'];

            $mail->CharSet = 'UTF-8';

            // sender
            $mail->setFrom(
                $_ENV['MAIL_FROM'],
                $_ENV['MAIL_FROM_NAME']
            );

            // receiver
            $mail->addAddress($_ENV['MAIL_TO']);

            // reply
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