$.ajax({
     url: "http://localhost:8080/edulib/auth/logedin",
     type: "get",
     xhrFields: {
        withCredentials: true
     },
     success: function (response) {
         if (window.location.href === "http://localhost:8282/edulib/siswa/login.php") {
            window.location.href = "http://localhost:8282/edulib/siswa/dashboard.php"
         } 
     },
     error: function (xhr, satus) {
         if (window.location.href != "http://localhost:8282/edulib/siswa/login.php")  {
            window.location.href = "http://localhost:8282/edulib/siswa/login.php"
         }
     }
});

function logOut() {
   $.ajax({
      url: "http://localhost:8282/edulib/auth/logout",
      type: "get",
      xhrFields: {
         withCredentials: true
      },
      success: function () {
         console.log("ok");
      }
   });
}