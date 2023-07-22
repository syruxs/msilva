function logoutConfirmation() {
    swal({
        title: "¿Estás seguro?",
        text: "Una vez que salgas, no podrás recuperar la sesión actual.",
        icon: "warning",
        buttons: true,
        dangerMode: true,
    })
    .then((willDelete) => {
        if (willDelete) {
            window.location.href = "../logout.php"; // Redirecciona al script de logout si se confirma
        } else {
            swal("Tu sesión actual está segura.");
        }
    });
}