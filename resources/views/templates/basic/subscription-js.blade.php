<script>
    (function ($) {
        "use strict";
        $(document).ready(function () {
            //filter subscription
            $(document).on('click', '.get_subscription_type_id', function(e){
                e.preventDefault();
                let type_id = $(this).data('type_id');
                $('.get_subscription_type_id').removeClass('active');
                $(this).addClass('active');
                $.ajax({
                    url:"{{ route('subscription.filter')}}",
                    data:{type_id:type_id},
                    method:'GET',
                    success:function(res){

                        if(res.status=='nothing'){
                            $('.search_subscription_result').html('<h3 class="text-center text-danger">'+"{{ __('Nothing Found') }}"+'</h3>');
                        }else{
                            $('.search_subscription_result').html(res);
                        }
                    }

                });
            });
            $(document).on('click', '.choose_plan', function(e){
                let subscription_id = $(this).data('id');
                let subscription_price = $(this).data('price');

                $('#subscription_id').val(subscription_id);
                $('#subscription_price').val(subscription_price);

            });

            // login
            $(document).on('click', '.login_to_buy_a_subscription', function(e){
                $('#buy_subscription_load_spinner').html('<i class="fas fa-spinner fa-pulse"></i>')
                setTimeout(function () {
                    $('#buy_subscription_load_spinner').html('');
                }, 10000);
                e.preventDefault();
                let username = $('#username').val();
                let password = $('#password').val();
                let subscription_price = $('#subscription_price').val();
                let erContainer = $(".error-message");
                erContainer.html('');
                $.ajax({
                    url:"{{ route('user.post-login')}}",
                    data:{username:username,password:password},
                    method:'POST',
                    error:function(res){
                        let errors = res.responseJSON;
                        erContainer.html('<div class="alert alert-danger"></div>');
                        $.each(errors.errors, function(index,value){
                            erContainer.find('.alert.alert-danger').append('<p>'+value+'</p>');
                        });
                    },
                    success: function(res){

                        if(res.status == 'failed'){
                            erContainer.html('<div class="alert alert-danger">'+res.msg+'</div>');
                        }else{
                            localStorage.setItem("showChoosePlanModal", "true");
                            location.reload();

                        }
                    }

                });
            });

            //buy subscription-load spinner
            $(document).on('click','#confirm_buy_subscription_load_spinner',function(){
                //Image validation
                let manual_payment = $('#order_from_user_wallet').val();
                if(manual_payment == 'manual_payment') {
                    let manual_payment_image = $('input[name="manual_payment_image"]').val();
                    if(manual_payment_image == '') {
                        toastr_warning_js("{{__('Image field is required')}}")
                        return false
                    }
                }

                $('#buy_subscription_load_spinner').html('<i class="fas fa-spinner fa-pulse"></i>')
                setTimeout(function () {
                    $('#buy_subscription_load_spinner').html('');
                }, 10000);
            });

            $(document).on("click", ".toggle-password", function(e) {
                e.preventDefault();
                let inputPass = $(this).parent().find("input");
                $(this).toggleClass("show-pass");
                if (inputPass.attr("type") == "password") {
                    inputPass.attr("type", "text");
                } else {
                    inputPass.attr("type", "password");
                }
            });
        });
        $(document).ready(function() {
            if (localStorage.getItem("showChoosePlanModal") === "true") {
                localStorage.removeItem("showChoosePlanModal"); // Remove the flag
                $('.choose_plan').trigger('click'); // Simulate click to show modal
            }
        });
    })(jQuery);
</script>
