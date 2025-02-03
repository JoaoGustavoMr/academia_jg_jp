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





// ABAIXO DAQUI FOI O QUE O GPT MANDOU NA ULTIMA MSG




document.addEventListener("DOMContentLoaded", function () {
    const btnAdicionarAula = document.getElementById("btnAdicionarAula");
    const modalAdicionarAula = document.getElementById("modalAdicionarAula");
    const closeModal = document.querySelector(".close");
    const formAdicionarAula = document.getElementById("formAdicionarAula");

    btnAdicionarAula.addEventListener("click", function () {
        fetch("obter_dados_usuario.php")
            .then(response => response.json())
            .then(data => {
                document.getElementById("instrutor").innerHTML = "";
                document.getElementById("aluno").innerHTML = "";

                if (data.tipo_usuario === "instrutor") {
                    // Instrutor só pode ser ele mesmo
                    document.getElementById("instrutor").innerHTML = `<option value="${data.usuario_id}" selected>${data.nome}</option>`;

                    // Listar todos os alunos
                    data.alunos.forEach(aluno => {
                        document.getElementById("aluno").innerHTML += `<option value="${aluno.id}">${aluno.nome}</option>`;
                    });

                    document.getElementById("tipoAula").value = data.especialidade;
                } else {
                    // Aluno só pode ser ele mesmo
                    document.getElementById("aluno").innerHTML = `<option value="${data.usuario_id}" selected>${data.nome}</option>`;

                    // Listar todos os instrutores
                    data.instrutores.forEach(instrutor => {
                        document.getElementById("instrutor").innerHTML += `<option value="${instrutor.id}" data-especialidade="${instrutor.especialidade}">${instrutor.nome}</option>`;
                    });

                    // Atualiza o tipo de aula ao escolher um instrutor
                    document.getElementById("instrutor").addEventListener("change", function () {
                        const especialidade = this.options[this.selectedIndex].getAttribute("data-especialidade");
                        document.getElementById("tipoAula").value = especialidade;
                    });
                }

                modalAdicionarAula.style.display = "block";
            });
    });

    closeModal.addEventListener("click", function () {
        modalAdicionarAula.style.display = "none";
    });

    formAdicionarAula.addEventListener("submit", function (e) {
        e.preventDefault();

        const formData = new FormData(formAdicionarAula);

        fetch("adicionar_aula.php", {
            method: "POST",
            body: new URLSearchParams(formData)
        })
        .then(response => response.json())
        .then(data => {
            if (data.sucesso) {
                Swal.fire("Sucesso!", "A aula foi adicionada!", "success")
                    .then(() => location.reload());
            } else {
                Swal.fire("Erro!", "Não foi possível adicionar a aula.", "error");
            }
        });
    });
});
