<!DOCTYPE html>
<html>
<head>
    <title>Processing JazzCash Payment...</title>
</head>
<body onload="document.jazzcashForm.submit();">
<form name="jazzcashForm" method="POST"
      action="https://sandbox.jazzcash.com.pk/ApplicationAPI/API/Payment/DoTransaction">
    @forelse($data as $key => $value)
        <input type="hidden" name="{{ $key }}" value="{{ $value }}">
    @empty
        <p>Error: No payment data</p>
    @endforelse
</form>
<p>Redirecting to JazzCash...</p>
</body>
</html>
