document.addEventListener("DOMContentLoaded", function () {
    document.getElementById("logout").addEventListener("click", function () {
        if (confirm("Tem certeza que deseja sair?")) {
            window.location.href = "logout.php";
        }
    });
});