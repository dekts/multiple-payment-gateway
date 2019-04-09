<html>
<head>
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0, user-scalable=no"/>
    <title>CITRUSPOPUP</title>
</head>
<body>

    <script src="https://checkout-static.citruspay.com/lib/js/jquery.min.js"></script>
    <script id="context" type="text/javascript" src="https://checkout-static.citruspay.com/kiwi/app-js/icp.min.js"></script>

    <script>

        window.citrusICP.launchIcp({
                orderAmount: '{{ $parameters['orderAmount'] }}',
                currency: '{{ $parameters['currency'] }}',
                email: "{{ $parameters['email'] }}",
                phoneNumber: "{{ $parameters['phone'] }}",
                merchantTxnId: '{{ $parameters['merchantTxnId'] }}',
                returnUrl:'{{ $parameters['returnUrl'] }}',
                vanityUrl: '{{ $parameters['vanityUrl'] }}',
                secSignature: '{{ $hash }}',
                firstName: '{{ $parameters['firstName'] }}',
                lastName: '{{ $parameters['lastName'] }}',
                addressStreet1: "",
                addressStreet2: "",
                addressCity: "",
                addressState: "",
                addressCountry: "",
                addressZip: "",
                notifyUrl: '{{ $parameters['notifyUrl'] }}',
                mode: "dropIn",
                customParameters: {
                    Username: '{{ $parameters["firstName"] }}'+'{{ $parameters["lastName"] }}',
                    Order: "{{ $parameters['order_id'] }}",
                    Domain: "{{ $parameters['Domain'] }}"
                }
            },
            {
            eventHandler: function(cbObj){
                if (cbObj.event === 'icpLaunched') {

                } else if (cbObj.event === 'icpClosed') {
                    var data = cbObj.message;
                    var sucess_message =  data.TxStatus;
                    var pgRespCode =  data.pgRespCode;

                    post_to_url(data.ReturnUrl, data, 'POST');
                }
            }
        });

        function post_to_url(path, data, method) {
            method = method || "post";

            var form = document.createElement("form");
            form.setAttribute("method", method);
            form.setAttribute("action", path);

            $.map(data,function (value,index) {
                var params = document.createElement("input");
                params.setAttribute("type", "hidden");
                params.setAttribute("name", index);
                if(value instanceof Object || value instanceof Array){
                    value = JSON.stringify(value);
                }
                params.setAttribute("value", value);
                form.appendChild(params);
            });

            document.body.appendChild(form);

            form.submit();
        }
    </script>
</body>
</html>

