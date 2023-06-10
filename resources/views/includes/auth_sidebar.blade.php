<div class="sidebar">
                <!-- Start Logobar -->
                <div class="logobar">
                    <a href="index.html" class="logo logo-large">
                      <img src="{{ asset('assets/images/thespacelit/logo-black.png') }}" class="img-fluid" alt="logo">
                    </a>
                    <a href="index.html" class="logo logo-small">
                      <img src="{{ asset('assets/images/small_logo.svg') }}" class="img-fluid" alt="logo">
                    </a>
                </div>
                <!-- End Logobar -->
                <!-- Start Profilebar -->
                <div class="profilebar text-center">
                    <img src="{{ asset('assets/images/users/profile.svg') }}" class="img-fluid" alt="profile">
                    <div class="profilename">
                      <h5>{{ Auth::user()->name }}</h5>
                      <p>Social Media Strategist</p>
                    </div>
                    <div class="userbox">
                        <ul class="list-inline mb-0">
                            <li class="list-inline-item">
                              <a href="{{route('user.profile')}}" class="profile-icon">
                                <img src="{{ asset('assets/images/svg-icon/user.svg') }}" class="img-fluid" alt="user">
                              </a>
                            </li>
                            <li class="list-inline-item">
                              <a href="#" class="profile-icon">
                                <img src="{{ asset('assets/images/svg-icon/email.svg') }}" class="img-fluid" alt="email">
                              </a>
                            </li>
                            <li class="list-inline-item">
                                <a href="{{ route('logout') }}" class="profile-icon" onclick="event.preventDefault();
                                                document.getElementById('logout-form').submit();">
                                    <img src="{{ asset('assets/images/svg-icon/logout.svg') }}" class="img-fluid" alt="{{ __('Logout') }}">
                                </a>
                                <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">
                                    @csrf
                                </form>
                            </li>

                        </ul>
                      </div>
                </div>
                <!-- End Profilebar -->
                <!-- Start Navigationbar -->
                <div class="navigationbar">
                    <ul class="vertical-menu">
                        <li class="vertical-header">Main</li>

                        @can('admin-dashboard')
                          <li>
                              <a href="{{ route('admin.dashboard') }}">
                                <img src="{{ asset('assets/images/svg-icon/dashboard.svg') }}" class="img-fluid" alt="dashboard">
                                <span>Dashboard</span>
                              </a>
                          </li>
                        @endcan

                        @can('user-dashboard')
                          <li>
                              <a href="{{ route('user.dashboard') }}">
                                <img src="{{ asset('assets/images/svg-icon/dashboard.svg') }}" class="img-fluid" alt="dashboard">
                                <span>Dashboard</span>
                              </a>
                          </li>
                        @endcan

                        @can('user-list')
                        <li>
                            <a href="{{ route('admin.users') }}">
                              <img src="{{ asset('assets/images/svg-icon/user.svg') }}" class="img-fluid" alt="Users">
                              <span>Users</span>
                            </a>
                        </li>
                        @endcan
                        <li>
                            <a href="{{ route('chat') }}">
                              <img src="{{ asset('assets/images/svg-icon/apps.svg') }}" class="img-fluid" alt="Chat">
                              <span>Chat</span>
                            </a>
                        </li>
                        <li>
                            <a href="javaScript:void();">
                              <img src="{{ asset('assets/images/svg-icon/dashboard.svg') }}" class="img-fluid" alt="dashboard">
                              <span>Attendance</span>
                            </a>
                        </li>
                        <li>
                            <a href="javaScript:void();">
                              <img src="{{ asset('assets/images/svg-icon/dashboard.svg') }}" class="img-fluid" alt="dashboard">
                              <span>Leave</span>
                            </a>
                        </li>
                    </ul>
                </div>
                <!-- End Navigationbar -->
            </div>
