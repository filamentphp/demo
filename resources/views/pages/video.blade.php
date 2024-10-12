@extends('.__base')

@section('content')

    <div class="page-wrapper">

        <main class="main">
            <div class="page-header"
                 style="background-image: url('images/demos/demo-beauty/page-header.jpg'); background-color: #ECEDF1;">
                <h1 class="page-title font-weight-bold text-dark">Video</h1>
                <ul class="breadcrumb pb-0">
                    <li class="text-dark"><a href="demo-beauty.html" class="text-dark"><i class="d-icon-home"></i></a>
                    </li>
                    <li class="delimiter text-dark">/</li>
                    <li class="text-dark">Video</li>
                </ul>
            </div>
            <!-- End PageHeader -->
            <div class="page-content mb-10 pb-3">
                <div class="container">
                    <nav class="toolbox toolbox-horizontal sticky-toolbox sticky-content fix-top">
                        <aside class="sidebar sidebar-fixed shop-sidebar">
                            <div class="sidebar-overlay"></div>
                            <a class="sidebar-close" href="#"><i class="d-icon-times"></i></a>
                        </aside>
                        <div class="toolbox-right">
                        </div>
                    </nav>

                    <div class="row cols-2 cols-sm-3 cols-md-4 product-wrapper">

                    </div>

                    <nav class="toolbox toolbox-pagination mb-1">
{{--                        <p class="toolbox-item show-info d-block mb-2 mb-sm-0">Showing 1–12 of 24 Products</p>--}}
{{--                        <ul class="pagination">--}}
{{--                            <li class="page-item disabled">--}}
{{--                                <a class="page-link page-link-prev" href="#" aria-label="Previous" tabindex="-1"--}}
{{--                                   aria-disabled="true">--}}
{{--                                    <i class="d-icon-arrow-left"></i>Prev--}}
{{--                                </a>--}}
{{--                            </li>--}}
{{--                            <li class="page-item active" aria-current="page"><a class="page-link" href="#">1</a></li>--}}
{{--                            <li class="page-item"><a class="page-link" href="#">2</a></li>--}}
{{--                            <li class="page-item">--}}
{{--                                <a class="page-link page-link-next" href="#" aria-label="Next">--}}
{{--                                    Next<i class="d-icon-arrow-right"></i>--}}
{{--                                </a>--}}
{{--                            </li>--}}
{{--                        </ul>--}}
                    </nav>
                </div>
            </div>

        </main>

        <!-- End Footer -->
    </div>
@endsection('content')
