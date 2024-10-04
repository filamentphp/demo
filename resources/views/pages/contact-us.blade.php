@extends('.__base')

@section('content')

<div class="page-wrapper">

    <main class="main">
        <nav class="breadcrumb-nav">
            <div class="container">
                <ul class="breadcrumb">
                    <li><a href="demo1.html"><i class="fa-solid fa-house"></i></a></li>
                    <li>Contact Us</li>
                </ul>
            </div>
        </nav>
        <div class="page-header" style="background-image: url(images/page-header/contact-us.jpg)">
            <h1 class="page-title font-weight-bold text-capitalize ls-l">Contact Us</h1>
        </div>
        <div class="page-content mt-10 pt-7">
            <section class="contact-section">
                <div class="container">
                    <div class="row">
                        <div class="col-lg-3 col-md-4 col-sm-6 ls-m mb-4">
                            <div class="grey-section d-flex align-items-center h-100">
                                <div>
                                    <h4 class="mb-2 text-capitalize">Headquarters</h4>
                                    <p>1600 Amphitheatre Parkway<br>New York WC1 1BA</p>

                                    <h4 class="mb-2 text-capitalize">Phone Number</h4>
                                    <p>
                                        <a href="tel:#">1.800.458.56</a><br>
                                        <a href="tel:#">1.800.458.56</a>
                                    </p>

                                    <h4 class="mb-2 text-capitalize">Support</h4>
                                    <p class="mb-4">
                                        <a href="#">support@your-domain.com</a><br>
                                        <a href="#">help@your-domain.com</a><br>
                                        <a href="#">Sale</a>
                                    </p>
                                </div>
                            </div>
                        </div>
                        <div class="col-lg-9 col-md-8 col-sm-6 d-flex align-items-center mb-4">
                            <div class="w-100">
                                @if (session()->has('success'))
                                    <div class="alert alert-success">
                                        {{ session('success') }}
                                    </div>
                                @endif

                                    <div>
                                        @if (session()->has('success'))
                                            <div class="alert alert-success">
                                                {{ session('success') }}
                                            </div>
                                        @endif

                                        <form wire:submit.prevent="submit">
                                            <div class="row mb-2">
                                                <div class="col-12 mb-4">
                                                    <textarea class="form-control" wire:model="message" required placeholder="Message*"></textarea>
                                                </div>
                                                <div class="col-md-6 mb-4">
                                                    <input class="form-control" type="text" wire:model="name" placeholder="Name *" required>
                                                </div>
                                                <div class="col-md-6 mb-4">
                                                    <input class="form-control" type="email" wire:model="email" placeholder="Email *" required>
                                                </div>
                                            </div>
                                            <button class="btn btn-dark btn-rounded" type="submit">Envoyer Message <i class="fa-solid fa-arrow-right"></i></button>
                                        </form>
                                    </div>
                            </div>
                        </div>
                    </div>
                </div>
            </section>
            <!-- End About Section-->


            <!-- Google Maps - Go to the bottom of the page to change settings and map location. -->
            <div class="grey-section google-map" id="googlemaps" style="height: 386px"></div>
            <!-- End Map Section -->
        </div>

    </main>
    <!-- End Main -->

</div>
@endsection
