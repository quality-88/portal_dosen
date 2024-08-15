// public/js/main.js
let timeoutInMinutes = 20;

function startSessionTimeout() {
  setTimeout(function() {
    window.location.href = 'https://portaluniversitasquality.ac.id:6923/portal/public/login';
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
