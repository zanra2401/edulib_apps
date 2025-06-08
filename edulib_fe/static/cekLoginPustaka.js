$.ajax({
     url: "http://localhost:8080/edulib/auth/logedin-pustaka",
     type: "get",
     xhrFields: {
        withCredentials: true
     },
     success: function (response) {
         console.log(response)
         if (window.location.href === "http://localhost:8282/edulib/pustakawan/login/" || window.location.hreef == "http://localhost:8282/edulib/pustakawan/login/index.php")  {
            window.location.href = "http://localhost:8282/edulib/pustakawan/buku"
         } 
     },
     error: function (xhr, satus) {
         if (window.location.href != "http://localhost:8282/edulib/pustakawan/login/" && window.location.href != "http://localhost:8282/edulib/pustakawan/login/index.php")  {
            window.location.href = "http://localhost:8282/edulib/pustakawan/login"
         }
     }
});

