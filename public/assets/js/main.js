// public/js/main.js
let timeoutInMinutes = 20;

function startSessionTimeout() {
  setTimeout(function() {
    window.location.href = '/login';
  }, timeoutInMinutes * 60 * 1000);
}

document.addEventListener("DOMContentLoaded", function() {
  startSessionTimeout();
});

document.addEventListener("mousemove", function() {
  startSessionTimeout();
});

document.addEventListener("keypress", function() {
  startSessionTimeout();
});
