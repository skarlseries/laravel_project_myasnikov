<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="x-apple-disable-message-reformatting">
    <title>Ежедневная статистика сайта</title>
</head>
<body style="margin:0; padding:0; width:100%; background-color:#f3f6fc; font-family:Arial, Helvetica, sans-serif; color:#182230;">
    <table role="presentation" cellpadding="0" cellspacing="0" border="0" width="100%" style="width:100%; background-color:#f3f6fc; border-collapse:collapse;">
        <tr>
            <td align="center" style="padding:32px 16px;">
                <table role="presentation" cellpadding="0" cellspacing="0" border="0" width="600" style="width:100%; max-width:600px; border-collapse:separate; border-spacing:0;">
                    <tr>
                        <td style="padding:22px 28px; background-color:#3157d5; border-radius:18px 18px 0 0;">
                            <div style="font-size:22px; line-height:28px; font-weight:700; color:#ffffff;">Мой сайт</div>
                            <div style="padding-top:5px; font-size:12px; line-height:18px; color:#e5ebff;">Ежедневный отчёт для модераторов</div>
                        </td>
                    </tr>
                    <tr>
                        <td style="padding:32px 28px 28px; background-color:#ffffff; border-left:1px solid #e4e9f3; border-right:1px solid #e4e9f3;">
                            <div style="display:inline-block; padding:6px 11px; background-color:#edf2ff; border-radius:20px; color:#3157d5; font-size:11px; line-height:16px; font-weight:700; letter-spacing:0.6px; text-transform:uppercase;">Статистика сайта</div>
                            <h1 style="margin:18px 0 10px; font-size:25px; line-height:32px; font-weight:700; color:#182230;">Ежедневная статистика</h1>
                            <p style="margin:0 0 24px; font-size:15px; line-height:24px; color:#667085;">Сводка активности сайта за <strong style="color:#344054;">{{ $reportDate }}</strong>.</p>

                            <table role="presentation" cellpadding="0" cellspacing="0" border="0" width="100%" style="width:100%; border-collapse:separate; border-spacing:0;">
                                <tr>
                                    <td width="50%" valign="top" style="width:50%; padding:0 7px 0 0;">
                                        <table role="presentation" cellpadding="0" cellspacing="0" border="0" width="100%" style="width:100%; border-collapse:separate; border-spacing:0; background-color:#f2f6ff; border:1px solid #e0e9ff; border-radius:12px;">
                                            <tr>
                                                <td style="padding:18px 16px 7px; font-size:12px; line-height:18px; color:#667085;">Просмотры новостей</td>
                                            </tr>
                                            <tr>
                                                <td style="padding:0 16px 18px; font-size:30px; line-height:36px; font-weight:700; color:#3157d5;">{{ number_format($articleViewsCount, 0, ',', ' ') }}</td>
                                            </tr>
                                        </table>
                                    </td>
                                    <td width="50%" valign="top" style="width:50%; padding:0 0 0 7px;">
                                        <table role="presentation" cellpadding="0" cellspacing="0" border="0" width="100%" style="width:100%; border-collapse:separate; border-spacing:0; background-color:#f3faf7; border:1px solid #dcefe6; border-radius:12px;">
                                            <tr>
                                                <td style="padding:18px 16px 7px; font-size:12px; line-height:18px; color:#667085;">Новые комментарии</td>
                                            </tr>
                                            <tr>
                                                <td style="padding:0 16px 18px; font-size:30px; line-height:36px; font-weight:700; color:#16845b;">{{ number_format($newCommentsCount, 0, ',', ' ') }}</td>
                                            </tr>
                                        </table>
                                    </td>
                                </tr>
                            </table>

                            <p style="margin:24px 0 0; padding:15px 17px; background-color:#f7f9fc; border-left:3px solid #3157d5; font-size:13px; line-height:21px; color:#667085;">Отчёт сформирован автоматически планировщиком задач Laravel. Показатели отражают данные, зарегистрированные за указанный календарный день.</p>
                        </td>
                    </tr>
                    <tr>
                        <td style="padding:18px 28px 22px; background-color:#ffffff; border:1px solid #e4e9f3; border-top:1px solid #eef1f6; border-radius:0 0 18px 18px;">
                            <p style="margin:0; font-size:12px; line-height:19px; color:#98a2b3;">Это автоматический отчёт сайта «Мой сайт», предназначенный для модераторов. Пожалуйста, не отвечайте на это письмо.</p>
                        </td>
                    </tr>
                </table>
            </td>
        </tr>
    </table>
</body>
</html>
