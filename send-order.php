<?php
// Настройки
$to_email = "antfckw@gmail.com";
$subject = "Новое замовлення з сайту effectCBD";

// Проверка метода запроса
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    
    // Получение данных из формы
    $product = isset($_POST['product']) ? htmlspecialchars($_POST['product']) : '';
    $price = isset($_POST['price']) ? htmlspecialchars($_POST['price']) : '';
    $name = isset($_POST['customerName']) ? htmlspecialchars($_POST['customerName']) : '';
    $phone = isset($_POST['customerPhone']) ? htmlspecialchars($_POST['customerPhone']) : '';
    $email = isset($_POST['customerEmail']) ? htmlspecialchars($_POST['customerEmail']) : '';
    $delivery = isset($_POST['deliveryMethod']) ? htmlspecialchars($_POST['deliveryMethod']) : '';
    $city = isset($_POST['customerCity']) ? htmlspecialchars($_POST['customerCity']) : '';
    $address = isset($_POST['customerAddress']) ? htmlspecialchars($_POST['customerAddress']) : '';
    $comment = isset($_POST['customerComment']) ? htmlspecialchars($_POST['customerComment']) : '';
    
    // Перевод способа доставки
    $delivery_methods = [
        'nova-poshta' => 'Нова Пошта',
        'ukrposhta' => 'Укрпошта',
        'courier' => 'Кур\'єр по Києву'
    ];
    $delivery_text = isset($delivery_methods[$delivery]) ? $delivery_methods[$delivery] : $delivery;
    
    // Формирование письма
    $message = "
    <html>
    <head>
        <style>
            body { font-family: Arial, sans-serif; line-height: 1.6; color: #333; }
            .container { max-width: 600px; margin: 0 auto; padding: 20px; }
            .header { background: #C9A86A; color: #fff; padding: 20px; text-align: center; border-radius: 8px 8px 0 0; }
            .content { background: #f9f9f9; padding: 30px; border: 1px solid #ddd; }
            .product-info { background: #fff; padding: 20px; margin-bottom: 20px; border-left: 4px solid #C9A86A; }
            .info-row { margin-bottom: 15px; }
            .label { font-weight: bold; color: #555; }
            .value { color: #333; }
            .footer { background: #333; color: #fff; padding: 15px; text-align: center; font-size: 12px; border-radius: 0 0 8px 8px; }
        </style>
    </head>
    <body>
        <div class='container'>
            <div class='header'>
                <h2>🛒 Нове замовлення з сайту effectCBD</h2>
            </div>
            <div class='content'>
                <div class='product-info'>
                    <h3 style='margin-top: 0; color: #C9A86A;'>📦 Товар</h3>
                    <div class='info-row'>
                        <span class='label'>Назва:</span> <span class='value'>{$product}</span>
                    </div>
                    <div class='info-row'>
                        <span class='label'>Ціна:</span> <span class='value' style='font-size: 18px; color: #C9A86A; font-weight: bold;'>{$price}</span>
                    </div>
                </div>
                
                <h3 style='color: #C9A86A;'>👤 Дані клієнта</h3>
                <div class='info-row'>
                    <span class='label'>Ім'я:</span> <span class='value'>{$name}</span>
                </div>
                <div class='info-row'>
                    <span class='label'>Телефон:</span> <span class='value'>{$phone}</span>
                </div>
                <div class='info-row'>
                    <span class='label'>Email:</span> <span class='value'>{$email}</span>
                </div>
                
                <h3 style='color: #C9A86A;'>🚚 Доставка</h3>
                <div class='info-row'>
                    <span class='label'>Спосіб доставки:</span> <span class='value'>{$delivery_text}</span>
                </div>
                <div class='info-row'>
                    <span class='label'>Місто:</span> <span class='value'>{$city}</span>
                </div>
                <div class='info-row'>
                    <span class='label'>Адреса:</span> <span class='value'>{$address}</span>
                </div>
                
                " . ($comment ? "<h3 style='color: #C9A86A;'>💬 Коментар</h3><div class='info-row'><span class='value'>{$comment}</span></div>" : "") . "
                
                <div style='margin-top: 30px; padding-top: 20px; border-top: 2px solid #C9A86A;'>
                    <p style='margin: 0; color: #666;'><strong>Дата замовлення:</strong> " . date('d.m.Y H:i:s') . "</p>
                </div>
            </div>
            <div class='footer'>
                <p style='margin: 0;'>effectCBD - Український виробник преміальної CBD продукції</p>
            </div>
        </div>
    </body>
    </html>
    ";
    
    // Заголовки для HTML письма
    $headers = "MIME-Version: 1.0" . "\r\n";
    $headers .= "Content-type:text/html;charset=UTF-8" . "\r\n";
    $headers .= "From: effectCBD <noreply@effectcbd.ua>" . "\r\n";
    
    // Если клиент указал email, добавляем Reply-To
    if (!empty($email)) {
        $headers .= "Reply-To: {$email}" . "\r\n";
    }
    
    // Отправка письма
    if (mail($to_email, $subject, $message, $headers)) {
        // Успешная отправка
        echo json_encode([
            'success' => true,
            'message' => 'Дякуємо за замовлення! Ми зв\'яжемося з вами найближчим часом.'
        ]);
    } else {
        // Ошибка отправки
        http_response_code(500);
        echo json_encode([
            'success' => false,
            'message' => 'Помилка відправки. Спробуйте пізніше або зателефонуйте нам.'
        ]);
    }
    
} else {
    // Неверный метод запроса
    http_response_code(405);
    echo json_encode([
        'success' => false,
        'message' => 'Метод не дозволений'
    ]);
}
?>
