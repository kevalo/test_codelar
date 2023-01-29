import './bootstrap';

(() => {

    //#region search pokemon
    const nameInput = document.querySelector("#name");
    const preview = document.getElementById("preview");

    const inputError = (input) => {
        input.classList.add("border-rose-500");
        input.classList.add("placeholder:text-rose-500");
    }

    const clearInputError = (input) => {
        input.classList.remove("border-rose-500");
        input.classList.remove("placeholder:text-rose-500");
    }

    const searchPokemonForm = document.querySelector("#searchPokemonForm");
    if (searchPokemonForm) {

        nameInput.addEventListener("keyup", () => {
            clearInputError(nameInput);
        });

        searchPokemonForm.addEventListener('submit', async (e) => {
            e.preventDefault();

            clearInputError(nameInput);

            const name = nameInput.value.trim().toLowerCase();

            if (!name) {
                inputError(nameInput);
                return;
            }

            const url = searchPokemonForm.action.replace(/name/, name);
            let response = null;
            try {
                preview.innerHTML = "<span class='text-center m-auto'>Buscando pokemón...</span>";
                response = await axios.get(url);
            } catch (error) {
                response = error.response;
            }

            preview.innerHTML = response.data.html;
        })
    }
    //#endregion search pokemon

    //#region add pokemon
    $(document).on("click", "#addPokemonForm", async (e) => {
        e.preventDefault();
        const form = e.currentTarget;

        let response = null;
        try {
            preview.innerHTML = '<span class="text-center m-auto">Guardando pokemón...</span>';
            let data = new FormData(form);
            response = await axios.post(form.action, data);
        } catch (error) {
            console.log(error);
            response = error.response;
        }

        preview.innerHTML = response.data.htmlPreview;
        document.querySelector("#teamList").innerHTML = response.data.html;
    })
    //#endregion add pokemon

    //#region view moves
    $(document).on("click", ".view-movements", (e) => {
        const element = e.currentTarget;
        const movesDiv = `moves-${element.dataset.id}`;
        $(`#${movesDiv}`).toggleClass("hidden");
    });
    //#endregion view moves

    //#region evolve pokemon
    $(document).on("submit", ".evolve-pokemon", async (e) => {
        e.preventDefault();
        const form = e.currentTarget;
        const evolveStatus = $(`#evolveStatus${form.dataset.id}`);

        let response = null;
        try {
            let data = new FormData(form);
            evolveStatus.text('Evolucionando pokemón...');
            evolveStatus.removeClass("hidden");
            response = await axios.post(form.action, data);
        } catch (error) {
            console.log(error);
            response = error.response;
        }

        if (response.data.found) {
            document.querySelector("#teamList").innerHTML = response.data.html;
        } else {
            evolveStatus.html('No hay más evoluciones.');
        }
    })
    //#endregion evolve pokemon

    //#region remove pokemon
    $(document).on("submit", ".remove-pokemon", async (e) => {
        e.preventDefault();
        const form = e.currentTarget;

        let response = null;
        try {
            let data = new FormData(form);
            response = await axios.post(form.action, data);
        } catch (error) {
            console.log(error);
            response = error.response;
        }

        document.querySelector("#teamList").innerHTML = response.data.html;
    })
    //#endregion remove pokemon

})();
