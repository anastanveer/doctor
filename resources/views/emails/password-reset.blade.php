<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Reset your password</title>
  </head>
  <body style="margin:0; padding:0; background:#f4f6f8; font-family: Arial, sans-serif;">
    <table role="presentation" width="100%" cellpadding="0" cellspacing="0" style="background:#f4f6f8; padding:24px 12px;">
      <tr>
        <td align="center">
          <table role="presentation" width="600" cellpadding="0" cellspacing="0" style="max-width:600px; width:100%; background:#ffffff; border-radius:16px; overflow:hidden; box-shadow:0 12px 30px rgba(15,23,42,.12);">
            <tr>
              <td style="background:#63d7cf; padding:20px 24px;">
                <h1 style="margin:0; font-size:22px; color:#111111;">Revise MRCEM</h1>
                <p style="margin:6px 0 0; font-size:13px; color:rgba(17,24,39,.75);">Password reset request</p>
              </td>
            </tr>
            <tr>
              <td style="padding:24px;">
                <p style="margin:0 0 12px; font-size:15px; color:#111111;">
                  Hi {{ $user->name }},
                </p>
                <p style="margin:0 0 16px; font-size:14px; color:rgba(17,24,39,.8); line-height:1.6;">
                  We received a request to reset your Revise MRCEM password. Click the button below to choose a new one.
                </p>

                <div style="margin:18px 0;">
                  <a href="{{ $url }}" style="display:inline-block; background:#111111; color:#ffffff; text-decoration:none; padding:12px 18px; border-radius:10px; font-weight:700; font-size:13px;">
                    Reset password
                  </a>
                </div>

                <p style="margin:0 0 12px; font-size:13px; color:rgba(17,24,39,.7); line-height:1.6;">
                  This link expires in {{ $expires }} minutes. If you did not request a reset, you can safely ignore this email.
                </p>
              </td>
            </tr>
            <tr>
              <td style="padding:18px 24px; background:#0b0b0c; color:#ffffff; font-size:12px;">
                Need help? Reply to this email and we will assist.
              </td>
            </tr>
          </table>
          <p style="margin:12px 0 0; font-size:11px; color:rgba(17,24,39,.6);">(c) Revise MRCEM Limited</p>
        </td>
      </tr>
    </table>
  </body>
</html>
