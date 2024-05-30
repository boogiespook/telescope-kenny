<?php
session_start();
if (isset($_GET['username'])) {
	$_SESSION['username'] = $_GET['username'];
header('Location: index.php'); 
}
?>


          <!DOCTYPE html>
          <html lang="en">
            <head>
              <meta charset="utf-8" />
              <meta name="viewport" content="width=device-width, initial-scale=1" />

              <!-- Include latest PatternFly CSS via CDN -->
              <link 
                rel="stylesheet" 
                href="https://unpkg.com/@patternfly/patternfly/patternfly.css" 
                crossorigin="anonymous"
              >
              <title>CrowsNest Login</title>
            </head>
            <body>
              <div
  class="pf-v5-c-background-image"

></div>
<div class="pf-v5-c-login">
  <div class="pf-v5-c-login__container">
    <main class="pf-v5-c-login__main">
      <header class="pf-v5-c-login__main-header">
        <h1 class="pf-v5-c-title pf-m-3xl">Log in to your CrowsNest account</h1>
      </header>
      <div class="pf-v5-c-login__main-body">
        <form class="pf-v5-c-form" novalidate action="<?php echo $_SERVER['PHP_SELF']; ?>" method="get">
          <div class="pf-v5-c-form__helper-text" aria-live="polite">
            <div class="pf-v5-c-helper-text pf-m-hidden">
              <div class="pf-v5-c-helper-text__item pf-m-error" id="-helper">
                <span class="pf-v5-c-helper-text__item-icon">
                  <i class="fas fa-fw fa-exclamation-circle" aria-hidden="true"></i>
                </span>
                <span
                  class="pf-v5-c-helper-text__item-text"
                >Invalid login credentials.</span>
              </div>
            </div>
          </div>
          <div class="pf-v5-c-form__group"><label class="pf-v5-c-form__label" for="username">
              <span class="pf-v5-c-form__label-text">Username</span>&nbsp;<span
                class="pf-v5-c-form__label-required"
                aria-hidden="true"
              >&#42;</span></label>

            <span class="pf-v5-c-form-control pf-m-required">
              <input
                required
                input="true"
                type="text"
                id="username"
                name="username"
              />
            </span>
          </div>
          
          
          <div class="pf-v5-c-form__group pf-m-action">
            <button
              class="pf-v5-c-button pf-m-primary pf-m-block"
              type="submit"
            >Log in</button>
          </div>
        </form>
      </div>
      
    </main>

  </div>
</div>
            </body>
          </html>
        