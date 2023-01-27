import axios from 'axios';
import './bootstrap';

(() => {

    const inputError = (input) => {
        input.classList.add("border-rose-500");
        input.classList.add("placeholder:text-rose-500");
    }

    const clearInputError = (input) => {
        input.classList.remove("border-rose-500");
        input.classList.remove("placeholder:text-rose-500");
    }

    const searchPokemonForm = document.querySelector("#formSearchPokemon");
    if (searchPokemonForm) {
        const nameInput = document.querySelector("#name");
        nameInput.addEventListener("keyup", () => {
            clearInputError(nameInput);
        });

        const preview = document.getElementById("preview");

        searchPokemonForm.addEventListener('submit', async (e) => {
            e.preventDefault();

            clearInputError(nameInput);

            const name = nameInput.value.trim();

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
})();