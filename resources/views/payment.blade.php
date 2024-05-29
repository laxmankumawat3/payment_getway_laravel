<!-- resources/views/checkout.blade.php -->

<!DOCTYPE html>
<html>
<head>
    <title>Checkout</title>
    <script src="https://checkout.razorpay.com/v1/checkout.js"></script>
</head>
<body>
    <form action="{{ route('checkout.process') }}" method="POST">
        @csrf
        <button id="pay-button">Pay Now</button>
    </form>

    <script>
        var options = {
            "key": "{{ $razorpayId }}",
            "amount": "{{ $order->amount }}",
            "currency": "{{ $order->currency }}",
            "name": "Your Company Name",
            "description": "Test Transaction",
            "order_id": "{{ $order->id }}",
            "handler": function (response){
                alert("paymentid",response.razorpay_payment_id);
                alert("order_id",response.razorpay_order_id);
                alert("signature",response.razorpay_signature);

                // Submit the form with payment details
                var form = document.createElement('form');
                form.setAttribute('action', '{{ route('checkout.process') }}');
                form.setAttribute('method', 'post');

                var token = document.createElement('input');
                token.setAttribute('type', 'hidden');
                token.setAttribute('name', '_token');
                token.setAttribute('value', '{{ csrf_token() }}');
                form.appendChild(token);

                var paymentId = document.createElement('input');
                paymentId.setAttribute('type', 'hidden');
                paymentId.setAttribute('name', 'razorpay_payment_id');
                paymentId.setAttribute('value', response.razorpay_payment_id);
                form.appendChild(paymentId);

                var orderId = document.createElement('input');
                orderId.setAttribute('type', 'hidden');
                orderId.setAttribute('name', 'razorpay_order_id');
                orderId.setAttribute('value', response.razorpay_order_id);
                form.appendChild(orderId);

                var signature = document.createElement('input');
                signature.setAttribute('type', 'hidden');
                signature.setAttribute('name', 'razorpay_signature');
                signature.setAttribute('value', response.razorpay_signature);
                form.appendChild(signature);

                document.body.appendChild(form);
                form.submit();
            },
            "prefill": {
                "name": "Customer Name",
                "email": "customer@example.com",
                "contact": "9999999999"
            },
            "theme": {
                "color": "#F37254"
            }
        };

        var rzp1 = new Razorpay(options);

        document.getElementById('pay-button').onclick = function(e){
            e.preventDefault();
            rzp1.open();
        };
    </script>
</body>
</html>
