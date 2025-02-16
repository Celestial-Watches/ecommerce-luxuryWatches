<?php
session_start();
if (!isset($_SESSION['user'])) {
  header("Location: selling.php");
  exit();
}
define('ALLOW_ACCESS', true);
include '../../PHP/components/navbar.php';
?>
<!DOCTYPE html>
<html>

<head>
  <title>Celestial Watches - Exchange Your Watch</title>
  <meta name="viewport" content="width=device-width, initial-scale=1.0">

  <!-- IONICONS -->
  <script src="https://unpkg.com/ionicons@7.4.0/dist/ionicons/ionicons.esm.js" type="module"></script>
  <script src="https://unpkg.com/ionicons@7.4.0/dist/ionicons/ionicons.js" nomodule></script>

  <!-- Remix Icons / Font Awesome -->
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/remixicon/4.2.0/remixicon.min.css">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">

  <!-- JS -->
  <script src="/src/assets/js/navigation.js" async></script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/crypto-js/3.1.9-1/crypto-js.js"></script>

  <!-- CSS -->
  <link rel="stylesheet" href="/src/assets/css/deskView.css">
  <link rel="stylesheet" href="/src/libs/swiper/swiper-bundle.min.css">
  <link rel="stylesheet" href="/src/assets/css/google-header.css">
  <link rel="stylesheet" href="assets/css//about-watch.css">

  <!-- FONTS -->
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700;800;900&display=swap" rel="stylesheet">

</head>

