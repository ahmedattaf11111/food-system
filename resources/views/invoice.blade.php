<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Web</title>
    <link src="/src/plugins/bootstrap/bootstrap.css" rel="stylesheet">
    <style>
        table {
            margin-bottom: 40px;
        }

        table tr {
            border: 1px solid gray;
        }

        .order-table table {
            width: 100%;
        }

        table th {
            text-align: left;
        }

        .statistic {
            text-align: right;
            margin-top: 30px;
        }

        .statistic .list {
            display: inline-block;
            margin-right: 10px;

        }

        .header {
            padding: 45px 0;
        }

        ul.list {
            list-style: none;
            padding: 0;
            margin: 0;
        }

        ul.list li,
        .invoice-message,
        table td {
            font-size: 14px;
            color: gray;
        }

        table th {
            font-size: 14px;
        }

        .invoice-message {
            margin: 35px 0;
        }

        .def-loc {
            font-weight: 500;
        }

        .right-header {
            font-weight: 500;
        }

        .shipping-info {
            padding-top: 20px;
            padding-bottom: 25px;
        }
    </style>
</head>

<body id="body">
    <div class="container">
        <div class="header border-bottom row">
            <div class="col-lg-6">
                <div class="left-sec border-right">
                    <h5 style="margin-bottom:25px">Invoice</h5>
                    <ul class="list">
                        <li>Invoice No: {{$order->code}}</li>
                        <li>Order Date : {{$order->created_at}}</li>
                    </ul>
                    <div class="def-loc">{{$order->location->name_ar}}</div>
                </div>
            </div>
            <div class="col-lg-6" style="text-align: right;">
                <div class="right-sec text-right">
                    <div class="right-header">
                        <img src="https://grostore.themetags.com/public/uploads/media/yqqPV512Gk5DMpvCj2UllKrCl52bam3yD6QvfiPP.png" />
                        <div>
                            Grostore Online Store

                        </div>
                    </div>
                    <ul class="list">
                        <li>USa</li>
                        <li>Phone: +9999999999</li>
                    </ul>
                </div>
            </div>

        </div>
        <div class="shipping-info">
            <h5>Shipping information</h5>
            <ul class="list">
                <li>Logistic:</li>
                <li>Delivery Type: {{$order->type}}</li>
            </ul>
        </div>
        <div class="order-table">
            <table>
                <thead>
                    <tr>
                        <th scope="col">Item</th>
                        <th scope="col">Price</th>
                        <th scope="col">QTY</th>
                        <th scope="col">Subtotal</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        @foreach($order_details as $order_detail)
                        <td scope="col">{{$order_detail["product"]->name_en}}</td>
                        <td scope="col">${{$order_detail['price']}}</td>
                        <td scope="col">{{$order_detail['quantity']}}</td>
                        <th scope="col">${{$order_detail['total_price']}}</th>
                        @endforeach

                    </tr>
                </tbody>
            </table>
        </div>
        <div class="statistic">
            <ul class="list">
                <li>Subtotal</li>
                <li>Tips</li>
                <li>Shipping Cost</li>
                <li>Tax</li>
                <li>Discount</li>
                <li style="font-weight: bold;color:black">Grand Total </li>
            </ul>
            <ul class="list">
                <li>${{$order->sub_total}}</li>
                <li>$0.00</li>
                <li>${{$order->shipping_charge}}</li>
                <li>${{$order->tax}}</li>
                <li>${{$order->discount}}</li>
                <li style="font-weight: bold;color:black">${{$order->grand_total}}</li>
            </ul>
        </div>
        <p class="invoice-message">
            Hello ,
            Thank you for shopping from our store and for your order. it is really awesome to have you as one of our paid users. We hope
            that you will be happy with Qlearly, if you ever have any questions, suggestions or concerns please do not hesitate to contact us
        </p>
        <ul class="list mb-5">
            <li>Best Regards</li>
            <li>Grostore Online Store</li>
            <li>Email: groshop@themetatags.com</li>
            <li>Website: https://grostore.themetags.com</li>
        </ul>
    </div>

</body>

</html>