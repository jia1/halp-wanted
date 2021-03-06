<!-- Warning: _head.blade.php also includes jQuery -->

<script
src="https://code.jquery.com/jquery-3.2.1.min.js"
integrity="sha256-hwg4gsxgFZhOsEEamdOYGBf13FyQuiTwlAQgxVSNgt4="
crossorigin="anonymous"></script>

<script>

function getUserName(callback){
    FB.api('/me', function(response) {
        callback(response.name);
    });
}

function getProfilePicture(callback){
    FB.api('/me/picture?type=normal', function(response) {
        callback(response.data.url);
    });
}
</script>

<script>
$(document).ready(function() {
    $.ajaxSetup({ cache: true });
    $.getScript('//connect.facebook.net/en_US/sdk.js', function(){
        FB.init({appId : '1592283090803235',
            autoLogAppEvents : true,
            cookie           : true,
            status           : true,
            xfbml            : true,
            version          : 'v2.10'
        });

        FB.AppEvents.logPageView();

        FB.getLoginStatus(function(response) {
            if (response.status === 'connected') {
                // Is logged in to Facebook and has authorized this app
                $('.navbar-nav .ml-auto').hide();
                $('.nav-internal').show();

                $('#mainNav').hide();
                $('#second').hide();

                getUserName((name)=>{
                    document.getElementById('userName').innerHTML=name;
                });


                getProfilePicture((name)=>{
                    document.getElementById('profilePic').innerHTML="<img style='border: 3px solid #3a3a3a;' src='"+name+"'/>";;
                });

                $('.logout').click(function(response) {
                    swal({title: 'Are you sure?',
                        text: 'You are about to log out from Addvise.',
                        showCancelButton: true,
                        confirmButtonText: 'Yes',
                        cancelButtonText: 'No'
                    }).then(
                        function () {
                            swal({title: 'Logging out...',
                                text: 'Have a great day ahead!',
                                confirmButtonText: 'Yes, I will!',
                                timer: 5000
                            }).then(function () {
                                FB.logout();
                                window.location.replace("/");
                                $('.nav-internal').hide();
                                $('.nav-external').show();
                            });
                        }, function (dismiss) {
                            if (dismiss === 'cancel') {
                                swal({title: ':D',
                                    text: 'Please stay here for ever and ever!',
                                    confirmButtonText: 'Yes!',
                                    timer: 5000
                                });
                            }
                        });
                });
            } else {
                // Either:
                // Is logged in to Facebook but has not authorized this app, OR
                // Is not logged in to Facebook
                $('.nav-external').show();
                $('.navbar-nav .ml-auto').show();

                $('#login').click(function() {
                    FB.login(function(response) {
                        if (response.authResponse) {
                            window.location.replace("{{ url('home') }}");
                        } else {
                            swal({title: 'Facebook login failure',
                                text: 'You closed the login window, right?',
                                showConfirmButton: false,
                                timer: 1000
                            });
                        }
                    }, {scope: 'public_profile,email',
                        return_scopes: true
                    });
                });
            }
        });

        // Translate FB user IDs to names
        setTimeout(function () {
            translateFB(FB);
        }, 1800);
    });
});
</script>

