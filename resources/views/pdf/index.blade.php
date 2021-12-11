<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Receit</title>
    <style type="text/css">
        @page {
            margin: 0px;
        }

        body {
            margin: 0px;
        }

        * {
            font-family: Verdana, Arial, sans-serif;
        }

        a {
            color: #fff;
            text-decoration: none;
        }

        table {
            font-size: x-small;
        }

        tfoot tr td {
            font-weight: bold;
            font-size: x-small;
        }

        .invoice table {
            margin: 20px;
        }

        .invoice h3 {
            margin-left: 15px;
            color: #4e73df
        }

        .invoice h2 {
            margin-left: 15px;
            color: #4e73df
        }

        .invoice h4 {
            margin-left: 15px;
        }

        .invoice a {
            margin-left: 15px;
        }

        .information {
            background-color: #4e73df;
            color: #FFF;
        }

        .information .logo {
            margin: 5px;
        }

        .information table {
            padding: 10px;
        }

    </style>
</head>
<body>
    <div class="information">
        <table width="100%">
            <tr>
                <td align="left" style="width: 40%;">
                    <h3>{{ $merged['transaction']['date'] }}</h3>
                    <h3></h3>
                    <pre>Proof of Operation</pre>
                </td>
                <td align="center">
                    <img src="img/logo.png" alt="Logo" width="64" class="logo" />
                </td>
                <td align="right" style="width: 40%;">

                    <h3>Vcards</h3>
                    <pre>
                    Financial Freedom
                </pre>
                </td>
            </tr>
        </table>
    </div>
    <br />
    <div class="invoice">
        <h3>Transaction Details #{{ $merged['transaction']['id'] }}</h3>
        <a style="color:gainsboro"> The request of operation with the characteristics identified below is registered in
            our system</a>
        <br>
        <table width="95%" style="border-collapse: separate; border-spacing: 0 15px;">
            <tbody>
                <tr>
                    <td style="color:#4e73df"><strong>OPERATION</strong></td>
                    <td> </td>
                </tr>
                <tr>
                    <td>Reference</td>
                    <td align='right'>{{ $merged['transaction']['payment_reference'] }}</td>
                </tr>
                <tr>
                    <td>Datetime</td>
                    <td align='right'>{{ $merged['transaction']['datetime'] }}</td>
                </tr>
                <tr>
                    <td>Type</td>
                    <td align='right'>{{ $merged['transaction']['type'] == 'D' ? 'Debit' : 'Credit' }}</td>
                </tr>
                <tr>
                    <td>Value</td>
                    <td align='right'>{{ $merged['transaction']['value'] }}€</td>
                </tr>
                <tr>
                    <td>Payment Type</td>
                    <td align='right'>{{ $merged['transaction']['payment_type'] }}</td>
                </tr>
            </tbody>
        </table>
        <br>
        <br>
        <table width="95%" style="border-collapse: separate; border-spacing: 0 15px;">
            <tbody>
                <tr>
                    <td style="color:#4e73df"><strong>SENDER</strong></td>
                    <td> </td>
                </tr>
                <tr>
                    <td>Name</td>
                    <td align='right'>{{ $merged['vcard']->name }}</td>
                </tr>
                <tr>
                    <td>Email</td>
                    <td align='right'>{{ $merged['vcard']->email }}</td>
                </tr>
                <tr>
                    <td>Phone Number</td>
                    <td align='right'>{{ $merged['vcard']->phone_number }}</td>
                </tr>

            </tbody>
        </table>
        <br>
        <br>
        @if ($merged['pair_vcard'] != null)
            <table width="95%" style="border-collapse: separate; border-spacing: 0 15px;">
                <tbody>
                    <tr>
                        <td style="color:#4e73df"><strong>RECEIVER</strong></td>
                        <td> </td>
                    </tr>
                    <tr>
                        <td>Name</td>
                        <td align='right'>{{ $merged['pair_vcard']->name }}</td>
                    </tr>
                    <tr>
                        <td>Email</td>
                        <td align='right'>{{ $merged['pair_vcard']->email }}</td>
                    </tr>
                    <tr>
                        <td>Phone Number</td>
                        <td align='right'>{{ $merged['pair_vcard']->phone_number }}</td>
                    </tr>

                </tbody>
            </table>
        @endif
    </div>
</body>

</html>
