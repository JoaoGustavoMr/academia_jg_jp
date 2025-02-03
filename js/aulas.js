document.addEventListener("DOMContentLoaded", function () {
    document.getElementById("logout").addEventListener("click", function () {
        if (confirm("Tem certeza que deseja sair?")) {
            window.location.href = "logout.php";
        }
    });
});

document.addEventListener("DOMContentLoaded", function () {
    const botoesExcluir = document.querySelectorAll(".excluir");
    const botoesEditar = document.querySelectorAll(".editar");

    // Função para excluir aula com SweetAlert
    botoesExcluir.forEach(botao => {
        botao.addEventListener("click", function () {
            const aulaId = this.getAttribute("data-id");

            Swal.fire({
                title: "Tem certeza?",
                text: "Esta ação não pode ser desfeita!",
                icon: "warning",
                showCancelButton: true,
                confirmButtonColor: "#d33",
                cancelButtonColor: "#3085d6",
                confirmButtonText: "Sim, excluir!",
                cancelButtonText: "Cancelar"
            }).then((result) => {
                if (result.isConfirmed) {
                    fetch("excluir_aula.php", {
                        method: "POST",
                        headers: { "Content-Type": "application/x-www-form-urlencoded" },
                        body: "id=" + encodeURIComponent(aulaId)
                    })
                    .then(response => response.json())
                    .then(data => {
                        if (data.sucesso) {
                            Swal.fire("Excluído!", "A aula foi excluída com sucesso.", "success")
                            .then(() => location.reload());
                        } else {
                            Swal.fire("Erro!", "Ocorreu um erro ao excluir a aula.", "error");
                        }
                    })
                    .catch(error => console.error("Erro:", error));
                }
            });
        });
    });

    // Função para editar a data da aula usando um modal
    botoesEditar.forEach(botao => {
        botao.addEventListener("click", function () {
            const aulaId = this.getAttribute("data-id");
            const aulaDataAtual = this.getAttribute("data-aulaData");

            Swal.fire({
                title: "Editar Data da Aula",
                html: `<input type="date" id="novaData" class="swal2-input" value="${aulaDataAtual}">`,
                showCancelButton: true,
                confirmButtonText: "Salvar",
                cancelButtonText: "Cancelar",
                preConfirm: () => {
                    const novaData = document.getElementById("novaData").value;
                    if (!novaData) {
                        Swal.showValidationMessage("Por favor, selecione uma nova data.");
                    }
                    return novaData;
                }
            }).then((result) => {
                if (result.isConfirmed) {
                    fetch("atualizar_aula.php", {
                        method: "POST",
                        headers: { "Content-Type": "application/x-www-form-urlencoded" },
                        body: "id=" + encodeURIComponent(aulaId) + "&nova_data=" + encodeURIComponent(result.value)
                    })
                    .then(response => response.json())
                    .then(data => {
                        if (data.sucesso) {
                            Swal.fire("Atualizado!", "A data da aula foi alterada com sucesso.", "success")
                            .then(() => location.reload());
                        } else {
                            Swal.fire("Erro!", "Não foi possível atualizar a data da aula.", "error");
                        }
                    })
                    .catch(error => console.error("Erro:", error));
                }
            });
        });
    });
});
