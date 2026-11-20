<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="x-apple-disable-message-reformatting">
    <title>Добавлена новая статья</title>
</head>
<body style="margin:0; padding:0; width:100%; background-color:#f3f6fc; font-family:Arial, Helvetica, sans-serif; color:#182230;">
    <table role="presentation" cellpadding="0" cellspacing="0" border="0" width="100%" style="width:100%; background-color:#f3f6fc; border-collapse:collapse;">
        <tr>
            <td align="center" style="padding:32px 16px;">
                <table role="presentation" cellpadding="0" cellspacing="0" border="0" width="600" style="width:100%; max-width:600px; border-collapse:separate; border-spacing:0;">
                    <tr>
                        <td style="padding:22px 28px; background-color:#3157d5; border-radius:18px 18px 0 0;">
                            <div style="font-size:22px; line-height:28px; font-weight:700; color:#ffffff;">Мой сайт</div>
                            <div style="padding-top:5px; font-size:12px; line-height:18px; color:#e5ebff;">Уведомления веб-проекта</div>
                        </td>
                    </tr>
                    <tr>
                        <td style="padding:32px 28px 28px; background-color:#ffffff; border-left:1px solid #e4e9f3; border-right:1px solid #e4e9f3;">
                            <div style="display:inline-block; padding:6px 11px; background-color:#edf2ff; border-radius:20px; color:#3157d5; font-size:11px; line-height:16px; font-weight:700; letter-spacing:0.6px; text-transform:uppercase;">Уведомление модератору</div>
                            <h1 style="margin:18px 0 10px; font-size:25px; line-height:32px; font-weight:700; color:#182230;">Добавлена новая статья</h1>
                            <p style="margin:0 0 24px; font-size:15px; line-height:24px; color:#667085;">На сайте появилась новая публикация. Ознакомьтесь с её содержанием и проверьте информацию.</p>

                            <table role="presentation" cellpadding="0" cellspacing="0" border="0" width="100%" style="width:100%; border-collapse:separate; border-spacing:0; background-color:#f7f9fe; border:1px solid #e8edf7; border-radius:12px;">
                                <tr>
                                    <td style="padding:20px 20px 8px; font-size:12px; line-height:18px; color:#7b8495;">Заголовок публикации</td>
                                </tr>
                                <tr>
                                    <td style="padding:0 20px 12px; font-size:20px; line-height:27px; font-weight:700; color:#182230;">{{ $article->title }}</td>
                                </tr>
                                <tr>
                                    <td style="padding:0 20px 10px; font-size:13px; line-height:20px; color:#667085;">
                                        Дата публикации: <strong style="color:#344054;">{{ $article->published_at }}</strong>
                                    </td>
                                </tr>
                                <tr>
                                    <td style="padding:0 20px 20px; font-size:14px; line-height:22px; color:#475467;">{{ $article->description }}</td>
                                </tr>
                            </table>

                            <table role="presentation" cellpadding="0" cellspacing="0" border="0" style="margin-top:24px; border-collapse:collapse;">
                                <tr>
                                    <td align="center" bgcolor="#3157d5" style="border-radius:9px;">
                                        <a href="{{ route('articles.show', $article) }}" style="display:inline-block; padding:13px 22px; border:1px solid #3157d5; border-radius:9px; color:#ffffff; font-size:14px; line-height:20px; font-weight:700; text-decoration:none;">Открыть статью</a>
                                    </td>
                                </tr>
                            </table>
                        </td>
                    </tr>
                    <tr>
                        <td style="padding:18px 28px 22px; background-color:#ffffff; border:1px solid #e4e9f3; border-top:1px solid #eef1f6; border-radius:0 0 18px 18px;">
                            <p style="margin:0; font-size:12px; line-height:19px; color:#98a2b3;">Это автоматическое уведомление сайта «Мой сайт». Пожалуйста, не отвечайте на это письмо.</p>
                        </td>
                    </tr>
                </table>
            </td>
        </tr>
    </table>
</body>
</html>
