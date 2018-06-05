document.asyncReady(function() {


    $(window).load(function () {
    var form;
    var isFromModal=false;
        $('.formremovegadget').submit(function(e){
            form = $('.formremovegadget');
            if(!isFromModal) {
                e.preventDefault();
                let type = $(this).data("type");

                if (type == 'gadget') {
                    $.modal.open('<div style="padding:20px">' +
                        '<p>You\'re removing a free gadget from the cart.</p>' +
                        '<p><b>Are you sure?</b></p>' +
                        '<button class="btn btn_confirm_remove_gadget is--primary is--large is--icon-left " data-type="0"><i class="icon--check"></i>Confirm</button>' +
                        '<button class="btn btn_confirm_remove_gadget is--secondary is--large is--icon-left " data-type="1"><i class="icon--cross"></i>Cancel</button>' +
                        '</div>', {
                        title: 'Removing Gadget From Cart',
                        additionalClass: 'confirm_remove_gadget',
                        width: 300,
                        height: 300,
                        closeOnOverlay: false,
                        showCloseButton: true,
                    });
                }
            }
        });

        $(document).on("click", ".btn_confirm_remove_gadget" , function() {

            let type = $(this).data("type");
            if(type==0) {
                isFromModal=true;
                form.submit();
            } else {
                $.modal.close();
            }
        });


    });
});

