<html>
<head>
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0, user-scalable=no"/>
    <title>CITRUS</title>
</head>
<body>

<form method="post" name="redirect" action="{{ $endPoint }}">
    <input type="hidden" id="merchantTxnId" name="merchantTxnId" value="{{ $parameters['merchantTxnId'] }}" />
    <input type="hidden" id="orderAmount" name="orderAmount" value="{{ $parameters['orderAmount'] }}" />
    <input type="hidden" id="currency" name="currency" value="{{ $parameters['currency'] }}" />
    <input type="hidden" id="email" name="email" value="devat73@gmail.com" />
    <input type="hidden" id="phoneNumber" name="phoneNumber" value="8320354276" />
    <input type="hidden" name="returnUrl" value="{{ $parameters['returnUrl'] }}" />
    <input type="hidden" id="notifyUrl" name="notifyUrl" value="{{ $parameters['notifyUrl'] }}" />
    <input type="hidden" id="secSignature" name="secSignature" value="{{ $hash }}" />
    <input type="hidden" id="payment_mode" name="payment_mode" value="{{ $parameters['payment_mode'] }}"  />

    <input type="hidden" name="customParams[0].name" value="Domain" />
    <input type="hidden" name="customParams[0].value" value='Citrus' id="Domain"/>
    <input type="hidden" name="customParams[1].name" value="Username" />
    <input type="hidden" name="customParams[1].value" value="dekts" id="Username"/>
    <input type="hidden" name="customParams[2].name" value="Order" />
    <input type="hidden" name="customParams[2].value" value="{{ $parameters['order_id'] }}" id="Order"/>
    <input type="hidden" name="customParams[3].name" value="redirectUrl" />
    <input type="hidden" name="customParams[3].value" value="{{ $parameters['returnUrl'] }}" />
</form>

<script src="https://checkout-static.citruspay.com/lib/js/jquery.min.js"></script>
<script language='javascript'>
    document.redirect.submit();
</script>
</body>
</html>
