$.ajax({
     url: "http://localhost:9191/edulib/auth/logedin-admin",
     type: "get",
     xhrFields: {
        withCredentials: true
     },
     success: function (response) {
         console.log(response)
         if (window.location.href === "http://localhost:8282/edulib/admin/login.php")  {
            window.location.href = "http://localhost:8282/edulib/admin/akun.php"
         } 
     },
     error: function (xhr, satus) {
         if (window.location.href != "http://localhost:8282/edulib/admin/login.php")  {
            window.location.href = "http://localhost:8282/edulib/admin/login.php"
         }
     }
});

