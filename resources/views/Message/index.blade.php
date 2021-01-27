<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
</head>

<body>

    <div id="token"></div>
    <div id="msg"></div>
    <div id="notis"></div>
    <div id="err"></div>

    <!-- If you enabled Analytics in your project, add the Firebase SDK for Analytics -->
    <!-- Add Firebase products that you want to use -->
    <!-- The core Firebase JS SDK is always required and must be listed first -->
    <!-- <script src="firebase-messaging-sw.js"></script> -->
    <script src="https://www.gstatic.com/firebasejs/7.17.1/firebase-app.js"></script>
    <script src="https://www.gstatic.com/firebasejs/7.17.1/firebase-analytics.js"></script>
    <script src="https://www.gstatic.com/firebasejs/7.17.1/firebase-auth.js"></script>
    <script src="https://www.gstatic.com/firebasejs/7.17.1/firebase-firestore.js"></script>
    <script src="https://www.gstatic.com/firebasejs/7.17.1/firebase-messaging.js"></script>
    <script>

        MsgElem = document.getElementById("msg");
        TokenElem = document.getElementById("token");
        NotisElem = document.getElementById("notis");
        ErrElem = document.getElementById("err");
        // Your web app's Firebase configuration
        var firebaseConfig = {
            apiKey: "AIzaSyD_pZokyYqQ1OsJ5srhhdyUHR1O6xUn_bE",
            authDomain: "lokasipasien-d02da.firebaseapp.com",
            databaseURL: "https://lokasipasien-d02da.firebaseio.com",
            projectId: "lokasipasien-d02da",
            storageBucket: "lokasipasien-d02da.appspot.com",
            messagingSenderId: "492528890188",
            appId: "1:492528890188:web:6caabd2034a21a881ddfe9"
        };

        // Initialize Firebase
        firebase.initializeApp(firebaseConfig);
        // Retrieve Firebase Messaging object.
        const messaging = firebase.messaging();
        // console.log(messaging);

        // Get Instance ID token. Initially this makes a network call, once retrieved
        // subsequent calls to getToken will return from cache.
        // Callback fired if Instance ID token is updated.
        // Get Instance ID token. Initially this makes a network call, once retrieved
        // subsequent calls to getToken will return from cache.

        if ('serviceWorker' in navigator) {
            navigator.serviceWorker.register("{{ asset('assets/js/firebase-messaging-sw.js') }}")
                .then(function (registration) {
                    console.log("Service Worker Registered");
                    messaging.useServiceWorker(registration);
                });
        }
        messaging.getToken().then((currentToken) => {
            TokenElem.innerHTML = currentToken;
            if (currentToken) {
                sendTokenToServer(currentToken);
                updateUIForPushEnabled(currentToken);
            } else {
                // Show permission request.
                console.log('No Instance ID token available. Request permission to generate one.');
                // Show permission UI.
                updateUIForPushPermissionRequired();
                setTokenSentToServer(false);
            }
        }).catch((err) => {
            console.log('An error occurred while retrieving token. ', err);
            showToken('Error retrieving Instance ID token. ', err);
            setTokenSentToServer(false);
        });

    </script>
</body>

</html>