@php
  $url_1 = Request::segment(2);
  $url_2 = Request::segment(3);
  $url_3 = Request::segment(4);
  $image=getSettingDataHelperapi('logo');
@endphp

<aside class="main-sidebar sidebar-dark-primary elevation-4">
    <!-- Brand Logo -->
    <a href="{{ route('admin.dashboard') }}" class="brand-link">
       <img src="{{getS3File($image)}}" alt="Mustafai Logo" class="brand-image" >
    </a>

    <!-- Sidebar -->
    <div class="sidebar">
      <!-- Sidebar user panel (optional) -->
      <div class="user-panel mt-3 pb-3 mb-3 d-flex">
        <div class="image">
          <img src="{{ getS3File(auth()->user()->profile) }}" id="admin_pic" class="img-circle elevation-2" alt="User Image">
        </div>
        <div class="info">
          <a href="{{ URL('admin/profile') }}" class="d-block">{{ auth()->user()->first_name }} {{ auth()->user()->last_name }}</a>
        </div>
      </div>

      <!-- Sidebar Menu -->
      <nav class="mt-2">
        <ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu" data-accordion="false">
          <li class="nav-item">
            <a href="{{ route('admin.dashboard') }}" class="nav-link {{ $url_1 == 'dashboard' ? 'active':'' }}">
              <i class="nav-icon fas fa-tachometer-alt"></i>
              <p>
                Dashboard
              </p>
            </a>
          </li>
          <!-- menu-open -->
          @if(have_right('View-Admin') || have_right('View-User') || have_right('View-Roles-Managment'))
            <li class="nav-item {{ ($url_1 == 'admins' || $url_1 == 'roles' || $url_1 == 'users') ? 'menu-open':'' }}">
              <a href="#" class="nav-link {{ ($url_1 == 'admins' || $url_1 == 'roles' || $url_1 == 'users') ? 'active':'' }}">
                <i class="nav-icon fas fa-cogs"></i>
                <p>
                  System
                  <i class="right fas fa-angle-left"></i>
                </p>
              </a>

              <ul class="nav nav-treeview">

                @if(have_right('View-Admin'))
                  <li class="nav-item">
                    <a href="{{ URL('admin/admins') }}" class="nav-link {{ $url_1 == 'admins' ? 'active':'' }}">
                      <i class="far fa-circle nav-icon"></i>
                      <p>Admins</p>
                    </a>
                  </li>
                @endif

                @if(have_right('View-User'))
                <li class="nav-item">
                  <a href="{{ URL('admin/users') }}" class="nav-link {{ $url_1 == 'users' ? 'active':'' }}">
                    <i class="far fa-circle nav-icon"></i>
                    <p>users</p>
                  </a>
                </li>
                @endif
                @if(have_right('View-Roles-Management'))
                <li class="nav-item">
                  <a href="{{ URL('admin/roles') }}" class="nav-link {{ $url_1 == 'roles' ? 'active':'' }}">
                    <i class="far fa-circle nav-icon"></i>
                    <p>Roles Management</p>
                  </a>
                </li>
                @endif
                @if(have_right('View-Roles-Management'))
                <li class="nav-item">
                  <a href="{{ URL('admin/designations') }}" class="nav-link {{ $url_1 == 'designations' ? 'active':'' }}">
                    <i class="far fa-circle nav-icon"></i>
                    <p>Designation Management</p>
                  </a>
                </li>
                @endif

              </ul>

            </li>
          @endif
        <!-- home menus by saad  -->

           @if(have_right('View-Header-Setting') || have_right('View-Slider') || have_right('View-Ceo-Message') || have_right('View-Office-Address'))
           <li class="nav-item {{ ($url_1 == 'slider' || $url_1 == 'ceomessage'  || $url_1 == 'office-addresses' || $url_1 == 'header-settings' ) ? 'menu-open':'' }}">
             <a href="#" class="nav-link {{ ($url_1 == 'slider' || $url_1 == 'office-addresses' || $url_1 == 'ceomessage' ) ? 'active':'' }}">
               <i class="nav-icon fas fa-home"></i>
               <p>
                  Home Settings
                 <i class="right fas fa-angle-left"></i>
               </p>
             </a>

             <ul class="nav nav-treeview">

              @if(have_right('View-Header-Setting'))
               <li class="nav-item">
                 <a href="{{ URL('admin/header-settings') }}" class="nav-link {{ $url_1 == 'header-settings' ? 'active':'' }}">
                   <i class="far fa-circle nav-icon"></i>
                   <p>Header Settings</p>
                 </a>
               </li>
               @endif

               @if(have_right('View-Slider'))
               <li class="nav-item">
                 <a href="{{ URL('admin/slider') }}" class="nav-link {{ $url_1 == 'slider' ? 'active':'' }}">
                   <i class="far fa-circle nav-icon"></i>
                   <p>Sliders</p>
                 </a>
               </li>
               @endif

               @if(have_right('View-Ceo-Message'))
               <li class="nav-item">
                 <a href="{{ URL('admin/ceomessage') }}" class="nav-link {{ $url_1 == 'ceomessage' ? 'active':'' }}">
                   <i class="far fa-circle nav-icon"></i>
                   <p>Menifesto</p>
                 </a>
               </li>
               @endif
               @if(have_right('View-Office-Address'))
               <li class="nav-item">
                 <a href="{{ URL('admin/office-addresses') }}" class="nav-link {{ $url_1 == 'office-addresses' ? 'active':'' }}">
                   <i class="far fa-circle nav-icon"></i>
                   <p>Office Address</p>
                 </a>
               </li>
               @endif
             </ul>
           </li>
         @endif

          <!-- end home menus by saad  -->

            <!-- Contact & subscriptions menus by saad  -->

          @if(have_right('View-Contacts') || have_right('View-Subscriptions'))
          <li class="nav-item {{ ($url_1 == 'contacts' || $url_1 == 'subscriptions' ) ? 'menu-open':'' }}">
            <a href="#" class="nav-link {{ ($url_1 == 'contacts' || $url_1 == 'subscriptions' ) ? 'active':'' }}">
              {{-- <i class="nav-icon fas fa-home"></i> --}}
              <i class="nav-icon fas fa-folder"></i>
              <p>
                  Queries
                <i class="right fas fa-angle-left"></i>
              </p>
            </a>

            <ul class="nav nav-treeview">

              @if(have_right('View-Contacts'))
              <li class="nav-item">
                <a href="{{ URL('admin/contacts') }}" class="nav-link {{ $url_1 == 'contacts' ? 'active':'' }}">
                  <i class="far fa-circle nav-icon"></i>
                  <p>Contact Us</p>
                </a>
              </li>
              @endif

              @if(have_right('View-Subscriptions'))
              <li class="nav-item">
                <a href="{{ URL('admin/subscriptions') }}" class="nav-link {{ $url_1 == 'subscriptions' ? 'active':'' }}">
                  <i class="far fa-circle nav-icon"></i>
                  <p>Newsletter Subscription</p>
                </a>
              </li>
              @endif

            </ul>

          </li>
        @endif

          <!-- Adress menus by saad  -->
          @if(have_right('View-Address') )
          <li class="nav-item {{ ($url_1 == 'countries' || $url_1 == 'provinces' || $url_1 == 'divisions' || $url_1 == 'districts' || $url_1 == 'tehsils' || $url_1 == 'cities' || $url_1 == 'union-councils' || $url_1 == 'branches' ) ? 'menu-open':'' }}">
            <a href="#" class="nav-link {{ ($url_1 == 'countries' || $url_1 == 'provinces' || $url_1 == 'divisions' || $url_1 == 'districts' || $url_1 == 'tehsils' || $url_1 == 'cities' || $url_1 == 'union-councils' || $url_1 == 'branches'   ) ? 'active':'' }}">
              <i class="nav-icon fas fa-map-marker"></i>
              <p>
                  Address Menu
                <i class="right fas fa-angle-left"></i>

              </p>
            </a>

            <ul class="nav nav-treeview">

              @if(have_right('View-Address'))
              <li class="nav-item">
                <a href="{{ URL('admin/countries') }}" class="nav-link {{ $url_1 == 'countries' ? 'active':'' }}">
                  <i class="far fa-circle nav-icon"></i>
                  <p>Add Country</p>
                </a>
              </li>
              @endif
              @if(have_right('View-Address'))
              <li class="nav-item">
                <a href="{{ URL('admin/provinces') }}" class="nav-link {{ $url_1 == 'provinces' ? 'active':'' }}">
                  <i class="far fa-circle nav-icon"></i>
                  <p>Add Provinces</p>
                </a>
              </li>
              @endif

              @if(have_right('View-Address'))
              <li class="nav-item">
                <a href="{{ URL('admin/divisions') }}" class="nav-link {{ $url_1 == 'divisions' ? 'active':'' }}">
                  <i class="far fa-circle nav-icon"></i>
                  <p>Add Divisions</p>
                </a>
              </li>
              @endif

              @if(have_right('View-Address'))
              <li class="nav-item">
                <a href="{{ URL('admin/districts') }}" class="nav-link {{ $url_1 == 'districts' ? 'active':'' }}">
                  <i class="far fa-circle nav-icon"></i>
                  <p>Add District</p>
                </a>
              </li>
              @endif

              @if(have_right('View-Address'))
              <li class="nav-item">
                <a href="{{ URL('admin/tehsils') }}" class="nav-link {{ $url_1 == 'tehsils' ? 'active':'' }}">
                  <i class="far fa-circle nav-icon"></i>
                  <p>Add Tehsils</p>
                </a>
              </li>
              @endif
              @if(have_right('View-Address'))
              <li class="nav-item">
                <a href="{{ URL('admin/branches') }}" class="nav-link {{ $url_1 == 'branches' ? 'active':'' }}">
                  <i class="far fa-circle nav-icon"></i>
                  <p>Add Branch</p>
                </a>
              </li>
              @endif

              @if(have_right('View-Address'))
              <li class="nav-item">
                <a href="{{ URL('admin/union-councils') }}" class="nav-link {{ $url_1 == 'union-councils' ? 'active':'' }}">
                  <i class="far fa-circle nav-icon"></i>
                  <p>Add Union Council</p>
                </a>
              </li>
              @endif
              @if(have_right('View-Address'))
              <li class="nav-item">
                <a href="{{ URL('admin/cities') }}" class="nav-link {{ $url_1 == 'cities' ? 'active':'' }}">
                  <i class="far fa-circle nav-icon"></i>
                  <p>Add City</p>
                </a>
              </li>
              @endif

            </ul>

          </li>
        @endif
        <!-- end Address menus by saad  -->

        <!-- start of library section-->

        @if(have_right('View-Library-Section'))

        <li class="nav-item">
          <a href="{{ URL('admin/library-section') }}" class="nav-link {{ $url_1 == 'library-section' ? 'active':'' }}">
          <i class="nav-icon fa fa-camera" aria-hidden="true"></i>
            <p>
              library sections
            </p>
          </a>
        </li>
        @endif

        {{-- @if(have_right('View-Book-Receipt'))
          <li class="nav-item">
            <a href="{{ URL('admin/book-receipts') }}" class="nav-link {{ $url_1 == 'book-receipts' ? 'active':'' }}">
              <i class="nav-icon fas  fa-book"></i>
              <p>
                Book Receipt
              </p>
            </a>
          </li>
        @endif --}}


         @if(have_right('View-Image-Gallery') || have_right('View-Video-Gallery') || have_right('View-Audio-Gallery') || have_right('View-Book-Gallery') || have_right('View-Document-Gallery') )
         <li class="nav-item {{ ($url_1 == 'library'  ) ? 'menu-open':'' }}">
           <a href="#" class="nav-link {{ ($url_1 == 'library'  ) ? 'active':'' }}">
            <i class="nav-icon fas fa-photo-video"></i>
             <p>
                 Library
               <i class="right fas fa-angle-left"></i>

             </p>
           </a>

           <ul class="nav nav-treeview">

             @if(have_right('View-Image-Gallery'))
             <li class="nav-item">
               <a href="{{ URL('admin/library/1/edit') }}" class="nav-link {{ $url_1.$url_2 == 'library1' ? 'active':'' }}">
                 <i class="far fa-circle nav-icon"></i>
                 <p>Image Gallery</p>
               </a>
             </li>
             @endif

             @if(have_right('View-Video-Gallery'))
             <li class="nav-item">
               <a href="{{ URL('admin/library/2/edit') }}" class="nav-link {{ $url_1.$url_2 == 'library2' ? 'active':'' }}">
                 <i class="far fa-circle nav-icon"></i>
                 <p>Video Gallery</p>
               </a>
             </li>
             @endif

             @if(have_right('View-Audio-Gallery'))
             <li class="nav-item">
               <a href="{{ URL('admin/library/3/edit') }}" class="nav-link {{ $url_1.$url_2 == 'library3' ? 'active':'' }}">
                 <i class="far fa-circle nav-icon"></i>
                 <p>Audio Gallery</p>
               </a>
             </li>
             @endif

             @if(have_right('View-Book-Gallery'))
             <li class="nav-item">
               <a href="{{ URL('admin/library/4/edit') }}" class="nav-link {{ $url_1.$url_2 == 'library4' ? 'active':'' }}">
                 <i class="far fa-circle nav-icon"></i>
                 <p>Book Gallery</p>
               </a>
             </li>
             @endif

             @if(have_right('View-Document-Gallery'))
             <li class="nav-item">
               <a href="{{ URL('admin/library/5/edit') }}" class="nav-link {{ $url_1.$url_2 == 'library5' ? 'active':'' }}">
                 <i class="far fa-circle nav-icon"></i>
                 <p>Document Library</p>
               </a>
             </li>
             @endif


           </ul>

         </li>
       @endif
        <!-- end of library section-->
        @if(have_right('View-Pages'))
          <li class="nav-item">
            <a href="{{ URL('admin/pages') }}" class="nav-link {{ $url_1 == 'pages' ? 'active':'' }}">
              <i class="nav-icon fas fa-file"></i>
              <p>
                Pages
              </p>
            </a>
          </li>
          @endif

            @if(have_right('View-Events'))
                <li class="nav-item">
                  <a href="{{ URL('admin/events') }}" class="nav-link {{ $url_1 == 'events' || $url_1 =='event-attendes'  ? 'active':'' }}">
                    <i class="nav-icon fas fa-calendar-alt"></i>
                    <p>
                    Events
                    </p>
                  </a>
                </li>
            @endif
            @if(have_right('View-Blood-Donors'))
                <li class="nav-item">
                  <a href="{{ URL('admin/donors') }}" class="nav-link {{ $url_1 == 'donors' ? 'active':'' }}">
                    {{-- <i class="nav-icon fas fa-hand-holding-usd"></i> --}}
                    {{-- <i class="nav-icon fas fa-hand-holding-medical"></i> --}}
                    <img src="{{asset('assets/admin/dist/img/blod-donor.png')}}" alt="no image" class="brand-image nav-icon" >
                    <p>
                    Blood Donor
                    </p>
                  </a>
                </li>
            @endif

            {{-- Sections Main  --}}
            @if(have_right('View-Our-team-Section'))
              <li class="nav-item">
                <a href="{{ URL('admin/sections') }}" class="nav-link {{ $url_1 == 'sections' ? 'active':'' }}">
                  <i class=" nav-icon fas fa-users"></i>
                  <p>Our Team Sections</p>
                </a>
              </li>
            @endif


            @if(have_right('View-Employee-Sections'))
              <li class="nav-item">
                <a href="{{ URL('admin/employee-sections') }}" class="nav-link {{ $url_1 == 'employee-sections' ? 'active':'' }}">
                <i class="nav-icon fa fa-user" aria-hidden="true"></i>
                  <p>Team Members</p>
                </a>
              </li>
            @endif

          <li class="nav-item d-none">
            <a href="{{ URL('admin/announcements') }}" class="nav-link {{ $url_1 == 'announcements' ? 'active':'' }}">
              <i class="nav-icon fas fa-tachometer-alt"></i>
              <p>
                Announcements
              </p>
            </a>
          </li>
          @if(have_right('View-Testimonials'))

          <li class="nav-item">
            <a href="{{ URL('admin/testimonials') }}" class="nav-link {{ $url_1 == 'testimonials' ? 'active':'' }}">
              <i class="nav-icon fas fa-user-friends"></i>
              <p>
                Testimonials
              </p>
            </a>
          </li>
          @endif
          {{-- @if(have_right('View-Doantions'))

          <li class="nav-item">
            <a href="{{ URL('admin/donations') }}" class="nav-link {{ $url_1 == 'donations' ? 'active':'' }}">

              <i class=" nav-icon fas fa-box"></i>
              <p>
                Donations
              </p>
            </a>
          </li>
         @endif
         @if(have_right('View-Donation-Payment-Method'))
         <li class="nav-item">
           <a href="{{ URL('admin/donation-payment-method') }}" class="nav-link {{ $url_1 == 'donation-payment-method' ? 'active':'' }}">
             <i class="nav-icon fas fa-file"></i>
             <p>
               Donations Methods
             </p>
           </a>
         </li>
       @endif --}}


       @if(have_right('View-Doantions') || have_right('View-Donation-Payment-Method')||have_right('View-Donation-Categories'))
       <li class="nav-item {{ ($url_1 == 'donations'|| $url_1=='donation-categories' || $url_1 == 'donation-payment-method' ) ? 'menu-open':'' }}">
         <a href="#" class="nav-link {{ ($url_1 == 'donations' || $url_1=='donation-categories' || $url_1 == 'donation-payment-method' ) ? 'active':'' }}">

           {{-- <i class="nav-icon fas fa-box"></i> --}}
           <i class="nav-icon fas fa-hand-holding-usd"></i>
           <p>
               Donations
              <i class="right fas fa-angle-left"></i>

           </p>
         </a>

         <ul class="nav nav-treeview">

           @if(have_right('View-Donations'))
           <li class="nav-item">
            <a href="{{ URL('admin/donations') }}" class="nav-link {{ $url_1 == 'donations' ? 'active':'' }}">
              <i class=" nav-icon fas fa-box"></i>
              <p>
                Donations

              </p>
            </a>
          </li>
           @endif

           @if(have_right('View-Donation-Categories'))
               <li class="nav-item">
                 <a href="{{ URL('admin/donation-categories') }}" class="nav-link {{ $url_1 == 'donation-categories' ? 'active':'' }}">
                   <i class="fa fa-list-ul nav-icon"></i>
                   <p>Donation Categories</p>
                 </a>
               </li>
               @endif

           @if(have_right('View-Donation-Methods'))
           <li class="nav-item">
            <a href="{{ URL('admin/donation-payment-method') }}" class="nav-link {{ $url_1 == 'donation-payment-method' ? 'active':'' }}">
              <i class=" nav-icon fas fa-money-check-alt"></i>
              <p>
                Donations  Methods
              </p>
            </a>
          </li>
           @endif

         </ul>

       </li>
     @endif
         @if(have_right('View-Headlines'))

          <li class="nav-item">
            <a href="{{ URL('admin/headlines') }}" class="nav-link {{ $url_1 == 'headlines' ? 'active':'' }}">
              <i class="nav-icon fas fa-comment"></i>
              <p>
                Headlines
              </p>
            </a>
          </li>
        @endif

        @if(have_right('View-Occupations'))

          <li class="nav-item">
            <a href="{{ URL('admin/occupations') }}" class="nav-link {{ $url_1 == 'occupations' ? 'active':'' }}">
              <i class="nav-icon fas fa-user-md"></i>
              <p>
                Professions
              </p>
            </a>
          </li>
        @endif

        @if(have_right('View-Posts'))
          <li class="nav-item">
              <a href="{{ URL('admin/posts') }}" class="nav-link {{ $url_1 == 'posts' ? 'active':'' }}">
                  <i class="nav-icon fab fa-usps"></i>
                  <p>
                      Posts
                  </p>
              </a>
          </li>
        @endif

        {{-- Magazine section --}}
         @if(have_right('View-Magazines-Category') || have_right('View-Magazines') )
         <li class="nav-item {{ ($url_1 == 'magazines'|| $url_1=='magazine-categories') ? 'menu-open':'' }}">
           <a href="#" class="nav-link {{ ($url_1 == 'magazine-categories' || $url_1 == 'magazines' ) ? 'active':'' }}">
            {{-- <i class="nav-icon fas fa fa-book"></i> --}}
            <i class="fas nav-icon fa-book-open"></i>
             <p>
                 Magazine
               <i class="right fas fa-angle-left"></i>

             </p>
           </a>

           <ul class="nav nav-treeview">

             @if(have_right('View-Magazines-Category'))
             <li class="nav-item">
               <a href="{{ URL('admin/magazine-categories') }}" class="nav-link {{ $url_1 == 'magazine-categories' ? 'active':'' }}">
                 <i class="far fa-circle nav-icon"></i>
                 <p>Magazines Category</p>
               </a>
             </li>
             @endif

             @if(have_right('View-Magazines'))
             <li class="nav-item">
               <a href="{{ URL('admin/magazines') }}" class="nav-link {{ $url_1== 'magazines' ? 'active':'' }}">
                 <i class="far fa-circle nav-icon"></i>
                 <p>Magazines</p>
               </a>
             </li>
             @endif

           </ul>

         </li>
       @endif

        {{-- end magazine section --}}

        {{-- Bank section --}}
        @if(have_right('View-Bank-Account') || have_right('View-Bank') )
        <li class="nav-item {{ ($url_1 == 'banks'|| $url_1=='bank-accounts') ? 'menu-open':'' }}">
          <a href="#" class="nav-link {{ ($url_1 == 'bank-accounts' || $url_1 == 'banks' ) ? 'active':'' }}">
           {{-- <i class="nav-icon fas fa fa-book"></i> --}}
           <i class="nav-icon fa fas fa-university"></i>
            <p>
                Bank
              <i class="right fas fa-angle-left"></i>

            </p>
          </a>

          <ul class="nav nav-treeview">


            @if(have_right('View-Bank'))
            <li class="nav-item">
              <a href="{{ URL('admin/banks') }}" class="nav-link {{ $url_1== 'banks' ? 'active':'' }}">
                <i class="far fa-circle nav-icon"></i>
                <p>Banks</p>
              </a>
            </li>
            @endif

            @if(have_right('View-Bank-Account'))
            <li class="nav-item">
              <a href="{{ URL('admin/bank-accounts') }}" class="nav-link {{ $url_1 == 'bank-accounts' ? 'active':'' }}">
                <i class="far fa-circle nav-icon"></i>
                <p>Bank Accounts</p>
              </a>
            </li>
            @endif
          </ul>

        </li>
      @endif

       {{-- end Bank section --}}

        @if(have_right('View-Cabinets'))
            <li class="nav-item">
                <a href="{{ URL('admin/cabinets') }}" class="nav-link {{ $url_1 == 'cabinets' ? 'active':'' }}">

                <i class=" nav-icon fas fa-boxes"></i>
                <p>
                    Cabinets
                </p>
                </a>
            </li>
        @endif
        @if(have_right('View-Business-Plans'))
          <li class="nav-item">
            <a href="{{ URL('admin/busines_plans') }}" class="nav-link {{ $url_1 == 'busines_plans' ? 'active':'' }}">

              <i class=" nav-icon fas fa-business-time"></i>
              <p>
                Business Plans
              </p>
            </a>
          </li>
       @endif
       @if(have_right('View-Business-Heading'))

        <li class="nav-item">
          <a href="{{ URL('admin/business-heading') }}" class="nav-link {{ $url_1 == 'business-heading' ? 'active':'' }}">

          <i class="nav-icon fa fa-address-book" aria-hidden="true"></i>
            <p>
              Business Heading
            </p>
          </a>
        </li>
      @endif
        @if(have_right('View-Shipment-Rate') || have_right('View-Vendors') || have_right('View-Categories') || have_right('View-Products') || have_right('View-Orders'))
          <li class="nav-item {{ ($url_1 == 'products' || $url_1 == 'categories' || $url_1 == 'shipment-rate' || $url_1 == 'orders' || $url_1 == 'vendors' || $url_1 == 'product-payment-method' ) ? 'menu-open':'' }}">
             <a href="#" class="nav-link {{ ($url_1 == 'products' || $url_1 == 'categories' || $url_1 == 'orders' || $url_1 == 'vendors'  || $url_1 == 'product-payment-method' ) ? 'active':'' }}">
               <i class="nav-icon fas fa-store"></i>
               <p>
                  Store And Sales
                 <i class="right fas fa-angle-left"></i>

               </p>
             </a>

             <ul class="nav nav-treeview">
              @if(have_right('View-Product-Payment-Method'))
              <li class="nav-item">
               <a href="{{ URL('admin/product-payment-method') }}" class="nav-link {{ $url_1 == 'product-payment-method' ? 'active':'' }}">
                <i class="far fa-circle nav-icon"></i>
                 <p>
                     Payment Methods
                 </p>
               </a>
             </li>
              @endif
              @if(have_right('View-Vendors'))
              <li class="nav-item">
                <a href="{{ URL('admin/vendors') }}" class="nav-link {{ $url_1 == 'vendors' ? 'active':'' }}">
                  <i class="far fa-circle nav-icon"></i>
                  <p>Vendors</p>
                </a>
              </li>
              @endif
              @if(have_right('View-Shipment-Rate'))
              <li class="nav-item">
                <a href="{{ URL('admin/shipment-rate') }}" class="nav-link {{ $url_1 == 'shipment-rate' ? 'active':'' }}">
                  <i class="far fa-circle nav-icon"></i>
                  <p>Shipment Rate</p>
                </a>
              </li>
              @endif
              @if(have_right('View-Categories'))
               <li class="nav-item">
                 <a href="{{ URL('admin/categories') }}" class="nav-link {{ $url_1 == 'categories' ? 'active':'' }}">
                   <i class="far fa-circle nav-icon"></i>
                   <p>Categories</p>
                 </a>
               </li>
               @endif
               @if(have_right('View-Products'))
               <li class="nav-item">
                 <a href="{{ URL('admin/products') }}" class="nav-link {{ $url_1 == 'products' ? 'active':'' }}">
                   <i class="far fa-circle nav-icon"></i>
                   <p>Products</p>
                 </a>
               </li>
               @endif

               @if(have_right('View-Orders'))
               <li class="nav-item">
                 <a href="{{ URL('admin/orders') }}" class="nav-link {{ $url_1 == 'orders' ? 'active':'' }}">
                   <i class="far fa-circle nav-icon"></i>
                   <p>Orders</p>
                 </a>
               </li>
               @endif

             </ul>

              {{-- book receipt  --}}
              @if(have_right('View-Book-Receipt')|| have_right('View-Book-leafs') )
              <li class="nav-item">
                <a href="{{ URL('admin/book-receipts') }}" class="nav-link {{ $url_1 == 'book-receipts' || $url_1 == 'book-receipts-leaf' ? 'active':'' }}">
                  <i class="nav-icon fas  fa-book"></i>
                  <p>
                    Book Receipt
                  </p>
                </a>
              </li>
            @endif
           </li>
          @endif
        </ul>
      </nav>
      <!-- /.sidebar-menu -->
    </div>
    <!-- /.sidebar -->
  </aside>
