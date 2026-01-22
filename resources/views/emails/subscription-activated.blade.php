<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Subscription activated</title>
  </head>
  <body style="margin:0; padding:0; background:#f4f6f8; font-family: Arial, sans-serif;">
    <table role="presentation" width="100%" cellpadding="0" cellspacing="0" style="background:#f4f6f8; padding:24px 12px;">
      <tr>
        <td align="center">
          <table role="presentation" width="600" cellpadding="0" cellspacing="0" style="max-width:600px; width:100%; background:#ffffff; border-radius:16px; overflow:hidden; box-shadow:0 12px 30px rgba(15,23,42,.12);">
            <tr>
              <td style="background:#63d7cf; padding:20px 24px;">
                <h1 style="margin:0; font-size:22px; color:#111111;">Revise MSRA</h1>
                <p style="margin:6px 0 0; font-size:13px; color:rgba(17,24,39,.75);">Your subscription is now active</p>
              </td>
            </tr>
            <tr>
              <td style="padding:24px;">
                <p style="margin:0 0 12px; font-size:15px; color:#111111;">
                  Hi {{ $subscription->user->name }},
                </p>
                <p style="margin:0 0 16px; font-size:14px; color:rgba(17,24,39,.8); line-height:1.6;">
                  Thank you for subscribing. Your plan is confirmed and your MSRA resources are ready.
                </p>

                <table role="presentation" width="100%" cellpadding="0" cellspacing="0" style="background:#f8fafc; border-radius:12px; padding:16px; border:1px solid rgba(15,23,42,.08);">
                  <tr>
                    <td style="font-size:13px; color:rgba(17,24,39,.7); padding-bottom:6px;">Plan</td>
                    <td style="font-size:13px; color:#111111; font-weight:700; padding-bottom:6px;">{{ $subscription->plan->name }}</td>
                  </tr>
                  <tr>
                    <td style="font-size:13px; color:rgba(17,24,39,.7); padding-bottom:6px;">Start date</td>
                    <td style="font-size:13px; color:#111111; font-weight:700; padding-bottom:6px;">{{ $subscription->started_at->format('d M Y') }}</td>
                  </tr>
                  <tr>
                    <td style="font-size:13px; color:rgba(17,24,39,.7);">Expiry date</td>
                    <td style="font-size:13px; color:#111111; font-weight:700;">{{ $subscription->expires_at->format('d M Y') }}</td>
                  </tr>
                </table>

                <p style="margin:18px 0 0; font-size:14px; color:rgba(17,24,39,.8); line-height:1.6;">
                  You can now access the question bank, revision notes, flashcards, and mock papers.
                </p>

                <div style="margin-top:18px;">
                  <a href="{{ route('dashboard') }}" style="display:inline-block; background:#111111; color:#ffffff; text-decoration:none; padding:12px 18px; border-radius:10px; font-weight:700; font-size:13px;">
                    Go to dashboard
                  </a>
                </div>
              </td>
            </tr>
            <tr>
              <td style="padding:18px 24px; background:#0b0b0c; color:#ffffff; font-size:12px;">
                Need help? Reply to this email or visit the support page inside your dashboard.
              </td>
            </tr>
          </table>
          <p style="margin:12px 0 0; font-size:11px; color:rgba(17,24,39,.6);">(c) Revise MSRA Limited</p>
        </td>
      </tr>
    </table>
  </body>
</html>
