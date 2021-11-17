function readURL(input, imgControlName) {
    if (input.files && input.files[0]) {
        var reader = new FileReader();
        reader.onload = function (e) {
            $(imgControlName).attr('src', e.target.result);
        }
        reader.readAsDataURL(input.files[0]);
    }
}
// File 1 image
$("#file1").change(function () {
    $(".file1_preview").show();
    var imgControlName = "#file1_img";
    readURL(this, imgControlName);
    $('.preview_file1').addClass('it');
    $('.btn-rmv1').addClass('rmv');
});

$("#remove_btn1").click(function (e) {
    e.preventDefault();
    $("#file1").val("");
    $("#file1_img").attr("src", "");
    $('.preview_file1').removeClass('it');
    $('.btn-rmv1').removeClass('rmv');
    $(".file1_preview").hide();
});

// File 2 image
$("#file2").change(function () {
    $(".file2_preview").show();
    var imgControlName = "#file2_img";
    readURL(this, imgControlName);
    $('.preview_file2').addClass('it');
    $('.btn-rmv2').addClass('rmv');
});

$("#remove_btn2").click(function (e) {
    e.preventDefault();
    $("#file2").val("");
    $("#file2_img").attr("src", "");
    $('.preview_file2').removeClass('it');
    $('.btn-rmv2').removeClass('rmv');
    $(".file2_preview").hide();
});

// File 3 image
$("#file3").change(function () {
    $(".file3_preview").show();
    var imgControlName = "#file3_img";
    readURL(this, imgControlName);
    $('.preview_file3').addClass('it');
    $('.btn-rmv3').addClass('rmv');
});

$("#remove_btn3").click(function (e) {
    e.preventDefault();
    $("#file3").val("");
    $("#file3_img").attr("src", "");
    $('.preview_file3').removeClass('it');
    $('.btn-rmv3').removeClass('rmv');
    $(".file3_preview").hide();
});

// File 4 image
$("#file4").change(function () {
    $(".file4_preview").show();
    var imgControlName = "#file4_img";
    readURL(this, imgControlName);
    $('.preview_file4').addClass('it');
    $('.btn-rmv4').addClass('rmv');
});

$("#remove_btn4").click(function (e) {
    e.preventDefault();
    $("#file4").val("");
    $("#file4_img").attr("src", "");
    $('.preview_file4').removeClass('it');
    $('.btn-rmv4').removeClass('rmv');
    $(".file4_preview").hide();
});

// Form Validation 

// Example starter JavaScript for disabling form submissions if there are invalid fields
(function () {
    'use strict'

    // Fetch all the forms we want to apply custom Bootstrap validation styles to
    var forms = document.querySelectorAll('.needs-validation')

    // Loop over them and prevent submission
    Array.prototype.slice.call(forms)
        .forEach(function (form) {
            form.addEventListener('submit', function (event) {
				var issueVal = '';
				if($("#basic_issue").length > 0){
					var issueVal = $("#basic_issue").val();
				}
                if (!form.checkValidity()) {
					$('.lostErrorMsg').show();
                    event.preventDefault()
                    event.stopPropagation()
                }
                else if (issueVal.includes('-----')) {
                    $('.error-message').html('Please select a valid issue').show();
                    $('#basic_issue').addClass('cat_error');
                    $('.lostErrorMsg').show();
					event.preventDefault()
                    event.stopPropagation()
                }
                else {
					$('.lostErrorMsg').hide();
                    $('.divLoading').show();
                }
                form.classList.add('was-validated')
            }, false)
        })
})()

// Check only Nuumber
//called when key is pressed in textbox
$("#phone").keypress(function (e) {
    $("#errphonemsg").hide();

    //if the letter is not digit then display error and don't type anything
    if (e.which != 8 && e.which != 0 && (e.which < 48 || e.which > 57)) {
        //display error message
        $("#errphonemsg").html("Please enter valid Phone Number").show();
        return false;
    }
});

// ISSUE SELECT VALIDATION 
$("#basic_issue").change(function () {
    var issueVal = $(this).val();
    if (issueVal.includes('-----')) {
        $('.error-message').html('Please select a valid issue').show();
        $('#basic_issue').removeClass('cat_success');
        $('#basic_issue').addClass('cat_error');
    } else {
        $('.error-message').hide();
        $('#basic_issue').removeClass('cat_error');
        $('#basic_issue').addClass('cat_success');
    }
});

//Close Ticket
$('#ticketClose').click(function () {

    if (confirm('If the Guest is satisfied and this ticket is complete, click OK to close this ticket. \r\n If the ticket is incomplete, click cancel to leave the ticket open.')) {
        $('#submitModal').modal("show");
    } else {

        //alert('Why did you press cancel? You should have confirmed');
    }

})