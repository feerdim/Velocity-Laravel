// Give the service worker access to Firebase Messaging.
// Note that you can only use Firebase Messaging here. Other Firebase libraries
// are not available in the service worker.
importScripts('https://www.gstatic.com/firebasejs/8.2.6/firebase-app.js');
importScripts('https://www.gstatic.com/firebasejs/8.2.6/firebase-messaging.js');

// Initialize the Firebase app in the service worker by passing in
// your app's Firebase config object.
// https://firebase.google.com/docs/web/setup#config-object
var firebaseConfig = {
    apiKey: "AIzaSyCDKVDWJFv0C-yVpQHe_nAa6HkjKbldnJU",
    authDomain: "velocity-4c789.firebaseapp.com",
    projectId: "velocity-4c789",
    storageBucket: "velocity-4c789.appspot.com",
    messagingSenderId: "302877622150",
    appId: "1:302877622150:web:e39aaaba74fd343ce2f607"
  };
// Initialize Firebase
firebase.initializeApp(firebaseConfig);

// Retrieve an instance of Firebase Messaging so that it can handle background
// messages.
const messaging = firebase.messaging();

messaging.onBackgroundMessage((payload) => {
    console.log('[firebase-messaging-sw.js] dari admin ', payload);
    // Customize notification here
    const { title,body } = payload.notification 
    const notificationOptions = {
      body,
      icon: '/assets/img/logo/logo-head.png'
    };
  
    self.registration.showNotification(title,
      notificationOptions);
  });
