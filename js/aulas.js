document.addEventListener("DOMContentLoaded", function () {
    document.body.addEventListener("click", async function (event) {
        if (event.target.classList.contains("excluir")) {
            const aulaId = event.target.getAttribute("data-id");

            const result = await Swal.fire({
                title: "Tem certeza?",
                text: "Esta ação não pode ser desfeita!",
                icon: "warning",
                showCancelButton: true,
                confirmButtonColor: "#d33",
                cancelButtonColor: "#3085d6",
                confirmButtonText: "Sim, excluir!",
                cancelButtonText: "Cancelar"
            });

            if (result.isConfirmed) {
                try {
                    const response = await fetch("excluir_aula.php", {
                        method: "POST",
                        headers: { "Content-Type": "application/x-www-form-urlencoded" },
                        body: `id=${encodeURIComponent(aulaId)}`
                    });

                    const data = await response.json();

                    if (data.sucesso) {
                        Swal.fire("Excluído!", "A aula foi excluída com sucesso.", "success")
                            .then(() => location.reload());
                    } else {
                        Swal.fire("Erro!", "Ocorreu um erro ao excluir a aula.", "error");
                    }
                } catch (error) {
                    console.error("Erro:", error);
                }
            }
        }
    });

    // ✅ Função para editar a data da aula
    document.body.addEventListener("click", async function (event) {
        if (event.target.classList.contains("editar")) {
            const aulaId = event.target.getAttribute("data-id");
            const aulaDataAtual = event.target.getAttribute("data-aulaData");

            const result = await Swal.fire({
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
            });

            if (result.isConfirmed) {
                try {
                    const response = await fetch("atualizar_aula.php", {
                        method: "POST",
                        headers: { "Content-Type": "application/x-www-form-urlencoded" },
                        body: `id=${encodeURIComponent(aulaId)}&nova_data=${encodeURIComponent(result.value)}`
                    });

                    const data = await response.json();

                    if (data.sucesso) {
                        Swal.fire("Atualizado!", "A data da aula foi alterada com sucesso.", "success")
                            .then(() => location.reload());
                    } else {
                        Swal.fire("Erro!", "Não foi possível atualizar a data da aula.", "error");
                    }
                } catch (error) {
                    console.error("Erro:", error);
                }
            }
        }
    });

    // ✅ Modal de Adicionar Aula
    const modal = document.getElementById("modal-aula");
    const btn = document.getElementById("adicionar-aula");
    const closeModal = document.querySelector(".close");

    btn.addEventListener("click", async function () {
        try {
            const response = await fetch("obter_dados_instrutor.php");
            const data = await response.json();

            if (data.sucesso) {
                document.getElementById("tipoAula").value = data.especialidade;
            } else {
                console.error("Erro ao buscar especialidade:", data.erro);
            }

            modal.style.display = "block";
        } catch (error) {
            console.error("Erro ao carregar especialidade:", error);
        }
    });

    closeModal.addEventListener("click", function () {
        modal.style.display = "none";
    });

    window.addEventListener("click", function (event) {
        if (event.target === modal) {
            modal.style.display = "none";
        }
    });

    // ✅ Envio do formulário de Adicionar Aula
    document.getElementById("formAdicionarAula").addEventListener("submit", async function (e) {
        e.preventDefault();

        const formData = new FormData(this);

        try {
            const response = await fetch("adicionar_aula.php", {
                method: "POST",
                body: new URLSearchParams(formData)
            });

            const data = await response.json();

            if (data.sucesso) {
                Swal.fire("Sucesso!", "A aula foi adicionada!", "success")
                    .then(() => location.reload());
            } else {
                Swal.fire("Erro!", "Não foi possível adicionar a aula.", "error");
            }
        } catch (error) {
            console.error("Erro ao adicionar aula:", error);
        }
    });
});
