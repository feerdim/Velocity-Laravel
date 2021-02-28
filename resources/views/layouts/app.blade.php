<!--
=========================================================
* Argon Dashboard PRO - v1.2.0
=========================================================
* Product Page: https://www.creative-tim.com/product/argon-dashboard-pro
* Copyright  Creative Tim (http://www.creative-tim.com)
* Coded by www.creative-tim.com
=========================================================
* The above copyright notice and this permission notice shall be included in all copies or substantial portions of the Software.
-->
<!DOCTYPE html>
<html>

<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
  <meta name="csrf-token" content="{{ csrf_token() }}">
  <meta name="description" content="Start your development with a Dashboard for Bootstrap 4.">
  <meta name="author" content="Creative Tim">
  <title>Velocity</title>
  <!-- Favicon -->
  <link rel="icon" href="{{ asset('assets/img/logo/logo-head.png') }}" type="image/png">
  <!-- Fonts -->
  <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Open+Sans:300,400,600,700">
  <!-- Icons -->
  <link rel="stylesheet" href="{{ asset('assets/vendor/nucleo/css/nucleo.css') }}" type="text/css">
  <link rel="stylesheet" href="{{ asset('assets/vendor/@fortawesome/fontawesome-free/css/all.min.css') }}" type="text/css">
  <!-- Page plugins -->
  <link rel="stylesheet" href="{{ asset('assets/vendor/fullcalendar/dist/fullcalendar.min.css') }}">
  <link rel="stylesheet" href="{{ asset('assets/vendor/sweetalert2/dist/sweetalert2.min.css') }}">
  <!-- Argon CSS -->
  <link rel="stylesheet" href="{{ asset('assets/css/argon.css?v=1.2.0') }}" type="text/css">

  <link rel="stylesheet" href="{{ asset('assets/vendor/datatables.net-bs4/css/dataTables.bootstrap4.min.css') }}">
  <link rel="stylesheet" href="{{ asset('assets/vendor/datatables.net-buttons-bs4/css/buttons.bootstrap4.min.css') }}">
  <link rel="stylesheet" href="{{ asset('assets/vendor/datatables.net-select-bs4/css/select.bootstrap4.min.css') }}">

  <script src="{{ asset('assets/vendor/jquery/dist/jquery.min.js') }}"></script>
  <script src="{{ asset('assets/vendor/moment/min/moment.min.js') }}"></script>
  <script src="{{ asset('assets/vendor/fullcalendar/dist/fullcalendar.min.js') }}"></script>
  <script src="{{ asset('assets/vendor/sweetalert2/dist/sweetalert2.min.js') }}"></script>
  <!-- The core Firebase JS SDK is always required and must be listed first -->
  <script src="https://www.gstatic.com/firebasejs/8.2.6/firebase-app.js"></script>
  <script src="https://www.gstatic.com/firebasejs/8.2.6/firebase-messaging.js"></script>
  <script>
    // Your web app's Firebase configuration
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
  </script>
  <script src="https://cdn.jsdelivr.net/npm/axios/dist/axios.min.js"></script>
</head>

<body>
  <!-- Sidenav -->
  <nav class="sidenav navbar navbar-vertical  fixed-left  navbar-expand-xs navbar-light bg-white" id="sidenav-main">
    <div class="scrollbar-inner">
      <!-- Brand -->
      <div class="sidenav-header  d-flex  align-items-center">
        <a class="navbar-brand" href="{{ route('admin.dashboard.index') }}">
          <img src="{{ asset('assets/img/logo/logo-main.png') }}" class="navbar-brand-img" alt="...">
        </a>
        <div class=" ml-auto ">
          <!-- Sidenav toggler -->
          <div class="sidenav-toggler d-none d-xl-block" data-action="sidenav-unpin" data-target="#sidenav-main">
            <div class="sidenav-toggler-inner">
              <i class="sidenav-toggler-line"></i>
              <i class="sidenav-toggler-line"></i>
              <i class="sidenav-toggler-line"></i>
            </div>
          </div>
        </div>
      </div>
      <div class="navbar-inner">
        <!-- Collapse -->
        <div class="collapse navbar-collapse" id="sidenav-collapse-main">
            <!-- Nav items -->
            <ul class="navbar-nav">
                @can('roles.index')
                  <li class="nav-item">
                  <a class="nav-link {{ setActive('admin/role'). setActive('admin/permission'). setActive('admin/user') }}" href="#navbar-dashboards" data-toggle="collapse" role="button" aria-expanded="false" aria-controls="navbar-dashboards">
                      <i class="ni ni-single-02 text-primary"></i>
                      <span class="nav-link-text">Admin Management</span>
                  </a>
                  <div class="collapse" id="navbar-dashboards">
                      <ul class="nav nav-sm flex-column">
                          @can('roles.index')
                          <li class="nav-item">
                              <a href="{{ route('admin.role.index') }}" class="nav-link {{ setActive('admin/role') }}">
                              <span class="sidenav-mini-icon"> R </span>
                              <span class="sidenav-normal"> Roles </span>
                              </a>
                          </li>
                          @endcan
                          @can('permissions.index')
                          <li class="nav-item">
                              <a href="{{ route('admin.permission.index') }}" class="nav-link {{ setActive('admin/permission') }}">
                              <span class="sidenav-mini-icon"> P </span>
                              <span class="sidenav-normal"> Permissions </span>
                              </a>
                          </li>
                          @endcan
                          @can('users.index')
                          <li class="nav-item">
                              <a href="{{ route('admin.user.index') }}" class="nav-link {{ setActive('admin/user') }}">
                              <span class="sidenav-mini-icon"> U </span>
                              <span class="sidenav-normal"> Users </span>
                              </a>
                          </li>
                          @endcan
                      </ul>
                  </div>
                  </li>
                @endcan
                @can('schedules.index')
                  <li class="nav-item">
                  <a class="nav-link {{ setActive('admin/time'). setActive('admin/solo_schedule') . setActive('admin/team_schedule'). setActive('admin/history_schedule'). setActive('admin/vs_one_schedule') }}" href="#navbar-schedule" data-toggle="collapse" role="button" aria-expanded="false" aria-controls="navbar-schedule">
                      <i class="ni ni-calendar-grid-58 text-pink"></i>
                      <span class="nav-link-text">Schedule Management</span>
                  </a>
                  <div class="collapse" id="navbar-schedule">
                      <ul class="nav nav-sm flex-column">
                        @can('schedules.create')
                          <li class="nav-item">
                              <a href="{{ route('admin.time.index') }}" class="nav-link {{ setActive('admin/time') }}">
                              <span class="sidenav-mini-icon"> C </span>
                              <span class="sidenav-normal"> Create Time </span>
                              </a>
                          </li>
                          @endcan
                          <li class="nav-item">
                              <a href="{{ route('admin.solo_schedule.index') }}" class="nav-link {{ setActive('admin/solo_schedule') }}">
                              <span class="sidenav-mini-icon"> S </span>
                              <span class="sidenav-normal"> Solo Schedule | Daily </span>
                              </a>
                          </li>
                          <li class="nav-item">
                              <a href="{{ route('admin.team_schedule.index') }}" class="nav-link {{ setActive('admin/team_schedule') }}">
                              <span class="sidenav-mini-icon"> T </span>
                              <span class="sidenav-normal"> Team Schedule | Daily </span>
                              </a>
                          </li>
                          <li class="nav-item">
                              <a href="{{ route('admin.vs_one_schedule.index') }}" class="nav-link {{ setActive('admin/vs_one_schedule') }}">
                              <span class="sidenav-mini-icon"> 1 </span>
                              <span class="sidenav-normal"> 1 VS 1 Schedule | Daily </span>
                              </a>
                          </li>
                          <li class="nav-item">
                              <a href="{{ route('admin.history_schedule.index') }}" class="nav-link {{ setActive('admin/history_schedule') }}">
                              <span class="sidenav-mini-icon">M </span>
                              <span class="sidenav-normal"> My Schedule </span>
                              </a>
                          </li>
                          <li class="nav-item">
                            <a href="{{ route('admin.vs_one.index') }}" class="nav-link {{ setActive('admin/vs_one') }}">
                            <span class="sidenav-mini-icon">1 </span>
                            <span class="sidenav-normal"> 1 VS 1 Schedule </span>
                            </a>
                          </li>
                          <li class="nav-item">
                              <a href="{{ route('admin.history_schedule.pending') }}" class="nav-link {{ setActive('admin/pending/history_schedule') }}">
                              <span class="sidenav-mini-icon"> P </span>
                              <span class="sidenav-normal"> Pending Schedule </span>
                              </a>
                          </li>
                          @can('roles.delete')
                          <li class="nav-item">
                              <a href="{{ route('admin.history_schedule.all') }}" class="nav-link {{ setActive('admin/all/history_schedule') }}">
                              <span class="sidenav-mini-icon"> H </span>
                              <span class="sidenav-normal"> Histories </span>
                              </a>
                          </li>
                          @endcan
                      </ul>
                  </div>
                  </li>
                @endcan
                @can('crown_packages.index')
                  <li class="nav-item">
                  <a class="nav-link {{ setActive('admin/transaction'). setActive('admin/crown_package')}}" href="#navbar-crown" data-toggle="collapse" role="button" aria-expanded="false" aria-controls="navbar-crown">
                    <i class="fas fa-crown text-yellow"></i>
                      <span class="nav-link-text">Topup Management</span>
                  </a>
                  <div class="collapse" id="navbar-crown">
                      <ul class="nav nav-sm flex-column">
                          @can('crown_packages.index')
                          <li class="nav-item">
                              <a class="nav-link {{ setActive('admin/crown') }}" href="{{ route('admin.crown_package.index') }}">
                                <span class="sidenav-mini-icon"> C </span>
                                <span class="sidenav-normal"> Crown Packages </span>
                              </a>
                          </li>
                          @endcan
                          @can('topup_charges.index')
                          <li class="nav-item">
                              <a href="{{ route('admin.transaction.index') }}" class="nav-link {{ setActive('admin/transaction') }}">
                              <span class="sidenav-mini-icon"> H </span>
                              <span class="sidenav-normal"> History Transaction </span>
                              </a>
                          </li>
                          @endcan
                      </ul>
                  </div>
                  </li>
                @endcan
                @can('games.index')
                <li class="nav-item">
                <a class="nav-link {{ setActive('admin/game'). setActive('admin/mmr') . setActive('admin/type')}}" href="#navbar-game" data-toggle="collapse" role="button" aria-expanded="false" aria-controls="navbar-game">
                  <i class="ni ni-controller text-green"></i>
                    <span class="nav-link-text">Game Management</span>
                </a>
                <div class="collapse" id="navbar-game">
                    <ul class="nav nav-sm flex-column">
                        @can('games.index')
                        <li class="nav-item">
                          <a class="nav-link {{ setActive('admin/game') }}" href="{{ route('admin.game.index') }}">
                              <span class="sidenav-mini-icon"> G </span>
                              <span class="sidenav-normal"> Games </span>
                            </a>
                        </li>
                        @endcan
                        @can('types.index')
                        <li class="nav-item">
                          <a class="nav-link {{ setActive('admin/type') }}" href="{{ route('admin.type.index') }}">
                            <span class="sidenav-mini-icon"> T </span>
                            <span class="sidenav-normal"> Types </span>
                            </a>
                        </li>
                        @endcan
                        @can('mmrs.index')
                        <li class="nav-item">
                          <a class="nav-link {{ setActive('admin/mmr') }}" href="{{ route('admin.mmr.index') }}">
                            <span class="sidenav-mini-icon"> M </span>
                            <span class="sidenav-normal"> Match Making Rate </span>
                            </a>
                        </li>
                        @endcan
                    </ul>
                </div>
                </li>
              @endcan

                @can('players.index')
                  <li class="nav-item">
                    <a class="nav-link {{ setActive('admin/player') }}" href="{{ route('admin.player.index') }}">
                      <i class="fas fa-chess text-info"></i>
                        <span class="nav-link-text">Players</span>
                    </a>
                  </li>
                @endcan

            </ul>
            <!-- Divider -->
            {{-- <hr class="my-3"> --}}
            <!-- Heading -->
            {{-- <h6 class="navbar-heading p-0 text-muted">
                <span class="docs-normal">Documentation</span>
                <span class="docs-mini">D</span>
            </h6> --}}
            <!-- Navigation -->
            {{-- <ul class="navbar-nav mb-md-3">
                <li class="nav-item">
                <a class="nav-link" href="https://demos.creative-tim.com/argon-dashboard-pro/docs/getting-started/overview.html" target="_blank">
                    <i class="ni ni-spaceship"></i>
                    <span class="nav-link-text">Getting started</span>
                </a>
                </li>
            </ul> --}}
        </div>
      </div>
    </div>
  </nav>
  <!-- Main content -->
  <div class="main-content" id="panel">
    <!-- Topnav -->
    <nav class="navbar navbar-top navbar-expand navbar-light bg-secondary border-bottom">
      <div class="container-fluid">
        <div class="collapse navbar-collapse" id="navbarSupportedContent">

          <!-- Navbar links -->
          <ul class="navbar-nav align-items-center  ml-md-auto ">
            <li class="nav-item d-xl-none">
              <!-- Sidenav toggler -->
              <div class="pr-3 sidenav-toggler sidenav-toggler-light" data-action="sidenav-pin" data-target="#sidenav-main">
                <div class="sidenav-toggler-inner">
                  <i class="sidenav-toggler-line"></i>
                  <i class="sidenav-toggler-line"></i>
                  <i class="sidenav-toggler-line"></i>
                </div>
              </div>
            </li>

          </ul>
          <ul class="navbar-nav align-items-center  ml-auto ml-md-0 ">
            <li class="nav-item dropdown">
              <a class="nav-link pr-0" href="#" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                <div class="media align-items-center">
                  <span class="avatar avatar-sm rounded-circle">
                      {{-- {{ dd(auth()->user()->avatar) }} --}}
                    <img alt="Image placeholder" src="{{ auth()->user()->avatar }}" style="height:100%; width:100%; object-fit:cover">
                  </span>
                  <div class="media-body  ml-2  d-none d-lg-block">
                    <span class="mb-0 text-sm  font-weight-bold">{{ auth()->user()->name }}</span>
                  </div>
                </div>
              </a>
              <div class="dropdown-menu  dropdown-menu-right ">
                <div class="dropdown-header noti-title">
                  <h6 class="text-overflow m-0">Welcome!</h6>
                </div>
                
                <a href="{{ route('admin.profile.index') }}" class="dropdown-item {{ setActive('admin/profile') }}">
                  <i class="ni ni-single-02"></i>
                  <span>My profile</span>
                </a>
                <div class="dropdown-divider"></div>
                <a href="#!" class="dropdown-item" data-toggle="modal" data-target="#logoutModal">
                  <i class="ni ni-user-run"></i>
                  <span>Logout</span>
                </a>
              </div>
            </li>
          </ul>
        </div>
      </div>
    </nav>
    <!-- Header -->
    <!-- Header -->
    <div class="header pb-6">
      <div class="container-fluid">
        <div class="header-body">
          <div class="row align-items-center py-4">
            <div class="col-lg-6 col-7">
              <h6 class="h2 d-inline-block mb-0">{{ isset($subTitle) ? $subTitle : $title }}</h6>
              <nav aria-label="breadcrumb" class="d-none d-md-inline-block ml-md-4">
                <ol class="breadcrumb breadcrumb-links">
                  <li class="breadcrumb-item"><a href="{{ route('admin.dashboard.index') }}"><i class="fas fa-home"></i></a></li>
                  <li class="breadcrumb-item"><a href="{{ route($link) }}">{{ $title }}</a></li>
                  @if (isset($subTitle))
                    <li class="breadcrumb-item active" aria-current="page">{{ $subTitle }}</li>  
                  @endif
                </ol>
              </nav>
            </div>
          </div>
        </div>
      </div>
    </div>

    <!-- Page content -->
    <div class="container-fluid mt--6">

      {{-- ALERT SUCCESS --}}
      <div id="success-alert" class="alert alert-success alert-dismissible fade show" role="alert" style="display:none">
          <span class="alert-icon"><i class="ni ni-like-2"></i></span>
          <span class="alert-text"><strong>Success!</strong> {{ session('success') }}</span>
          <button type="button" class="close" data-dismiss="alert" aria-label="Close">
              <span aria-hidden="true">&times;</span>
          </button>
      </div>
      {{-- END ALERT SUCCESS --}}
      {{-- ALERT ERROR --}}
      <div id="error-alert" class="alert alert-danger alert-dismissible fade show" role="alert" style="display: none">
          <span class="alert-icon"><i class="ni ni-like-2"></i></span>
          <span class="alert-text"><strong>Error!</strong> {{ session('error') }}</span>
          <button type="button" class="close" data-dismiss="alert" aria-label="Close">
              <span aria-hidden="true">&times;</span>
          </button>
      </div>
      {{-- END ALERT ERROR --}}
        @yield('content')
      
      <!-- Footer -->
      <footer class="footer pt-0">
        <div class="row align-items-center justify-content-lg-between">
          <div class="col-lg-6">
            <div class="copyright text-center  text-lg-left  text-muted">
              &copy; 2021 <a href="" class="font-weight-bold ml-1" target="_blank">Velocity League</a>
            </div>
          </div>
          {{-- <div class="col-lg-6">
            <ul class="nav nav-footer justify-content-center justify-content-lg-end">
              <li class="nav-item">
                <a href="https://www.creative-tim.com/presentation" class="nav-link" target="_blank">About Us</a>
              </li>
            </ul>
          </div> --}}
        </div>
      </footer>
    </div>

  </div>

  <!-- Logout Modal-->
  <div class="modal fade" id="logoutModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="exampleModalLabel">Apakah Yakin Ingin Keluar ?</h5>
          <button class="close" type="button" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">×</span>
          </button>
        </div>
        <div class="modal-body">Silahkan pilih "Logout" di bawah untuk mengakhiri sesi saat ini.</div>
        <div class="modal-footer">
          <button class="btn btn-secondary" type="button" data-dismiss="modal">Cancel</button>
          <a class="btn btn-danger" href="{{ route('logout') }}" style="cursor: pointer" onclick="event.preventDefault(); document.getElementById('logout-form').submit();">Logout</a>
          <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
            @csrf
        </form>
        </div>
      </div>
    </div>
  </div>
  <!-- Argon Scripts -->
  <!-- Core -->
  
  <script src="{{ asset('assets/vendor/bootstrap/dist/js/bootstrap.bundle.min.js') }}"></script>
  <script src="{{ asset('assets/vendor/js-cookie/js.cookie.js') }}"></script>
  <script src="{{ asset('assets/vendor/jquery.scrollbar/jquery.scrollbar.min.js') }}"></script>
  <script src="{{ asset('assets/vendor/jquery-scroll-lock/dist/jquery-scrollLock.min.js') }}"></script>
  <!-- Optional JS -->
  <script src="{{ asset('assets/vendor/chart.js/dist/Chart.min.js') }}"></script>
  <script src="{{ asset('assets/vendor/chart.js/dist/Chart.extension.js') }}"></script>
  
  
  {{-- <script src="{{ asset('assets/js/demo.min.js') }}"></script> --}}
  <!-- Demo JS - remove this in your project -->
  <script src="{{ asset('assets/vendor/datatables.net/js/jquery.dataTables.min.js') }}"></script>
  <script src="{{ asset('assets/vendor/datatables.net-bs4/js/dataTables.bootstrap4.min.js') }}"></script>
  <script src="{{ asset('assets/vendor/datatables.net-buttons/js/dataTables.buttons.min.js') }}"></script>
  <script src="{{ asset('assets/vendor/datatables.net-buttons-bs4/js/buttons.bootstrap4.min.js') }}"></script>
  <script src="{{ asset('assets/vendor/datatables.net-buttons/js/buttons.html5.min.js') }}"></script>
  <script src="{{ asset('assets/vendor/datatables.net-buttons/js/buttons.flash.min.js') }}"></script>
  <script src="{{ asset('assets/vendor/datatables.net-buttons/js/buttons.print.min.js') }}"></script>
  <script src="{{ asset('assets/vendor/datatables.net-select/js/dataTables.select.min.js') }}"></script>
  <!-- Argon JS -->
  <script src="{{ asset('assets/js/argon.js?v=1.2.0') }}"></script>
  <script>
    @if(session()->has('success'))
      $("#success-alert").show(1000)
      setTimeout(() => {
        $("#success-alert").hide(1000) 
      }, 6000);
    @elseif(session()->has('error'))
      $("#error-alert").show(1000)
      setTimeout(() => {
        $("#error-alert").hide(1000) 
      }, 6000);
    @endif
  </script>
    <script>
      const messaging = firebase.messaging();

      function sendTokenToServer(fcm_token) {
          // if(!'{{ Auth::user()->fcm_token }}') {
              const user_id = '{{ Auth::user()->id }}'
              axios.post('/api/savetoken/user', {
                  fcm_token, user_id
              })
              .then(data => {
                  console.log(data,'>>>>masuk');
              })
          // }
      }

      function retrieveToken () {
          messaging.getToken({ vapidKey: "BHVSzq0-7AAiCpKC9FSGZxA5n2NKSXYRIXO_9s4OVp5FDf0q5Xtg9SDrj7AS5-Ug8AmGNua3-m4q8hNjefPvdOg" }).then((currentToken) => {
          if (currentToken) {
              sendTokenToServer(currentToken)
          } else {
              // Show permission request UI
              console.log('No registration token available. Request permission to generate one.');
              Swal.fire('warning', 'you should allow notification','warning')
              // ...
          }
          }).catch((err) => {
          console.log('An error occurred while retrieving token. ', err);
          // ...
          });
      }
      retrieveToken()

      messaging.onTokenRefresh(() => {
          retrieveToken()
      })
      messaging.onMessage((payload) => {
        window.location.reload()
      })
      
  </script>
{{-- <script>
  const messaging = firebase.messaging();

  function sendTokenToServer(fcm_token) {
      // console.log(fcm_token,'>>>>TOKEN');
      const user_id = '{{ Auth::user()->id }}'
      axios.post('/api/savetoken/user', {
          fcm_token, user_id
      })
      .then(data => {
          console.log(data);
      })
  }

  function retrieveToken () {
      messaging.getToken({ vapidKey: "BHVSzq0-7AAiCpKC9FSGZxA5n2NKSXYRIXO_9s4OVp5FDf0q5Xtg9SDrj7AS5-Ug8AmGNua3-m4q8hNjefPvdOg" }).then((currentToken) => {
      if (currentToken) {
          sendTokenToServer(currentToken)
      } else {
          // Show permission request UI
          console.log('No registration token available. Request permission to generate one.');
          alert('you should allow notification')
          Swal.fire('warning', 'you should allow notification','warning')
          // ...
      }
      }).catch((err) => {
      console.log('An error occurred while retrieving token. ', err);
      // ...
      });
  }
  retrieveToken()

  messaging.onTokenRefresh(() => {
    console.log('masukkkk BERHASILLLLLLLLLL');
      retrieveToken()
  })
  firebase.messaging().onMessage(notification => {
    alert('Notification received!', notification);
  });
  
</script> --}}

</body>

</html>