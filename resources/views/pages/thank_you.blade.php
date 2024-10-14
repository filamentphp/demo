@extends('.__base_main')

@section('content')

    <div class="page-wrapper">
        <main class="main order">
            <div class="page-content pt-7 pb-10 mb-10">
                <div class="step-by pr-4 pl-4">
                    <h3 class="title title-simple title-step">1. Panier d'Achat</h3>
                    <h3 class="title title-simple title-step">2. Caisse</h3>
                    <h3 class="title title-simple title-step active">3. Commande Terminée</h3>
                </div>
                <div class="container mt-8">
                    <div class="order-message mr-auto ml-auto">
                        <div class="icon-box d-inline-flex align-items-center">
                            <div class="icon-box-icon mb-0">
                                <svg version="1.1" id="Layer_1" xmlns="http://www.w3.org/2000/svg"
                                     xmlns:xlink="http://www.w3.org/1999/xlink" x="0px" y="0px" viewBox="0 0 50 50"
                                     enable-background="new 0 0 50 50" xml:space="preserve">
                                <g>
                                    <path fill="none" stroke-width="3" stroke-linecap="round"
                                          stroke-linejoin="bevel" stroke-miterlimit="10" d="
                                        M33.3,3.9c-2.7-1.1-5.6-1.8-8.7-1.8c-12.3,0-22.4,10-22.4,22.4c0,12.3,10,22.4,22.4,22.4c12.3,0,22.4-10,22.4-22.4
                                        c0-0.7,0-1.4-0.1-2.1"></path>
                                    <polyline fill="none" stroke-width="4" stroke-linecap="round"
                                              stroke-linejoin="bevel" stroke-miterlimit="10" points="
                                        48,6.9 24.4,29.8 17.2,22.3 	"></polyline>
                                </g>
                            </svg>
                            </div>
                            <div class="icon-box-content text-left">
                                <h5 class="icon-box-title font-weight-bold lh-1 mb-1">Merci !</h5>
                                <p class="lh-1 ls-m">Votre commande a été bien reçue</p>
                            </div>
                        </div>
                    </div>

                    <a href="{{route('index')}}" class="btn btn-icon-left btn-dark btn-back btn-rounded btn-md mb-4"><i
                            class="d-icon-arrow-left"></i> Retour à la Liste</a>
                </div>
            </div>
        </main>
        <!-- End Footer -->
    </div>


@endsection('content')
