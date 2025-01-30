document.addEventListener("DOMContentLoaded", function () {
    // Edição de data (mantém o que já fizemos)
    document.querySelectorAll(".editar").forEach(button => {
        button.addEventListener("click", function () {
            const aulaID = this.getAttribute("data-id");
            const aulaDataAtual = this.getAttribute("data-aulaData");

            const novaData = prompt("Digite a nova data da aula (YYYY-MM-DD):", aulaDataAtual);

            if (novaData && novaData !== aulaDataAtual) {
                fetch("atualizar_aula.php", {
                    method: "POST",
                    headers: { "Content-Type": "application/x-www-form-urlencoded" },
                    body: `id=${aulaID}&nova_data=${novaData}`
                })
                .then(response => response.json())
                .then(data => {
                    if (data.sucesso) {
                        alert("Data atualizada com sucesso!");
                        location.reload();
                    } else {
                        alert("Erro ao atualizar a data.");
                    }
                })
                .catch(error => console.error("Erro:", error));
            }
        });
    });

    // Exclusão de aula
    document.querySelectorAll(".excluir").forEach(button => {
        button.addEventListener("click", function () {
            const aulaID = this.getAttribute("data-id");

            if (confirm("Tem certeza que deseja excluir esta aula?")) {
                fetch("excluir_aula.php", {
                    method: "POST",
                    headers: { "Content-Type": "application/x-www-form-urlencoded" },
                    body: `id=${aulaID}`
                })
                .then(response => response.json())
                .then(data => {
                    if (data.sucesso) {
                        alert("Aula excluída com sucesso!");
                        location.reload();
                    } else {
                        alert("Erro ao excluir a aula.");
                    }
                })
                .catch(error => console.error("Erro:", error));
            }
        });
    });
});





document.addEventListener("DOMContentLoaded", function () {
    document.getElementById("logout").addEventListener("click", function () {
        if (confirm("Tem certeza que deseja sair?")) {
            window.location.href = "logout.php";
        }
    });
});

