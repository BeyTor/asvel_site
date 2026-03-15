<?php

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require 'PHPMailer/src/Exception.php';
require 'PHPMailer/src/PHPMailer.php';
require 'PHPMailer/src/SMTP.php';

if ($_SERVER["REQUEST_METHOD"] !== "POST") {
    header("Location: iletisim.html");
    exit;
}

if (!empty($_POST['website'])) {
    exit;
}

$name = trim($_POST["name"] ?? "");
$email = trim($_POST["email"] ?? "");
$phone = trim($_POST["phone"] ?? "");
$message = trim($_POST["message"] ?? "");

if ($name === "" || $email === "" || $message === "") {
    echo "<script>alert('Lütfen zorunlu alanları doldurun.');window.location.href='iletisim.html';</script>";
    exit;
}

if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    echo "<script>alert('Lütfen geçerli bir e-posta adresi girin.');window.location.href='iletisim.html';</script>";
    exit;
}

try {
    // 1) INFO MAİLİNE GELEN ASIL MESAJ
    $mail = new PHPMailer(true);
    $mail->isSMTP();
    $mail->Host = 'SMTP_HOST_BURAYA';
    $mail->SMTPAuth = true;
    $mail->Username = 'INFO_MAIL_BURAYA';
    $mail->Password = 'SMTP_PASSWORD_BURAYA';
    $mail->SMTPSecure = PHPMailer::ENCRYPTION_SMTPS;
    $mail->Port = 465;
    $mail->CharSet = 'UTF-8';

    $mail->setFrom('INFO_MAIL_BURAYA', 'ASVEL Website');
    $mail->addAddress('INFO_MAIL_BURAYA');
    $mail->addReplyTo($email, $name);

    $mail->Subject = 'Web Sitesinden Yeni Mesaj';
    $mail->Body = "Yeni web sitesi mesajı\n\n"
        . "Ad Soyad: $name\n"
        . "E-posta: $email\n"
        . "Telefon: $phone\n\n"
        . "Mesaj:\n$message";

    $mail->send();

    // 2) MÜŞTERİYE GİDEN OTOMATİK CEVAP (NOREPLY)
    $autoReply = new PHPMailer(true);
    $autoReply->isSMTP();
    $autoReply->Host = 'SMTP_HOST_BURAYA';
    $autoReply->SMTPAuth = true;
    $autoReply->Username = 'NOREPLY_MAIL_BURAYA';
    $autoReply->Password = 'SMTP_PASSWORD_BURAYA';
    $autoReply->SMTPSecure = PHPMailer::ENCRYPTION_SMTPS;
    $autoReply->Port = 465;
    $autoReply->CharSet = 'UTF-8';

    $autoReply->setFrom('NOREPLY_MAIL_BURAYA', 'ASVEL Asansör');
    $autoReply->addAddress($email, $name);
    $autoReply->isHTML(true);
    $autoReply->addEmbeddedImage('images/logo.png', 'asvellogo');

    $autoReply->Subject = 'ASVEL Asansör | Mesajınız Tarafımıza Ulaşmıştır';

    $autoReply->Body = '
    <div style="font-family: Arial, sans-serif; font-size: 14px; color: #333333; line-height: 1.6;">
        
        <div style="text-align:center; margin-bottom:25px;">
        <img src="cid:asvellogo" alt="ASVEL Asansör" style="width:320px; height:auto;">
        </div>

        <p>Merhaba <strong>' . htmlspecialchars($name, ENT_QUOTES, 'UTF-8') . '</strong>,</p>

        <p>
            ASVEL Asansör web sitesi üzerinden ilettiğiniz mesaj tarafımıza başarıyla ulaşmıştır.<br>
            Talebiniz en kısa sürede incelenerek sizinle iletişime geçilecektir.
        </p>

        <p>Bilginize sunar, iyi günler dileriz.</p>

        <hr style="border: none; border-top: 1px solid #dddddd; margin: 24px 0;">

        <p style="margin: 0 0 12px 0;"><strong>ŞİRKET_ADI_BURAYA</strong></p>

        <p style="margin: 0 0 12px 0;">
            <strong>Telefon</strong><br>
            [TELEFON_NUMARASI_1]<br>
            [TELEFON_NUMARASI_2]
        </p>

        <p style="margin: 0 0 12px 0;">
            <strong>Adres</strong><br>
            [ADRES_BİLGİSİ_BURAYA]
        </p>

        <p style="margin: 0 0 12px 0;">
            <strong>Web</strong><br>
            <a href="https://www.ornekdomain.com" style="color: #333333; text-decoration: none;">www.ornekdomain.com</a>
        </p>

        <p style="font-size: 12px; color: #777777; margin-top: 24px;">
            Bu e-posta otomatik olarak gönderilmiştir. Lütfen bu mesaja yanıt vermeyiniz.
        </p>
    </div>
    ';

    $autoReply->AltBody = "Merhaba $name,\n\n"
        . "ASVEL Asansör web sitesi üzerinden ilettiğiniz mesaj tarafımıza başarıyla ulaşmıştır.\n"
        . "Talebiniz en kısa sürede incelenerek sizinle iletişime geçilecektir.\n\n"
        . "Bilginize sunar, iyi günler dileriz.\n\n"
        . "--\n"
        . "ŞİRKET_ADI_BURAYA\n\n"
        . "Telefon\n"
        . "[TELEFON_NUMARASI_1]\n"
        . "[TELEFON_NUMARASI_2]\n\n"
        . "Adres\n"
        . "[ADRES_BİLGİSİ_BURAYA]\n\n"
        . "Web\n"
        . "www.ornekdomain.com\n\n"
        . "Bu e-posta otomatik olarak gönderilmiştir. Lütfen bu mesaja yanıt vermeyiniz.";

    $autoReply->send();

    echo "<script>alert('Mesaj başarıyla gönderildi.');window.location.href='iletisim.html';</script>";
} catch (Exception $e) {
    echo "Hata oluştu.";
}
?>