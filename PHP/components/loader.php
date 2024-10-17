<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Celestial Watches - Exclusivity in Every Tick</title>
    <!-- CSS for Loader -->
  <style>
    /* Loader Container */
    #loader {
      position: fixed;
      top: 0;
      left: 0;
      width: 100%;
      height: 100%;
      background-color: rgba(255, 255, 255, 0.9); /* Semi-transparent background */
      display: flex;
      justify-content: center;
      align-items: center;
      z-index: 9999; /* Ensure it's on top of everything */
      visibility: hidden; /* Hidden by default */
    }
  
    /* Loader Circle */
    .loading-circle {
      border: 8px solid rgba(0, 0, 0, 0.1);
      border-radius: 50%;
      border-top: 8px solid #000; /* Customize the color */
      width: 60px;
      height: 60px;
      animation: spin 1s linear infinite;
    }
  
    /* Logo in the middle */
    .loader-logo img {
      width: 100px; /* Adjust the size of your logo */
      margin-bottom: 20px;
    }
  
    /* Loading Animation */
    @keyframes spin {
      0% {
        transform: rotate(0deg);
      }
      100% {
        transform: rotate(360deg);
      }
    }
  </style>
  
</head>
<body>
    <!-- Loader HTML -->
<div id="loader">
    <div class="loader-logo">
      <img src="your-logo.png" alt="Loading" />
    </div>
    <div class="loading-circle"></div>
  </div>
  
  <script>
    // Show loader during page load
document.onreadystatechange = function() {
  if (document.readyState === 'interactive') {
    document.getElementById('loader').style.visibility = 'visible';
  }
};

window.addEventListener('load', function() {
  // Hide loader when the page is fully loaded
  document.getElementById('loader').style.visibility = 'hidden';
});

// Show loader on form submission
document.querySelectorAll('form').forEach(form => {
  form.addEventListener('submit', function() {
    document.getElementById('loader').style.visibility = 'visible';
  });
});

// Example: Show loader during slow network request using Fetch API
function fetchData(url) {
  // Show loader before the request
  document.getElementById('loader').style.visibility = 'visible';

  fetch(url)
    .then(response => response.json())
    .then(data => {
      // Process the data
      console.log(data);
    })
    .finally(() => {
      // Hide loader after the request is complete
      document.getElementById('loader').style.visibility = 'hidden';
    });
}

// Show loader after a delay if the page takes too long to load (e.g., 500ms)
setTimeout(function() {
  if (document.readyState !== 'complete') {
    document.getElementById('loader').style.visibility = 'visible';
  }
}, 500); // Delay can be adjusted

  </script>
</body>
</html>