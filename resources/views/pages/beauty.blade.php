@extends('.__base')

@section('content')

    <div class="page-wrapper">

        <main class="main">
            <div class="page-header"
                 style="background-image: url('images/demos/demo-beauty/page-header.jpg'); background-color: #ECEDF1;">
                <h1 class="page-title font-weight-bold text-dark">Beauty</h1>
                <ul class="breadcrumb pb-0">
                    <li class="text-dark"><a href="demo-beauty.html" class="text-dark"><i class="d-icon-home"></i></a>
                    </li>
                    <li class="delimiter text-dark">/</li>
                    <li class="text-dark">Beauty</li>
                </ul>
            </div>
            <!-- End PageHeader -->
            <div class="page-content mb-10 pb-3 mt-5">
                <div class="container">
                    <section class="default-section">
                        <h2 class="title title-center">Default</h2>
                        <div class="code-template">
                            <div class="row">
                                <div class="col-sm-6 col-lg-3 mb-4">
                                    <div
                                        class="category category-default category-rounded category-absolute overlay-zoom">
                                        <a href="#">
                                            <figure class="category-media">
                                                <img src="images/categories/category2.jpg" alt="category" width="280"
                                                     height="280" />
                                            </figure>
                                        </a>

                                    </div>
                                </div>
                                <div class="col-sm-6 col-lg-3 mb-4">
                                    <div
                                        class="category category-default category-rounded category-absolute overlay-zoom">
                                        <a href="#">
                                            <figure class="category-media">
                                                <img src="images/categories/category2.jpg" alt="category" width="280"
                                                     height="280" />
                                            </figure>
                                        </a>

                                    </div>
                                </div>
                                <div class="col-sm-6 col-lg-3 mb-4">
                                    <div
                                        class="category category-default category-rounded category-absolute overlay-zoom">
                                        <a href="#">
                                            <figure class="category-media">
                                                <img src="images/categories/category2.jpg" alt="category" width="280"
                                                     height="280" />
                                            </figure>
                                        </a>

                                    </div>
                                </div>
                                <div class="col-sm-6 col-lg-3 mb-4">
                                    <div
                                        class="category category-default category-rounded category-absolute overlay-zoom">
                                        <a href="#">
                                            <figure class="category-media">
                                                <img src="images/categories/category2.jpg" alt="category" width="280"
                                                     height="280" />
                                            </figure>
                                        </a>

                                    </div>
                                </div>
                            </div>

                        </div>
                    </section>
                </div>
            </div>

        </main>

        <!-- End Footer -->
    </div>
@endsection('content')
