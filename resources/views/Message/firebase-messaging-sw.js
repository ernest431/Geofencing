// Give the service worker access to Firebase Messaging.
// Note that you can only use Firebase Messaging here, other Firebase libraries
// are not available in the service worker.
importScripts('https://www.gstatic.com/firebasejs/7.17.1/firebase-app.js');
importScripts('https://www.gstatic.com/firebasejs/7.17.1/firebase-messaging.js');

// Initialize the Firebase app in the service worker by passing in
// your app's Firebase config object.
// https://firebase.google.com/docs/web/setup#config-object


firebase.initializeApp({
  apiKey: "AIzaSyD_pZokyYqQ1OsJ5srhhdyUHR1O6xUn_bE",
  authDomain: "lokasipasien-d02da.firebaseapp.com",
  databaseURL: "https://lokasipasien-d02da.firebaseio.com",
  projectId: "lokasipasien-d02da",
  storageBucket: "lokasipasien-d02da.appspot.com",
  messagingSenderId: "492528890188",
  appId: "1:492528890188:web:6caabd2034a21a881ddfe9"
});

// Retrieve an instance of Firebase Messaging so that it can handle background
// messages.
const messaging = firebase.messaging();
