'use strict';
console.log('Starting service worker');

if( 'function' === typeof importScripts) {

  importScripts('https://www.gstatic.com/firebasejs/5.7.0/firebase-app.js');
  importScripts('https://www.gstatic.com/firebasejs/5.7.0/firebase-messaging.js');
  //importScripts('core/decoder.js');

  // Initialize the Firebase app in the service worker by passing in the
  // messagingSenderId.
  firebase.initializeApp({
    'messagingSenderId': '776419312119'
  });

  // Retrieve an instance of Firebase Messaging so that it can handle background
  // messages.
  const messaging = firebase.messaging();



}