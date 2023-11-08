const residentes = [];

const handleGetCharacterData = async (characterUrl) => {
    try {
        const response = await axios.get(characterUrl);
        return response.data;
    } catch (error) {
        throw error;
    }
};

const handleSaveCharacter = async (data) => {
    const token = document
        .querySelector('meta[name="csrf-token"]')
        .getAttribute("content");

    try {
        const res = await axios.post("/save-character", data, {
            headers: {
                "Content-Type": "application/json",
                "X-CSRF-TOKEN": token,
            },
        });

        if (res.status === 200) {
            return res.data;
        } else {
            throw new Error("Error en la solicitud");
        }
    } catch (error) {
        throw error;
    }
};

const handleGetResidents = async (id) => {
    if (id < 1) return;
    
    try {
        const response = await axios.get(
            `https://rickandmortyapi.com/api/location/${id}`
        );
        const res = response.data.residents.slice(0, 5);

        const promesas = res.map((characterUrl) =>
            handleGetCharacterData(characterUrl)
        );
        const responses = await Promise.all(promesas);

        const residentesFiltrados = responses.map((residente) => ({
            name: residente.name,
            status: residente.status,
            species: residente.species,
            originName: residente.origin.name,
            image: residente.image,
            episodes: residente.episode.slice(0, 3),
        }));

        residentesFiltrados.sort((a, b) => a.name.localeCompare(b.name));
        residentes.push(...residentesFiltrados);

        await handleSaveCharacter(residentesFiltrados);
    } catch (error) {
        console.error(error);
    }
};

document.addEventListener("DOMContentLoaded", function () {
    const locationInput = document.getElementById("location");
    const searchButton = document.getElementById("searchButton");
    const residentsContainer = document.getElementById("residentsContainer");

    searchButton.addEventListener("click", async function () {
        const locationId = locationInput.value
            ? parseFloat(locationInput.value)
            : null;
        if (!locationId) return;

        document.body.classList.remove("bg-success", "bg-primary", "bg-danger");
        document.body.classList.remove("text-light");

        if (locationId < 50) {
            document.body.classList.add("bg-success");
        } else if (locationId >= 50 && locationId < 80) {
            document.body.classList.add("bg-primary");
        } else {
            document.body.classList.add("bg-danger");
        }

        document.body.classList.add("text-light");

        residentes.length = 0;

        try {
            await handleGetResidents(locationId);
            renderResidents();
        } catch (error) {
            console.error(error);
        }
    });

    const renderResidents = () => {
        residentsContainer.innerHTML = `<h2 class="mt-5">Residents of the searched location:</h2>`;

        if (residentes.length) {
            residentes.forEach((residente, index) => {
                const cardHtml = `
          <div class="col mt-3">
            <div class="card pointer m-auto" style="width: 235px;">
              <img src="${residente.image}" class="card-img" alt="${residente.name}" 
                data-bs-toggle="modal" data-bs-target="#characterModal"
                data-character-index="${index}">
                <h6 class="card-title m-auto py-2">${residente.name}</h6>                
            </div>
          </div>
        `;

                residentsContainer.innerHTML += cardHtml;
            });

            // Agregar el evento clic al abrir el modal
            const characterImages = document.querySelectorAll(
                '[data-bs-toggle="modal"]'
            );
            characterImages.forEach((img) => {
                img.addEventListener("click", (e) => {
                    const characterIndex = e.target.getAttribute(
                        "data-character-index"
                    );
                    populateCharacterModal(characterIndex);
                });
            });
        } else {
            const cardHtml = `
        <div class="residentsIsEmpty">
            <h2>This location has no residents</h2>
        </div>
        `;

            residentsContainer.innerHTML = cardHtml;
        }
    };

    const populateCharacterModal = (characterIndex) => {
        const character = residentes[characterIndex];
        const modal = document.getElementById("characterModal");
        const characterImage = modal.querySelector("#characterImage");
        const characterName = modal.querySelector("#characterName");
        const modalHeader = modal.querySelector("#characterModalLabel");
        const characterStatus = modal.querySelector("#characterStatus");
        const characterSpecies = modal.querySelector("#characterSpecies");
        const characterOrigin = modal.querySelector("#characterOrigin");
        const characterEpisodes = modal.querySelector("#characterEpisodes");

        modalHeader.textContent = `Resident's details: ${character.name}`;
        characterImage.src = character.image;
        characterName.textContent = character.name;
        characterStatus.textContent = character.status;
        characterSpecies.textContent = character.species;
        characterOrigin.textContent = character.originName;

        const episodesHtml = character.episodes.map((episode) => {
            const episodeNumber = episode.match(/\d+/);
            if (episodeNumber) {
                return `<p><a href="${episode}" target="_blank" style="font-size: 16px;">Episode ${episodeNumber[0]}</a></p>`;
            }
            return episode;
        });

        characterEpisodes.innerHTML = episodesHtml.join("");
    };
});
