<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>API Documentation</title>
    <style>
        body {
    font-family: Arial, sans-serif;
    margin: 0;
    padding: 0;
    background-color: #f4f4f4;
}

.container {
    max-width: 800px;
    margin: 0 auto;
    padding: 20px;
    background-color: #fff;
    border-radius: 8px;
    box-shadow: 0 0 10px rgba(0,0,0,0.1);
}

h1, h2, h3 {
    color: #333;
}

h1 {
    border-bottom: 2px solid #333;
    padding-bottom: 10px;
}

h2 {
    font-size: 1.5em;
    margin-top: 20px;
}

.endpoint-section {
    margin-bottom: 20px;
}

.endpoint-section h3 {
    font-size: 1.2em;
    border-bottom: 1px solid #ccc;
    padding-bottom: 5px;
}

.endpoint-section ul {
    list-style: none;
    padding: 0;
}

.endpoint-section ul li {
    padding: 8px 0;
    border-bottom: 1px solid #eee;
    display: flex;
    justify-content: space-between;
}

.method {
    background-color: #007BFF;
    color: #fff;
    padding: 4px 8px;
    border-radius: 4px;
    font-size: 0.9em;
}

    </style>
</head>
<body>
    <div class="container">
        <h1>API Documentation</h1>
        <h2>Base URL: <code>https://butique-back-2.lxdyi.com/api</code></h2>

        <section class="endpoint-section">
            <h3>General Authentication and loggedIn Management</h3>
            <ul>
                <li><span class="method">POST</span> /user/update_profile</li>
                <li><span class="method">GET</span> /user/my_profile</li>
                <li><span class="method">POST</span> /register</li>     //AUTH ROUTE
                <li><span class="method">POST</span> /check-otp</li>
                <li><span class="method">POST</span> /login</li>
                <li><span class="method">GET</span> /logout</li>
                <li><span class="method">GET</span> /logout-all</li>
                <li><span class="method">GET</span> /forget-password</li>
                <li><span class="method">POST</span> /check-forget-password"</li>
                <li><span class="method">POST</span> /reset-password</li>

            </ul>
        </section>

        <section class="endpoint-section">
            <h3>Client</h3>
            <ul>
                <li><span class="method">POST</span> /rating</li>
                <li><span class="method">DELETE</span> /rating/{id}</li>
                <li><span class="method">POST</span> /wishlist</li>
                <li><span class="method">DELTE</span> /wishlist/{id}</li>
                <li><span class="method">GET</span> /wishlist</li>
                <li><span class="method">PUT</span> /wishlist/{id}</li>```

                <li><span class="method">POST</span> /search</li>
            </ul>
        </section>

        <section class="endpoint-section">
            <h3>Cart and Payment Management</h3>
            <ul>
                <li><span class="method">GET</span> /cart/my-cart</li>
                <li><span class="method">POST</span> /cart/add-to-cart</li>
                <li><span class="method">Delete</span> /cart/remove-from-cart</li>
                <li><span class="method">PUT</span> /cart/{id}</li>
                <li><span class="method">DELETE</span> /order/{id}</li>
                <li><span class="method">PUT</span> /order/{id}</li>
                <li><span class="method">GET</span> /order/my_order</li>
                <li><span class="method">POST</span> /order</li>
                <li><span class="method">POST</span> /product/index_admin</li>
                <li><span class="method">GET</span> /product</li>
                <li><span class="method">GET</span> /product/{id}</li>
                <li><span class="method">DELETE</span> /product/{id}</li>
                <li><span class="method">POST</span> /shipping</li>
                <li><span class="method">DELETE</span> /shipping/{id}</li>
                <li><span class="method">PUT</span> /shipping/{id}</li>

                </ul>
        </section>

        <section class="endpoint-section">
            <h3>flashSaleItem</h3>
            <ul>
                <li><span class="method">POST</span> /flashSaleItem</li>
                <li><span class="method">PUT</span> /flashSaleItem/{id}</li>
                <li><span class="method">DELETE</span> /flashSaleItem/{id}</li>
                <li><span class="method">POST</span> /flashSale</li>
                <li><span class="method">GET</span> /flashSale</li>
                <li><span class="method">DELETE</span> /flashSale/{id}</li>

            </ul>
        </section>

        <section class="endpoint-section">
            <h3>setting and message and category</h3>
            <ul>
                <li><span class="method">POST</span> /setting</li>
                <li><span class="method">GET</span> /setting</li>
                <li><span class="method">GET</span> /stats</li>
                <li><span class="method">GET</span> /message/index_chat_messages</li>
                <li><span class="method">POST</span> /message/send_as_client</li>

                <li><span class="method">POST</span> /category</li>
                <li><span class="method">DELETE</span> /category/{id}</li>
                <li><span class="method">PUT</span> /category/{id}</li>
                <li><span class="method">GET</span> /category</li>
                <li><span class="method">GET</span> /category/show-admin</li>
                <li><span class="method">GET</span> /category/show-sub-category</li>

            </ul>
        </section>

        <section class="endpoint-section">
            <h3>Notifications</h3>
            <ul>
                <li><span class="method">POST</span> /send-notification</li>

            </ul>
        </section>


    </div>
</body>
</html>
