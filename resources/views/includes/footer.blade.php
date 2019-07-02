<div id="back-to-top">
    <a href="#top" aria-label="Go to top"><i class="ui-arrow-up"></i></a>
</div>
</div> <!-- end main container -->

<!-- Footer -->
<footer class="footer">
    <div class="container">
        <div class="footer__widgets">
            <div class="row">

                <div class="col-lg-3 col-md-6">
                    <div class="widget">
                        <h4 style="color: white">OnlineBooksReview</h4>
                        <p class="mt-20">You will get reviews and recommendation of best online books. Get suggestion of buying best books from online.</p>
                    </div>
                </div>

                <div class="col-lg-3 col-md-6">
                    <h4 class="widget-title white">Be Social</h4>
                    <div class="white">Stay with us</div>
                    <div class="socials mt-20">
                        <a href="https://facebook.com/onlinebooksreview" class="social-facebook" aria-label="facebook"><i class="ui-facebook"></i></a>
                        <a href="https://twitter.com/onlinebooks24" class="social-twitter" aria-label="twitter"><i class="ui-twitter"></i></a>
                        <a href="https://plus.google.com/b/110233331450185953116/" class="social-google-plus" aria-label="google+"><i class="ui-google"></i></a>
                        <a href="https://www.linkedin.com/company-beta/13346322" class="social-linkedin" aria-label="linkedin"><i class="ui-linkedin"></i></a>
                    </div>
                </div>


                <div class="col-lg-3 col-md-6">
                    <div class="widget widget_nav_menu">
                        <h4 class="widget-title white">Useful Links</h4>
                        <ul>
                            <li><a href="/advertise-us">Advertise Us</a></li>
                            <li><a href="/privacy-policy">Privacy Policy</a></li>
                            <li><a href="/refund-policy">Refund Policy</a></li>
                            <li><a href="/terms-of-service">Terms of service</a></li>
                            <li><a href="/contact">Contact</a></li>
                        </ul>
                    </div>
                </div>

                <div class="col-lg-3 col-md-6">
                    <div class="widget widget__newsletter">
                        <h4 class="widget-title white">subscribe</h4>
                        <p>Join our Newsletter</p>

                        <form class="mc4wp-form" method="post">
                            <div class="mc4wp-form-fields">
                                <p>
                                    <i class="mc4wp-form-icon ui-email"></i>
                                    <input type="email" name="email" class="update_email" placeholder="Your email" required="">
                                </p>
                                <p>
                                    <input type="submit" class="btn btn-md btn-color btn-subscribe" value="Subscribe">
                                </p>
                            </div>
                        </form>

                    </div>
                </div>

            </div>
        </div>
    </div> <!-- end container -->

    <div class="footer__bottom">
        <div class="container text-center">
            <span class="copyright">
              <p>© 2018 <a href="#"><b style="color: #87B832">Online Books Review</b></a>, All Rights Reserved.</p>
            </span>
        </div>
    </div> <!-- end bottom footer -->
</footer> <!-- end footer -->
<!-- Footer -->
</main> <!-- end main-wrapper -->

<!-- jQuery Scripts -->
<script type="text/javascript" src="{{ asset('js/jquery.min.js') }}"></script>
<script type="text/javascript" src="{{ asset('js/bootstrap.min.js') }}"></script>
<script type="text/javascript" src="{{ asset('js/easing.min.js') }}"></script>
<script type="text/javascript" src="{{ asset('js/owl-carousel.min.js') }}"></script>
<script type="text/javascript" src="{{ asset('js/modernizr.min.js') }}"></script>
<script type="text/javascript" src="{{ asset('js/scripts.js') }}"></script>
<script type="text/javascript"  src="{{ asset('/js/js.cookie.min.js') }}"></script>
<script src="//z-na.amazon-adsystem.com/widgets/onejs?MarketPlace=US&adInstanceId=fd2721e7-db67-4cdb-b312-1319ef936081"></script>

<script>
    if(Cookies.get('email') != undefined){
        var category_id = $('.category_id').data('value');
        var email = Cookies.get('email');
        if(category_id != undefined){
            $.ajax("/update-category-subscriber?email=" + email + "&category_id=" + category_id, {
                success: function(data) {
                }
            });
        }
    } else {
        setTimeout(function() {
            $('#subscribe-modal').modal();
        }, 60000);
    }

    $('.btn-subscribe').click(function(e){
        e.preventDefault();
        var email = $('.email').val();
        var category_id = $('.category_id').data('value');
        if(isEmail(email)){
            $.ajax("/subscribe-now?email=" + email, {
                success: function(data) {
                    $('.close').click();

                    if(category_id != undefined){
                        $.ajax("/update-category-subscriber?email=" + email + "&category_id=" + category_id, {
                            success: function(data) {
                            }
                        });
                    }

                    window.setTimeout(function(){
                        alert("Thanks for subscribing to our newsletter.");
                    }, 1000);

                }
            });
        } else {
            alert('Email address is invalid.');
        }
    });

    $('.update_email').on('blur', function() {
        $('.email').val($(this).val());
    });

    function isEmail(email) {
        var regex = /^([a-zA-Z0-9_.+-])+\@(([a-zA-Z0-9-])+\.)+([a-zA-Z0-9]{2,4})+$/;
        return regex.test(email);
    }

</script>

