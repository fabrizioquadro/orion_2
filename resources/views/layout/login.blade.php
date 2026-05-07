<!DOCTYPE html>

<html lang="pt-BR" class="light-style layout-wide customizer-hide" dir="ltr" data-theme="theme-default" data-assets-path="/public/template/" data-template="vertical-menu-template">
  <head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=no, minimum-scale=1.0, maximum-scale=1.0" />

    <title>Clube Orion Prime - Sistema Online</title>

    <meta name="description" content="Clube Orion Prime - Sistema Online" />

    <!-- Favicon -->
    <link rel="icon" type="image/x-icon" href="{{ asset('/public/img/logo_vert_branco.png') }}" />

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com" />
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin />
    <link
      href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&ampdisplay=swap"
      rel="stylesheet" />

    <!-- Icons -->
    <link rel="stylesheet" href="{{ asset('/public/template/vendor/fonts/materialdesignicons.css') }}" />
    <link rel="stylesheet" href="{{ asset('/public/template/vendor/fonts/flag-icons.css') }}" />

    <!-- Menu waves for no-customizer fix -->
    <link rel="stylesheet" href="{{ asset('/public/template/vendor/libs/node-waves/node-waves.css') }}" />

    <!-- Core CSS -->
    <link rel="stylesheet" href="{{ asset('/public/template/vendor/css/rtl/core.css') }}" class="template-customizer-core-css" />
    <link rel="stylesheet" href="{{ asset('/public/template/vendor/css/rtl/theme-default.css') }}" class="template-customizer-theme-css" />
    <link rel="stylesheet" href="{{ asset('/public/template/css/demo.css') }}" />

    <!-- Vendors CSS -->
    <link rel="stylesheet" href="{{ asset('/public/template/vendor/libs/perfect-scrollbar/perfect-scrollbar.css') }}" />
    <link rel="stylesheet" href="{{ asset('/public/template/vendor/libs/typeahead-js/typeahead.css') }}" />
    <!-- Vendor -->
    <link rel="stylesheet" href="{{ asset('/public/template/vendor/libs/@form-validation/umd/styles/index.min.css') }}" />

    <!-- Page CSS -->
    <!-- Page -->
    <link rel="stylesheet" href="{{ asset('/public/template/vendor/css/pages/page-auth.css') }}" />

    <!-- Helpers -->
    <script src="{{ asset('/public/template/vendor/js/helpers.js') }}"></script>
    <!--! Template customizer & Theme config files MUST be included after core stylesheets and helpers.js in the <head> section -->
    <!--? Template customizer: To hide customizer set displayCustomizer value false in config.js.  -->
    <script src="{{ asset('/public/template/vendor/js/template-customizer.js') }}"></script>
    <!--? Config:  Mandatory theme config file contain global vars & default theme options, Set your preferred theme option in this file.  -->
    <script src="{{ asset('/public/template/js/config.js') }}"></script>
    <style>
    .imgFundo{
        min-height: 100%;
        background-size: cover;
        background-repeat: no-repeat;
        background-position: center center;
        background-image: url({{ asset('/public/img/fundo.jpg') }});
    }

    .btn-primary{
        background-color: #266d45 !important;
        border-color: #266d45 !important;
    }

    .btn-primary:hover{
        background-color: #245238 !important;
        border-color: #245238 !important;
    }

    </style>
  </head>

  <body class='imgFundo'>
    <!-- Content -->

    <div class="position-relative">
      <div class="authentication-wrapper authentication-basic container-p-y">
        <div class="authentication-inner py-4">
          <!-- Login -->
          <div class="card p-2">
            <!-- Logo -->
            <div class="app-brand justify-content-center mt-5">
              <a href="/" class="app-brand-link gap-2">
                <span class="app-brand-logo demo">
                  <span>
                    <img src="{{ asset('/public/img/logo_vert_branco.png') }}" style='height: 200px' alt="">
                  </span>
                </span>
                <!-- <span class="app-brand-text demo text-heading fw-bold">Smart Money Makers</span> -->
              </a>
            </div>
            <!-- /Logo -->
            @yield('conteudo')
          </div>
          <!-- /Login -->
        </div>
      </div>
    </div>

    <!-- / Content -->

    <!-- Core JS -->
    <!-- build:js assets/vendor/js/core.js -->
    <script src="{{ asset('/public/template/vendor/libs/jquery/jquery.js') }}"></script>
    <script src="{{ asset('/public/template/vendor/libs/popper/popper.js') }}"></script>
    <script src="{{ asset('/public/template/vendor/js/bootstrap.js') }}"></script>
    <script src="{{ asset('/public/template/vendor/libs/node-waves/node-waves.js') }}"></script>
    <script src="{{ asset('/public/template/vendor/libs/perfect-scrollbar/perfect-scrollbar.js') }}"></script>
    <script src="{{ asset('/public/template/vendor/libs/hammer/hammer.js') }}"></script>
    <script src="{{ asset('/public/template/vendor/libs/i18n/i18n.js') }}"></script>
    <script src="{{ asset('/public/template/vendor/libs/typeahead-js/typeahead.js') }}"></script>
    <script src="{{ asset('/public/template/vendor/js/menu.js') }}"></script>

    <!-- endbuild -->

    <!-- Vendors JS -->
    <script src="{{ asset('/public/template/vendor/libs/@form-validation/umd/bundle/popular.min.js') }}"></script>
    <script src="{{ asset('/public/template/vendor/libs/@form-validation/umd/plugin-bootstrap5/index.min.js') }}"></script>
    <script src="{{ asset('/public/template/vendor/libs/@form-validation/umd/plugin-auto-focus/index.min.js') }}"></script>

    <!-- Main JS -->
    <script src="{{ asset('/public/template/js/main.js') }}"></script>

    <!-- Page JS -->
    <script src="{{ asset('/public/template/js/pages-auth.js') }}"></script>
    <script src="{{ asset('/public/js/script.js') }}"></script>
  </body>
</html>
