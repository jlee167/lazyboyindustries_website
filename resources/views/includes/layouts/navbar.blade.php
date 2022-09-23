<?php
use App\Models\User;
?>


<nav id="mainNavbar"
  class="navbar navbar-expand-lg navbar-light navbar-default bg-light fixed-top"
  :style="{ backgroundColor: navColor, borderBottom: bottomBorder }">
  <article class="container-fluid">

    <section id="brand-container" class="navbar-brand brand-text">
      <img id="brand-logo"
        src="{{ asset('/images/GitHub-Mark-Light-32px.png') }}">
      <p id="brand-name">LazyBoy Industries</p>
    </section>

    <button class="navbar-toggler" type="button" data-toggle="collapse"
      data-target="#collapsibleNavbar" style="margin-right:20px;">
      <img src="/images/icon-list.svg">
    </button>

    <div class="collapse navbar-collapse" id="collapsibleNavbar">
      <ul id="menu-links" class="navbar-nav mr-auto">
        <li class="nav-item"> <a class="nav-link" href="/views/main">
            Home</a></li>
        <li class="nav-item">
          <a id="resumeBtn" class="nav-link pointer" @click="enableModal()"
            onmouseover="">About</a>
        </li>
        <li class="nav-item dropdown">
          <a class="nav-link dropdown-toggle" href="#"
            id="navbarDarkDropdownMenuLink" role="button" data-toggle="dropdown"
            data-target="productsDropdown" aria-expanded="false">
            Shop
          </a>
          <ul id="productsDropdown" class="dropdown-hover">
            <li><a class="dropdown-item" href="/views/sales/1">USB Camera</a>
            </li>
            <li><a class="dropdown-item" href="/views/sales/2">LTE Camera</a>
            </li>
            <li><a class="dropdown-item" href="/views/sales/3">Wifi Camera</a>
            </li>
            <li><a class="dropdown-item" href="/views/sales/4">FPGA Camera</a>
            </li>
          </ul>
        </li>
        <li class="nav-item"> <a class="nav-link"
            href="/views/dashboard?page=1"> Forum</a></li>
        <li class="nav-item"> <a class="nav-link"
            href="/views/support"> Support</a></li>
        <li class="nav-item">
          <a class="nav-link"
            href="/views/peers">Broadcast</a>
        </li>
      </ul>

      <div class="my-2 my-lg-0 d-flex flex-row align-items-center justify-content-center">


        @auth
          {{-- For Authorized Users --}}
          <div class="dropdown">
            <button class="btn btn-secondary dropdown-toggle" type="button"
              id="dropdownMenuButton" data-toggle="dropdown" aria-haspopup="true"
              aria-expanded="false">
              <a class="dropdown" href="/views/user-info"
                style="margin:0 0 0 0; margin-right:5px; padding: 0 0 0 0;">
                <img id="profileImage"
                  style="box-shadow: 0px 0px 4px 2px rgba(0, 174, 255, 0.753);"
                  src="{{ User::getDefaultImageUrl() }}"></a>
            </button>
            <div class="dropdown-menu dropdown-menu-right"
              aria-labelledby="dropdownMenuButton">
              <a class="dropdown-item" href="/views/user-info">
                <p class="user-dropdown">My Info</p>
              </a>
              <a class="dropdown-item" href="/views/peers">
                <p class="user-dropdown">Friends</p>
              </a>
              <a class="dropdown-item" href="/views/cart">
                <p class="user-dropdown">Cart</p>
              </a>
              <a class="dropdown-item" href="/views/purchase_history">
                <p class="user-dropdown">My Purchases</p>
              </a>
              <a class="dropdown-item" @click="logout()">
                <p class="user-dropdown">Logout</p>
              </a>
            </div>
          </div>
        @endauth

        @guest
          {{-- For guests --}}
          <a id="signBtn" href="/login/redirect" role="button">
            Sign In
          </a>
        @endguest

      </div>
    </div>
  </article>
</nav>

<script src="{{ mix('js/navbar.js') }}"></script>


@include('includes.layouts.modal')
