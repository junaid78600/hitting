<div class="vertical-menu">

    <div data-simplebar class="h-100">

        <!--- Sidemenu -->
        <div id="sidebar-menu">
            <!-- Left Menu Start -->
            <ul class="metismenu list-unstyled" id="side-menu">
                <li class="menu-title">Menu</li>

                <li>
                    <a href="home" class="waves-effect">
                        <i class="bx bx-home-circle"></i>
                        <span>Dashboard</span>
                    </a>
                </li>

                <li>
                    <a href="javascript: void(0);" class="has-arrow waves-effect">
                        <i class="bx bx-briefcase-alt-2"></i>
                        <span>Management</span>
                    </a>
                    <ul class="sub-menu" aria-expanded="false">
                        <li><a href="{{url('admin/user')}}">User</a></li>
                    </ul>
                </li>

                <li>
                    <a href="{{route('admin.category.index')}}" class="waves-effect">
                        <i class="bx bx-reply"></i>
                        <span>Category Managemnet</span>
                    </a>
                </li>

                <li>
                    <a href="{{route('admin.product.index')}}" class="waves-effect">
                        <i class="bx bxs-basket"></i>
                        <span>Product Managemnet</span>
                    </a>
                </li>



            </ul>
        </div>
        <!-- Sidebar -->
    </div>
</div>

            <div class="main-content">