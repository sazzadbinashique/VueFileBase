<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Donation Receipt</title>
  <style>
    body { font-family: 'Inter', 'Hind Siliguri', sans-serif; background: #f5f7f5; margin: 0; padding: 0; }
    .container { max-width: 560px; margin: 40px auto; background: #fff; border-radius: 12px; overflow: hidden; box-shadow: 0 4px 20px rgba(0,0,0,0.08); }
    .header { background: #0E6B5C; color: #FBF6EC; padding: 32px 28px 24px; text-align: center; }
    .header h1 { margin: 0 0 4px; font-size: 22px; font-weight: 700; font-family: 'Fraunces', serif; }
    .header p { margin: 0; opacity: .85; font-size: 14px; }
    .body { padding: 28px; }
    .body h2 { font-size: 16px; font-weight: 600; color: #0E6B5C; margin: 0 0 16px; }
    .details { width: 100%; border-collapse: collapse; }
    .details td { padding: 10px 0; border-bottom: 1px solid #eee; font-size: 14px; }
    .details td:last-child { text-align: right; font-weight: 600; color: #10302B; }
    .details tr:last-child td { border: none; }
    .footer { padding: 20px 28px; text-align: center; font-size: 12px; color: #888; border-top: 1px solid #eee; }
    .footer a { color: #0E6B5C; text-decoration: none; }
  </style>
</head>
<body>
  <div class="container">
    <div class="header">
      <h1>{{ __('Thank You for Your Donation') }}</h1>
      <p>{{ __('Future Bridge Foundation') }}</p>
    </div>
    <div class="body">
      <h2>{{ __('Donation Receipt') }}</h2>
      <table class="details">
        <tr><td>{{ __('Donor') }}</td><td>{{ $donation->donor_name }}</td></tr>
        <tr><td>{{ __('Email') }}</td><td>{{ $donation->donor_email }}</td></tr>
        <tr><td>{{ __('Project') }}</td><td>{{ $donation->project?->title ?? 'General' }}</td></tr>
        <tr><td>{{ __('Amount') }}</td><td>
          @if($donation->currency === 'BDT')
            ৳{{ number_format($donation->amount, 2) }}
          @else
            ${{ number_format($donation->amount, 2) }}
          @endif
        </td></tr>
        <tr><td>{{ __('Transaction ID') }}</td><td style="font-family: monospace; font-size: 12px;">{{ $donation->transaction_id }}</td></tr>
        <tr><td>{{ __('Date') }}</td><td>{{ $donation->created_at->format('F j, Y') }}</td></tr>
        <tr><td>{{ __('Payment Method') }}</td><td>{{ $donation->currency === 'BDT' ? 'SSLCommerz' : 'Stripe' }}</td></tr>
      </table>
    </div>
    <div class="footer">
      <p>{{ __('This receipt was generated automatically.') }}</p>
      <p>&copy; {{ date('Y') }} <a href="{{ config('app.url') }}">{{ __('Future Bridge Foundation') }}</a>. {{ __('All rights reserved.') }}</p>
    </div>
  </div>
</body>
</html>