<body>

  <div class="form-container">
    <form class="formBox">
      <h2>Please, give us details to make the best matching proposal for your watch</h2>

      <!-- 1. Select your watch -->
      <div class="form-row">
        <label class="form-labell" for="select-watch">
          Select your watch that you want to exchange?
          <span class="required-star">*</span>
        </label>
        <div class="form-control" style="display: flex; align-items: center; ">
          <div class="ui-input-icon">
            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
              <path
                stroke-linejoin="round"
                stroke-linecap="round"
                stroke-width="2"
                stroke="currentColor"
                d="M21 21L16.65 16.65M19 11C19 15.4183 15.4183 19 11 19C6.58172 19 3 15.4183 3 11C3 6.58172 6.58172 3 11 3C15.4183 3 19 6.58172 19 11Z"></path>
            </svg>
          </div>
          <input
            type="search"
            id="select-watch"
            name="select_watch"
            placeholder="Please, select your watch"
            required />
        </div>
      </div>



      <!-- 3. Four Yes/No questions in two columns -->
      <div class="two-column-grid">
        <div class="grid-block">
          <!-- Original box? -->
          <div class="form-row">
            <label class="form-labell">
              Do you have the original box?
              <span class="required-star">*</span>
            </label>
            <div class="form-control checkbox-group">
              <div class="checkbox-item">
                <input type="checkbox" id="box-yes" name="original_box" value="yes" required onchange="onlyOne(this)" />
                <label for="box-yes">Yes</label>
              </div>
              <div class="checkbox-item">
                <input type="checkbox" id="box-no" name="original_box" value="no" required onchange="onlyOne(this)" />
                <label for="box-no">No</label>
              </div>
            </div>
          </div>

          <!-- Unworn with factory stickers? -->
          <div class="form-row">
            <label class="form-labell">
              Is your watch unworn with factory stickers intact?
              <span class="required-star">*</span>
            </label>
            <div class="form-control checkbox-group">
              <div class="checkbox-item">
                <input type="checkbox" id="unworn-yes" name="unworn" value="yes" required onchange="onlyOne(this)" />
                <label for="unworn-yes">Yes</label>
              </div>
              <div class="checkbox-item">
                <input type="checkbox" id="unworn-no" name="unworn" value="no" required onchange="onlyOne(this)" />
                <label for="unworn-no">No</label>
              </div>
            </div>
          </div>
        </div>

        <div class="grid-block">
          <!-- Original papers? -->
          <div class="form-row">
            <label class="form-labell">
              Do you have the original papers?
              <span class="required-star">*</span>
            </label>
            <div class="form-control checkbox-group">
              <div class="checkbox-item">
                <input type="checkbox" id="papers-yes" name="original_papers" value="yes" required onchange="onlyOne(this)" />
                <label for="papers-yes">Yes</label>
              </div>
              <div class="checkbox-item">
                <input type="checkbox" id="papers-no" name="original_papers" value="no" required onchange="onlyOne(this)" />
                <label for="papers-no">No</label>
              </div>
            </div>
          </div>

          <!-- Purchased from Watches World? -->
          <div class="form-row">
            <label class="form-labell">
              Was your watch purchased from Watches World?
              <span class="required-star">*</span>
            </label>
            <div class="form-control checkbox-group">
              <div class="checkbox-item">
                <input type="checkbox" id="ww-yes" name="purchased_from_ww" value="yes" required onchange="onlyOne(this)" />
                <label for="ww-yes">Yes</label>
              </div>
              <div class="checkbox-item">
                <input type="checkbox" id="ww-no" name="purchased_from_ww" value="no" required onchange="onlyOne(this)" />
                <label for="ww-no">No</label>
              </div>
            </div>
          </div>
        </div>
      </div>

      <script>
        function onlyOne(checkbox) {
          let group = document.getElementsByName(checkbox.name);
          group.forEach((item) => {
            if (item !== checkbox) item.checked = false;
          });
        }
      </script>

      <!-- 4. Two columns: (How old is it? + How much are you expecting?) and (Condition + Production year) -->
      <div class="two-column-grid">
        <div class="grid-block">
          <!-- How old is it? -->
          <div class="form-row">
            <label class="form-labell boxing" for="watch-age">
              How old is it?
              <span class="required-star">*</span>
            </label>
            <div class="form-control box-select">
              <select id="watch-age" name="watch_age" required>
                <option value="" disabled selected>Select option</option>
                <option value="0">Less than 2 Years Old</option>
                <option value="1">2 to 5 Years Old</option>
                <option value="2">6 to 10 Years Old</option>
                <option value="3">11 to 24 Years Old</option>
                <option value="4">25+ Years Old</option>
              </select>
            </div>
          </div>

          <!-- How much are you expecting? -->
          <div class="form-row">
            <label class="form-labell boxing" for="expected-price">
              How much are you expecting?
              <span class="required-star">*</span>
            </label>
            <div class="form-control box-select">
              <div class="price-input-container">
                <input
                  type="text"
                  id="expected-price"
                  name="expected_price"
                  placeholder="Type a price"
                  required />
              </div>
            </div>
          </div>
        </div>

        <div class="grid-block">
          <!-- Condition -->
          <div class="form-row">
            <label class="form-labell boxing" for="condition">
              What is the condition?
              <span class="required-star">*</span>
            </label>
            <div class="form-control box-select">
              <select id="condition" name="condition" required>
                <option value="" disabled selected>Select condition</option>
                <option value="0">New</option>
                <option value="1">Unworn</option>
                <option value="2">Very Good</option>
                <option value="3">Good</option>
                <option value="4">Fair</option>
                <option value="5">Poor</option>
                <option value="6">Incomplete</option>
              </select>
            </div>
          </div>

          <!-- Production year -->
          <div class="form-row">
            <label class="form-labell boxing" for="production-year">
              What is production year?
              <span class="required-star">*</span>
            </label>
            <div class="form-control box-select">
              <input
                type="text"
                id="production-year"
                name="production_year"
                placeholder="Type a production year"
                required />
            </div>
          </div>
        </div>
      </div>

      <!-- 5. Additional information -->
      <div class="form-row">
        <label class="form-labell" for="additional-info">Additional information</label>
        <div class="form-control">
          <textarea
            id="additional-info"
            name="additional_info"
            placeholder="Type your text here"></textarea>
        </div>
      </div>

      <!-- 6. Select watch to buy -->
      <div class="form-row">
        <label class="form-labell" for="select-watch">
          What watch do you want to buy?
          <span class="required-star">*</span>
        </label>
        <div class="form-control" style="display: flex; align-items: center;">
          <div class="ui-input-icon">
            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
              <path
                stroke-linejoin="round"
                stroke-linecap="round"
                stroke-width="2"
                stroke="currentColor"
                d="M21 21L16.65 16.65M19 11C19 15.4183 15.4183 19 11 19C6.58172 19 3 15.4183 3 11C3 6.58172 6.58172 3 11 3C15.4183 3 19 6.58172 19 11Z"></path>
            </svg>
          </div>
          <input
            type="search"
            id="select-watch"
            name="select_watch"
            placeholder="Please, select your watch"
            required />
        </div>
      </div>

    </form>
    <!-- 7. Submit Button -->
    <div class="form-row" style="text-align: end;">
      <button type="submit" style="margin-bottom: 0 !important;" class="btn-submit">
        Confirm these informations
      </button>
    </div>
  </div>
  <?php include '../../PHP/components/footer.php'; ?>

  <!-- JS Files -->
  <script src="/src/libs/swiper/swiper-bundle.min.js" async></script>
  <script src="/src/assets/js/index.js" async></script>
  <script src="/src/assets/js/currency-language.js" async></script>
  <script src="/src/assets/js/cookie-monitor.js" async></script>
  <script src="/src/assets/js/imagePreview.js" async></script>
</body>

</html>