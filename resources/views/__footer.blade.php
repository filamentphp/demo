<footer class="footer">
    <div class="container">
        <div class="footer-middle">
            <div class="row">
                <div class="col-lg-4 col-md-12">
                    <div class="widget widget-about pl-0">
                        <a href="{{assert("images/payment.png")}}" class="logo-footer">
                            <img src="{{asset("images/logo/logo.jpg")}}" alt="logo-footer" width="154" height="43" />
                        </a>
                        <div class="widget-body">
                            <p>Une entreprise dynamique depuis plus de 50 ans au service permanent du coiffeur et de l'esthéticienne,
                                qui étudie, produit et agit pour offrir un service toujours meilleur  et une gamme de produits de
                                qualité supérieure. <br>
                            </p>
                            <a href="mailto:contact@muster-dikson.co">contact@muster-dikson.com</a>
                        </div>
                    </div>
                    <!-- End Widget -->
                </div>
                <div class="col-lg-8 col-md-12">
                    <div class="row">
                        <div class="col-md-6 col-sm-12">
                            <div class="widget">
                                <h4 class="widget-title">À Propos de Nous</h4>
                                <ul class="widget-body">
                                    <li>
                                        <a href="{{route('about_us')}}">À Propos de Nous</a>
                                    </li>
                                    <li>
                                        <a href="{{route('contact_us')}}">Contactez Nous</a>
                                    </li>
                                </ul>
                            </div>
                            <!-- End Widget -->
                        </div>
                        <div class="col-md-6 col-sm-12">
                            <div class="widget">
                                <h4 class="widget-title">Service Client</h4>
                                <ul class="widget-body">
                                    <li>
                                        <a href="#">Méthodes de Paiement</a>
                                    </li>
{{--                                    <li>--}}
{{--                                        <a href="#">Garantie de Remboursement !</a>--}}
{{--                                    </li>--}}
{{--                                    <li>--}}
{{--                                        <a href="#">Retours</a>--}}
{{--                                    </li>--}}
{{--                                    <li>--}}
{{--                                        <a href="#">Expédition</a>--}}
{{--                                    </li>--}}
                                        <li>
                                            <a href="#">Service Client</a>
                                        </li>
                                        <li>
                                            <a href="#">Conditions Générales</a>
                                        </li>
                                </ul>
                            </div>
                            <!-- End Widget -->
                        </div>


                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- End Footer Middle -->
    <div class="container">
        <div class="footer-bottom">
            <div class="footer-left">
                <figure class="payment">
                    <img src="{{asset("images/payment.png")}}" alt="payment" width="159" height="29" />
                </figure>
            </div>
            <div class="footer-center">
                <p class="copyright">Muster & Dikson &copy; 2024</p>
            </div>
            <div class="footer-right">
                <div class="social-links">
                    <a href="#" title="social-list" class="social-link social-facebook fab fa-facebook-f"></a>
                    <a href="#" title="social-list" class="social-link social-twitter fab fa-twitter"></a>
                    <a href="#" title="social-list" class="social-link social-linkedin fab fa-linkedin-in"></a>
                </div>
            </div>
        </div>
    </div>
    <!-- End Footer Bottom -->
</footer>
