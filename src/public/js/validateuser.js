$("#edit-user-submit").on("click",function(e){
    e.preventDefault();

    var email = document.getElementById('email');
    var uri_link = document.getElementById('uri_link');

    var json = {
        email: email.value,
        uri_link: uri_link.value
    };

    //the ajax call returns true if the email exists
    $.get("/api/validateuseredit", json).done(function(data) {
        alert("data loaded: " + data);

        email.setCustomValidity(data.email);
        uri_link.setCustomValidity(data.uri_link);

        //then we submit the form
        //$("form").submit();
    });
});